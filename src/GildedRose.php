<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @var Item[]
     */
    private $items;
    private const AGED_BRIE = 'Aged Brie';
    private const BACKSTAGE = 'Backstage passes to a TAFKAL80ETC concert';
    private const SULFURAS = 'Sulfuras, Hand of Ragnaros';

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            switch ($item->name){
                case self::AGED_BRIE:
                    $this->updateQualityBrie($item);
                    break;

                case self::BACKSTAGE:
                    $this->updateQualityBackstage($item);
                    break;

                case self::SULFURAS:
                    $this->updateQualitySulfuras($item);
                    break;

                default:
                    $this->updateQualityNormal($item);
                    break;
            }
        }
    }

    private function updateQualityBrie(Item $item){
        $this->increaseOneFromQualityIfItIsLessThenFifty($item);

        $this->decreaseOneFromSellIn($item);

        if ($item->sell_in < 0) {
            $this->increaseOneFromQualityIfItIsLessThenFifty($item);
        }
    }

    private function updateQualityBackstage(Item $item){
        $this->increaseOneFromQualityIfItIsLessThenFifty($item);

        if ($item->sell_in < 11) {
            $this->increaseOneFromQualityIfItIsLessThenFifty($item);
        }
        if ($item->sell_in < 6) {
            $this->increaseOneFromQualityIfItIsLessThenFifty($item);
        }

        $this->decreaseOneFromSellIn($item);

        $this->decreaseQualityInZeroIfSellInIsLessThenZero($item);
    }

    private function updateQualitySulfuras(Item $item){
        $this->increaseOneFromQualityIfItIsLessThenFifty($item);

        if ($item->sell_in < 0) {
            $this->increaseOneFromQualityIfItIsLessThenFifty($item);
        }
    }

    private function updateQualityNormal(Item $item){
        $this->decreaseOneFromQualityIfItIsBiggerThenZero($item);

        $this->decreaseOneFromSellIn($item);

        if ($item->sell_in < 0) {
            $this->decreaseOneFromQualityIfItIsBiggerThenZero($item);
        }
    }

    private function increaseOneFromQualityIfItIsLessThenFifty(Item $item)
    {
        if ($item->quality < 50) {
            $item->quality = $item->quality + 1;
        }
    }

    private function decreaseOneFromSellIn(Item $item)
    {
        $item->sell_in = $item->sell_in - 1;
    }

    private function decreaseOneFromQualityIfItIsBiggerThenZero(Item $item)
    {
        if ($item->quality > 0) {
            $item->quality = $item->quality - 1;
        }
    }

    private function decreaseQualityInZeroIfSellInIsLessThenZero(Item $item)
    {
        if ($item->sell_in < 0) {
            $item->quality -= $item->quality;
        }
    }
}
