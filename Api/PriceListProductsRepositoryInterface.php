<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface PriceListProductsRepositoryInterface
{

    /**
     * Save PriceListProducts
     * @param \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface $priceListProducts
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface $priceListProducts
    );

    /**
     * Retrieve PriceListProducts
     * @param string $pricelistproductsId
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($pricelistproductsId);

    /**
     * Retrieve PriceListProducts matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete PriceListProducts
     * @param \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface $priceListProducts
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface $priceListProducts
    );

    /**
     * Delete PriceListProducts by ID
     * @param string $pricelistproductsId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($pricelistproductsId);
}
