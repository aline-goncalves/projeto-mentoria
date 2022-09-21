<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testUpdateQuality_itemSulfurasImmutableQuality():void
    {
        //arrange
        $items = [new Item("Sulfuras, Hand of Ragnaros", 0, 80)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(80, $items[0]->quality);
    }

    public function testUpdateQuality_itemSulfurasDoesNotHaveASellIn_sellInEqualsZero():void
    {
        //arrange
        $items = [new Item("Sulfuras, Hand of Ragnaros", 0, 80)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(0, $items[0]->sell_in);
    }

    public function testUpdateQuality_itemBackstageQualityIncreasesTwoIfSellInEqualsTen():void
    {
        //arrange
        $items = [new Item("Backstage passes to a TAFKAL80ETC concert", 10, 5)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(7, $items[0]->quality);
    }

    public function testUpdateQuality_itemBackstageQualityIncreasesTwoIfSellInMinorThenTen():void
    {
        //arrange
        $items = [new Item("Backstage passes to a TAFKAL80ETC concert", 10, 5)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(7, $items[0]->quality);
    }

    public function testUpdateQuality_itemBackstageQualityIncreasesThreeIfSellInEqualsFive():void
    {
        //arrange
        $items = [new Item("Backstage passes to a TAFKAL80ETC concert", 5, 5)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(8, $items[0]->quality);
    }

    public function testUpdateQuality_itemBackstageQualityIncreasesThreeIfSellInMinorThenFive():void
    {
        //arrange
        $items = [new Item("Backstage passes to a TAFKAL80ETC concert", 4, 5)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(8, $items[0]->quality);
    }

    public function testUpdateQuality_itemQualityDecreasesTwoTimesFasterIfSellInIsZero():void
    {
        //arrange
        $items = [new Item("Elixir of the Mongoose", 0, 2)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(0, $items[0]->quality);
    }

    public function testUpdateQuality_AgedBrieIncreasesQualityWhenSellInDecreases():void
    {
        //arrange
        $items = [new Item("Aged Brie", 2, 0)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(1, $items[0]->quality);
    }

    public function testUpdateQuality_AItemQualityCanNotBeNegative():void
    {
        //arrange
        $items = [new Item("+5 Dexterity Vest", -6, 0)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(0, $items[0]->quality);
    }

    public function testUpdateQuality_AItemQualityCanNotBeMoreThenFifty():void
    {
        //arrange
        $items = [new Item("Aged Brie", 2, 50)];
        $glidedRose = new GildedRose($items);

        //act
        $glidedRose->updateQuality();

        //assert
        $this->assertEquals(50, $items[0]->quality);
    }
}
