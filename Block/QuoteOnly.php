<?php

namespace SuttonSilver\PriceLists\Block;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Information;
use SuttonSilver\PriceLists\Model\PriceListData;

/**
 * Class QuoteOnly
 * @package SuttonSilver\PriceLists\Block
 */
class QuoteOnly extends Template
{
    /**
     * @var PriceListData
     */
    protected $priceListData;
    /**
     * @var Information
     */
    protected $_storeInfo;

    /**
     * @var StoreInterface
     */
    protected $_store;

    public function __construct(
        Context $context,
        PriceListData $priceListData,
        Information $storeInfo,
        \Magento\Store\Model\Store $store,
        array $data = []
    ) {
        $this->priceListData = $priceListData;
        $this->_storeInfo = $storeInfo;
        $this->_store = $store;
        parent::__construct($context, $data);
    }

    /**
     * isLoggedIn
     * @return bool|null
     */
    public function isLoggedIn():? bool
    {
        return $this->priceListData->isLoggedInId();
    }

    public function getPhone()
    {
        try {
            $storeInfo = $this->_storeInfo->getStoreInformationObject($this->_store);
            $phone = $storeInfo->getPhone();
            return $phone;
        } catch (NoSuchEntityException $e) {
        }

        return '';
    }
}
