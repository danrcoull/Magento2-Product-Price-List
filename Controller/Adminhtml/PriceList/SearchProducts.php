<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Controller\Adminhtml\PriceList;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Class SearchProducts
 * @package SuttonSilver\PriceLists\Controller\Adminhtml\PriceList
 */
class SearchProducts extends Action
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

    public function __construct(
        Context $context,
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
        $searchKey = $this->getRequest()->getParam('searchKey');
        $pageNum = (int)$this->getRequest()->getParam('page');
        $limit = (int)$this->getRequest()->getParam('limit');

        $this->searchCriteriaBuilder->addFilter('type_id', 'simple', 'eq');
        if ($searchKey) {
            $this->searchCriteriaBuilder->addFilter('name', '%' . $searchKey . '%', 'like');
            //$this->searchCriteriaBuilder->addFilter('sku', $searchKey, 'like');
        }
        $sortOrder = $this->sortOrderBuilder
            ->setField('name')
            ->setDirection(SortOrder::SORT_DESC)
            ->create();

        $this->searchCriteriaBuilder->addSortOrder($sortOrder);

        $this->searchCriteriaBuilder
            ->setPageSize($limit)
            ->setCurrentPage($pageNum);

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
            'options' => $customerById,
            'total' => empty($customerById) ? 0 : $totalValues
        ]);
    }
}
