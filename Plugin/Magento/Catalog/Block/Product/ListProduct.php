<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Plugin\Magento\Catalog\Block\Product;

class ListProduct
{

    public function afterGetLoadedProductCollection(
        \Magento\Catalog\Block\Product\ListProduct $subject,
        $result
    ) {
        //Your plugin code
        return $result;
    }
}
