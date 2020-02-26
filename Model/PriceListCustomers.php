<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use SuttonSilver\PriceLists\Api\Data\PriceListCustomersInterface;
use SuttonSilver\PriceLists\Api\Data\PriceListCustomersInterfaceFactory;
use SuttonSilver\PriceLists\Model\ResourceModel\PriceListCustomers\Collection;

/**
 * Class PriceListCustomers
 * @package SuttonSilver\PriceLists\Model
 */
class PriceListCustomers extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var string
     */
    protected $_eventPrefix = 'suttonsilver_pricelists_pricelistcustomers';
    /**
     * @var PriceListCustomersInterfaceFactory
     */
    protected $pricelistcustomersDataFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param PriceListCustomersInterfaceFactory $pricelistcustomersDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param ResourceModel\PriceListCustomers $resource
     * @param Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        PriceListCustomersInterfaceFactory $pricelistcustomersDataFactory,
        DataObjectHelper $dataObjectHelper,
        ResourceModel\PriceListCustomers $resource,
        Collection $resourceCollection,
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
