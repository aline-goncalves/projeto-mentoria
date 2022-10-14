<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    private const AGED_BRIE = 'Aged Brie';
    private const BACKSTAGE = 'Backstage passes to a TAFKAL80ETC concert';
    private const SULFURAS = 'Sulfuras, Hand of Ragnaros';

    public function provideSellInDays(): iterable {
        yield '0 days' => [0];
        yield '1 day' => [1];
        yield '100 days' => [100];
        yield '2000 days' => [2000];
        yield 'negative' => [-1];
    }

    /** @dataProvider provideSellInDays */
    public function testUpdateQuality_ShouldNotUpdateQualityForSulfurasItem(
        int $sellInDays
    ): void
    {
        //arrange
        $items = [new Item(self::SULFURAS, $sellInDays, 80)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(80, $items[0]->quality);
    }

    public function testUpdateQuality_ShouldNotUpdateQualityForSulfurasItem_EvenAfterNDays(): void {
        $daysAhead = 10;
        $sellIn = $daysAhead;

        $item = new Item(self::SULFURAS, $sellIn, 80);

        $this->executeUpdateQualityNTimes($daysAhead, [$item]);

        $this->assertEquals(80, $item->quality);
    }

    public function testUpdateQuality_ShouldIncreaseTwoAtQualityForItemBackstageIfSellInEqualsTen():void
    {
        //arrange
        $items = [new Item("Backstage passes to a TAFKAL80ETC concert", 10, 5)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(7, $items[0]->quality);
    }

    public function testUpdateQuality_ShouldIncreaseTwoAtQualityForItemBackstageIfSellInMinorThenTen():void
    {
        //arrange
        $items = [new Item("Backstage passes to a TAFKAL80ETC concert", 10, 5)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(7, $items[0]->quality);
    }

    public function  testUpdateQuality_ShouldIncreaseThreeAtQualityForItemBackstageIfSellInEqualsFive():void
    {
        //arrange
        $items = [new Item("Backstage passes to a TAFKAL80ETC concert", 5, 5)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(8, $items[0]->quality);
    }

    public function testUpdateQuality_ShouldIncreaseThreeAtQualityForItemBackstageIfSellInMinorThenFive():void
    {
        //arrange
        $items = [new Item("Backstage passes to a TAFKAL80ETC concert", 4, 5)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(8, $items[0]->quality);
    }

    public function provideItemsWithExpiredSellInAndTheirExpectedFinalQuality(): iterable {
        //     itemWithExpiredSellIn                                          expectedFinalQuality
        yield [new Item("Elixir of the Mongoose", -1, 2),           0         ];
        yield [new Item("Elixir of the Mongoose", -2, 2),           0         ];

        yield [new Item("Elixir of the Mongoose", -1, 10),          8         ];
        yield [new Item("Meu Item Favorito", -1, 10),               8         ];
        yield [new Item("", -1, 10),                                8         ];
    }

    /** @dataProvider provideItemsWithExpiredSellInAndTheirExpectedFinalQuality */
    public function testUpdateQuality_ShouldDecreaseTwoTimesFasterTheItemQualityIfSellInIsExpired(
        Item $item,
        int $expectedFinalQuality
    ):void
    {
        //arrange
        $glidedRose = new GildedRose([$item]);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals($expectedFinalQuality, $item->quality);
    }

    public function testUpdateQuality_ShoudIncreaseQualityForAgedBrieWhenSellInDecreases():void
    {
        //arrange
        $items = [new Item("Aged Brie", 2, 0)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(1, $items[0]->quality);
    }

    public function testUpdateQuality_AItemQualityShouldNotBeNegative():void
    {
        //arrange
        $items = [new Item("+5 Dexterity Vest", -6, 0)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(0, $items[0]->quality);
    }

    public function testUpdateQuality_AItemQualityShouldNotBeMoreThenFifty():void
    {
        //arrange
        $items = [new Item("Aged Brie", 2, 50)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(50, $items[0]->quality);
    }

    private function executeUpdateQualityNTimes(int $daysAhead, array $items): void {
        $gildedRose = new GildedRose($items);

        for ($i = 0; $i < $daysAhead; $i++) {
            $gildedRose->updateQuality();
        }
    }
}
