<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Api\Data;

interface PriceListProductsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get PriceListProducts list.
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface[]
     */
    public function getItems();

    /**
     * Set price_list_id list.
     * @param \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
