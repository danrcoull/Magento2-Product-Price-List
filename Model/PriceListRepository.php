<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Model;

use SuttonSilver\PriceLists\Api\Data\PriceListSearchResultsInterfaceFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use SuttonSilver\PriceLists\Api\PriceListRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use SuttonSilver\PriceLists\Model\ResourceModel\PriceList as ResourcePriceList;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use SuttonSilver\PriceLists\Api\Data\PriceListInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use SuttonSilver\PriceLists\Model\ResourceModel\PriceList\CollectionFactory as PriceListCollectionFactory;

class PriceListRepository implements PriceListRepositoryInterface
{

    protected $dataObjectHelper;

    protected $priceListCollectionFactory;

    private $storeManager;

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $extensionAttributesJoinProcessor;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;
    protected $resource;

    protected $priceListFactory;

    protected $dataPriceListFactory;


    /**
     * @param ResourcePriceList $resource
     * @param PriceListFactory $priceListFactory
     * @param PriceListInterfaceFactory $dataPriceListFactory
     * @param PriceListCollectionFactory $priceListCollectionFactory
     * @param PriceListSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourcePriceList $resource,
        PriceListFactory $priceListFactory,
        PriceListInterfaceFactory $dataPriceListFactory,
        PriceListCollectionFactory $priceListCollectionFactory,
        PriceListSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->priceListFactory = $priceListFactory;
        $this->priceListCollectionFactory = $priceListCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPriceListFactory = $dataPriceListFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \SuttonSilver\PriceLists\Api\Data\PriceListInterface $priceList
    ) {
        /* if (empty($priceList->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $priceList->setStoreId($storeId);
        } */
        
        $priceListData = $this->extensibleDataObjectConverter->toNestedArray(
            $priceList,
            [],
            \SuttonSilver\PriceLists\Api\Data\PriceListInterface::class
        );
        
        $priceListModel = $this->priceListFactory->create()->setData($priceListData);
        
        try {
            $this->resource->save($priceListModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the priceList: %1',
                $exception->getMessage()
            ));
        }
        return $priceListModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($priceListId)
    {
        $priceList = $this->priceListFactory->create();
        $this->resource->load($priceList, $priceListId);
        if (!$priceList->getId()) {
            throw new NoSuchEntityException(__('PriceList with id "%1" does not exist.', $priceListId));
        }
        return $priceList->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->priceListCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \SuttonSilver\PriceLists\Api\Data\PriceListInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \SuttonSilver\PriceLists\Api\Data\PriceListInterface $priceList
    ) {
        try {
            $priceListModel = $this->priceListFactory->create();
            $this->resource->load($priceListModel, $priceList->getPricelistId());
            $this->resource->delete($priceListModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the PriceList: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($priceListId)
    {
        return $this->delete($this->get($priceListId));
    }
}
