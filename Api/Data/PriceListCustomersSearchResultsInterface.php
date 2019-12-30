<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Api\Data;

interface PriceListCustomersSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get PriceListCustomers list.
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListCustomersInterface[]
     */
    public function getItems();

    /**
     * Set price_list_id list.
     * @param \SuttonSilver\PriceLists\Api\Data\PriceListCustomersInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
