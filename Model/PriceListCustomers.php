<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Model;

use SuttonSilver\PriceLists\Api\Data\PriceListCustomersInterface;
use Magento\Framework\Api\DataObjectHelper;
use SuttonSilver\PriceLists\Api\Data\PriceListCustomersInterfaceFactory;

class PriceListCustomers extends \Magento\Framework\Model\AbstractModel
{

    protected $dataObjectHelper;

    protected $_eventPrefix = 'suttonsilver_pricelists_pricelistcustomers';
    protected $pricelistcustomersDataFactory;


    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param PriceListCustomersInterfaceFactory $pricelistcustomersDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \SuttonSilver\PriceLists\Model\ResourceModel\PriceListCustomers $resource
     * @param \SuttonSilver\PriceLists\Model\ResourceModel\PriceListCustomers\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        PriceListCustomersInterfaceFactory $pricelistcustomersDataFactory,
        DataObjectHelper $dataObjectHelper,
        \SuttonSilver\PriceLists\Model\ResourceModel\PriceListCustomers $resource,
        \SuttonSilver\PriceLists\Model\ResourceModel\PriceListCustomers\Collection $resourceCollection,
        array $data = []
    ) {
        $this->pricelistcustomersDataFactory = $pricelistcustomersDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve pricelistcustomers model with pricelistcustomers data
     * @return PriceListCustomersInterface
     */
    public function getDataModel()
    {
        $pricelistcustomersData = $this->getData();
        
        $pricelistcustomersDataObject = $this->pricelistcustomersDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $pricelistcustomersDataObject,
            $pricelistcustomersData,
            PriceListCustomersInterface::class
        );
        
        return $pricelistcustomersDataObject;
    }
}
