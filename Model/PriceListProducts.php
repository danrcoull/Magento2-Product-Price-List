<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Model;

use SuttonSilver\PriceLists\Api\Data\PriceListProductsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface;

class PriceListProducts extends \Magento\Framework\Model\AbstractModel
{

    protected $dataObjectHelper;

    protected $pricelistproductsDataFactory;

    protected $_eventPrefix = 'suttonsilver_pricelists_pricelistproducts';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param PriceListProductsInterfaceFactory $pricelistproductsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts $resource
     * @param \SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        PriceListProductsInterfaceFactory $pricelistproductsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts $resource,
        \SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts\Collection $resourceCollection,
        array $data = []
    ) {
        $this->pricelistproductsDataFactory = $pricelistproductsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve pricelistproducts model with pricelistproducts data
     * @return PriceListProductsInterface
     */
    public function getDataModel()
    {
        $pricelistproductsData = $this->getData();
        
        $pricelistproductsDataObject = $this->pricelistproductsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $pricelistproductsDataObject,
            $pricelistproductsData,
            PriceListProductsInterface::class
        );
        
        return $pricelistproductsDataObject;
    }
}
