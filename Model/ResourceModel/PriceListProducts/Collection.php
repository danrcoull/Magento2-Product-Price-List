<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \SuttonSilver\PriceLists\Model\PriceListProducts::class,
            \SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts::class
        );
    }
}
