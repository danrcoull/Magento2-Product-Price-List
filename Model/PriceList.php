<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Model;

use SuttonSilver\PriceLists\Api\Data\PriceListInterface;
use Magento\Framework\Api\DataObjectHelper;
use SuttonSilver\PriceLists\Api\Data\PriceListInterfaceFactory;

class PriceList extends \Magento\Framework\Model\AbstractModel
{

    protected $dataObjectHelper;

    protected $pricelistDataFactory;

    protected $_eventPrefix = 'suttonsilver_pricelists_pricelist';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param PriceListInterfaceFactory $pricelistDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \SuttonSilver\PriceLists\Model\ResourceModel\PriceList $resource
     * @param \SuttonSilver\PriceLists\Model\ResourceModel\PriceList\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        PriceListInterfaceFactory $pricelistDataFactory,
        DataObjectHelper $dataObjectHelper,
        \SuttonSilver\PriceLists\Model\ResourceModel\PriceList $resource,
        \SuttonSilver\PriceLists\Model\ResourceModel\PriceList\Collection $resourceCollection,
        array $data = []
    ) {
        $this->pricelistDataFactory = $pricelistDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve pricelist model with pricelist data
     * @return PriceListInterface
     */
    public function getDataModel()
    {
        $pricelistData = $this->getData();
        
        $pricelistDataObject = $this->pricelistDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $pricelistDataObject,
            $pricelistData,
            PriceListInterface::class
        );
        
        return $pricelistDataObject;
    }
}
