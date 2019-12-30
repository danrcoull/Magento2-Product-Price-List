<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Model;

use SuttonSilver\PriceLists\Api\Data\PriceListProductsSearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use SuttonSilver\PriceLists\Api\PriceListProductsRepositoryInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts\CollectionFactory as PriceListProductsCollectionFactory;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use SuttonSilver\PriceLists\Api\Data\PriceListProductsInterfaceFactory;
use SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts as ResourcePriceListProducts;
use Magento\Framework\Exception\NoSuchEntityException;

class PriceListProductsRepository implements PriceListProductsRepositoryInterface
{

    protected $dataObjectHelper;

    private $storeManager;

    protected $dataPriceListProductsFactory;

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $extensionAttributesJoinProcessor;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;
    protected $resource;

    protected $priceListProductsCollectionFactory;

    protected $priceListProductsFactory;


    /**
     * @param ResourcePriceListProducts $resource
     * @param PriceListProductsFactory $priceListProductsFactory
     * @param PriceListProductsInterfaceFactory $dataPriceListProductsFactory
     * @param PriceListProductsCollectionFactory $priceListProductsCollectionFactory
     * @param PriceListProductsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourcePriceListProducts $resource,
        PriceListProductsFactory $priceListProductsFactory,
        PriceListProductsInterfaceFactory $dataPriceListProductsFactory,
        PriceListProductsCollectionFactory $priceListProductsCollectionFactory,
        PriceListProductsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->priceListProductsFactory = $priceListProductsFactory;
        $this->priceListProductsCollectionFactory = $priceListProductsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPriceListProductsFactory = $dataPriceListProductsFactory;
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
        \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface $priceListProducts
    ) {
        /* if (empty($priceListProducts->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $priceListProducts->setStoreId($storeId);
        } */
        
        $priceListProductsData = $this->extensibleDataObjectConverter->toNestedArray(
            $priceListProducts,
            [],
            \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface::class
        );
        
        $priceListProductsModel = $this->priceListProductsFactory->create()->setData($priceListProductsData);
        
        try {
            $this->resource->save($priceListProductsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the priceListProducts: %1',
                $exception->getMessage()
            ));
        }
        return $priceListProductsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($priceListProductsId)
    {
        $priceListProducts = $this->priceListProductsFactory->create();
        $this->resource->load($priceListProducts, $priceListProductsId);
        if (!$priceListProducts->getId()) {
            throw new NoSuchEntityException(__('PriceListProducts with id "%1" does not exist.', $priceListProductsId));
        }
        return $priceListProducts->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->priceListProductsCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface::class
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
        \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface $priceListProducts
    ) {
        try {
            $priceListProductsModel = $this->priceListProductsFactory->create();
            $this->resource->load($priceListProductsModel, $priceListProducts->getPricelistproductsId());
            $this->resource->delete($priceListProductsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the PriceListProducts: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($priceListProductsId)
    {
        return $this->delete($this->get($priceListProductsId));
    }
}
