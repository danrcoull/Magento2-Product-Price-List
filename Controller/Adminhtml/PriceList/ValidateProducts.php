<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Controller\Adminhtml\PriceList;

use Magento\Backend\App\Action;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Class ValidateProducts
 * @package SuttonSilver\PriceLists\Controller\Adminhtml\PriceList
 */
class ValidateProducts extends Action
{

    /**
     * @var CollectionFactory
     */
    private $productCollection;
    /**
     * @var JsonFactory
     */
    private $jsonResultFactory;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * ValidateProducts constructor.
     * @param Action\Context $context
     * @param JsonFactory $jsonResultFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CollectionFactory $productCollection
     * @param SortOrderBuilder $sortOrderBuilder
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        JsonFactory $jsonResultFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CollectionFactory $productCollection,
        SortOrderBuilder $sortOrderBuilder,
        ProductRepositoryInterface $productRepository
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->productCollection = $productCollection;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productRepository = $productRepository;
        $this->sortOrderBuilder = $sortOrderBuilder;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var  $ids */
        $ids = json_decode($this->getRequest()->getParam('ids'));

        if (is_array($ids)) {
            $this->searchCriteriaBuilder->addFilter('entity_id', $ids, 'in');
            //$this->searchCriteriaBuilder->addFilter('sku', $searchKey, 'like');
        }
        /** @var  $sortOrder */
        $sortOrder = $this->sortOrderBuilder
            ->setField('name')
            ->setDirection(SortOrder::SORT_DESC)
            ->create();

        $this->searchCriteriaBuilder->addSortOrder($sortOrder);

        /** @var  $searchCriteria */
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $products = $this->productRepository
            ->getList($searchCriteria)
            ->getItems();

        $totalValues = count($products);
        $customerById = [];
        /** @var  CustomerInterface $product */
        foreach ($products as $product) {
            $productId = $product->getId();
            $customerById[$productId] = [
                'value' => $productId,
                'label' => $product->getName(),
                'is_active' => $product->getStatus(),
                'path' => $product->getSku(),
                'optgroup' => false
            ];
        }
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonResultFactory->create();
        return $resultJson->setData([
            'options' => $customerById
        ]);
    }
}
