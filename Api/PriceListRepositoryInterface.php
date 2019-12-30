<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface PriceListRepositoryInterface
{

    /**
     * Save PriceList
     * @param \SuttonSilver\PriceLists\Api\Data\PriceListInterface $priceList
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \SuttonSilver\PriceLists\Api\Data\PriceListInterface $priceList
    );

    /**
     * Retrieve PriceList
     * @param string $pricelistId
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($pricelistId);

    /**
     * Retrieve PriceList matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete PriceList
     * @param \SuttonSilver\PriceLists\Api\Data\PriceListInterface $priceList
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \SuttonSilver\PriceLists\Api\Data\PriceListInterface $priceList
    );

    /**
     * Delete PriceList by ID
     * @param string $pricelistId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($pricelistId);
}
