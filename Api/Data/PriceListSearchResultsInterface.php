<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Api\Data;

interface PriceListSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get PriceList list.
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListInterface[]
     */
    public function getItems();

    /**
     * Set name list.
     * @param \SuttonSilver\PriceLists\Api\Data\PriceListInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
