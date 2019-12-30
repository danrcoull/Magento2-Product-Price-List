<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Plugin\Magento\Catalog\Model;

class Product
{
    protected $priceListData;
    public function __construct(\SuttonSilver\PriceLists\Model\PriceListData $priceListData)
    {
        $this->priceListData = $priceListData;
    }

    public function afterGetPrice(
        \Magento\Catalog\Model\Product $subject,
        $result
    ) {
        $price = $this->priceListData->getProductPrice($subject->getId());


        return $price > 0 ? $price : $result;
    }
}
