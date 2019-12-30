<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Controller\Adminhtml\PriceList;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use SuttonSilver\PriceLists\Api\Data\PriceListCustomersInterface;
use SuttonSilver\PriceLists\Api\Data\PriceListCustomersInterfaceFactory;
use SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface;
use SuttonSilver\PriceLists\Api\Data\PriceListProductsInterfaceFactory;
use SuttonSilver\PriceLists\Api\PriceListCustomersRepositoryInterface;
use SuttonSilver\PriceLists\Api\PriceListProductsRepositoryInterface;
use SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts\Collection;

class Save extends \Magento\Backend\App\Action
{
    protected $dataPersistor;
    protected $priceListCustomersRepository;
    protected $priceListProductsRepository;
    protected $priceListProducts;
    protected $priceListCustomers;
    protected $priceListCustomersCollection;
    protected $priceListProductsCollection;

    /**
     * @param Context $context
     * @param PriceListCustomersRepositoryInterface $priceListCustomersRepository
     * @param PriceListProductsRepositoryInterface $priceListProductsRepository
     * @param PriceListProductsInterfaceFactory $priceListProducts
     * @param PriceListCustomersInterfaceFactory $priceListCustomers
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        PriceListCustomersRepositoryInterface $priceListCustomersRepository,
        \SuttonSilver\PriceLists\Model\ResourceModel\PriceListCustomers\CollectionFactory $priceListCustomersCollection,
        \SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts\CollectionFactory $priceListProductsCollection,
        PriceListProductsRepositoryInterface $priceListProductsRepository,
        PriceListProductsInterfaceFactory $priceListProducts,
        PriceListCustomersInterfaceFactory $priceListCustomers,
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->priceListCustomersRepository = $priceListCustomersRepository;
        $this->priceListProductsRepository = $priceListProductsRepository;
        $this->priceListProducts = $priceListProducts;
        $this->priceListCustomers = $priceListCustomers;
        $this->priceListCustomersCollection = $priceListCustomersCollection;
        $this->priceListProductsCollection = $priceListProductsCollection;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $products = isset($data['products']) ? $data['products'] : null;
            $customers = isset($data['customers']) ? $data['customers'] : null;

            $id = $this->getRequest()->getParam('pricelist_id');

            $model = $this->_objectManager->create(\SuttonSilver\PriceLists\Model\PriceList::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Pricelist no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Pricelist.'));
                $this->dataPersistor->clear('suttonsilver_pricelists_pricelist');

                $this->updateProducts($products, $model->getId());
                $this->updateCustomers($customers, $model->getId());

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['pricelist_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Pricelist.'));
            }

            $this->dataPersistor->set('suttonsilver_pricelists_pricelist', $data);
            return $resultRedirect->setPath('*/*/edit', ['pricelist_id' => $this->getRequest()->getParam('pricelist_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function updateProducts($products, $id)
    {
        /** new product ids */
        $selectedIds = [];
        $priceMap = [];
        foreach ($products as $product) {
            if (is_array($product['product_id'])) {
                foreach ($product['product_id'] as $ipd) {
                    $selectedIds[] = (int)$ipd;
                    $priceMap[$ipd] = $product['product_price'];
                }
            } else {
                $selectedIds[] = $product['product_id'];
                $priceMap[ $product['product_id']] = $product['product_price'];
            }
        }

        /** @var Collection $collection */
        $collection = $this->priceListProductsCollection->create();
        /** Get the collection of non existing ids in the new save */
        $collection->addFieldToFilter('price_list_product_id', ['nin' => $selectedIds]);
        $collection->addFieldToFilter('price_list_id', $id);

        /** @var PriceListProductsInterface $oldItem */
        foreach ($collection as $oldItem) {
            try {
                /** delete the old item via the repository interface */
                $this->priceListProductsRepository->delete($oldItem->getDataModel());
            } catch (\Exception $E) {
            }
        }

        /** @var int $sid */
        foreach ($selectedIds as $sid) {
            /** @var PriceListProductsInterface $item */
            $item = $this->priceListProducts->create();
            try {
                /** @var Collection $collection */
                $collection = $this->priceListProductsCollection->create();
                $collection->addFieldToFilter('price_list_product_id', $sid);
                $collection->addFieldToFilter('price_list_id', $id);
                $original = $collection->getFirstItem();
                if ($original->getPriceListProductId()) {
                    /** Keep the original database id if it alredy exists as there should only be one product per list */
                    /** @var  PriceListProductsInterface $item */
                    $item = $original->getDataModel();
                }
            } catch (\Exception $e) {
            }

            $item->setPriceListProductId($sid);
            $item->setPriceListId($id);
            if (isset($priceMap[$sid])) {
                $item->setPriceListProductPrice($priceMap[$sid]);
            }
            try {
                $this->priceListProductsRepository->save($item);
            } catch (\Exception $E) {
            }
        }
    }

    public function updateCustomers($customers, $id)
    {
        /** new product ids */
        $selectedIds = [];
        foreach ($customers as $customer) {
            if (is_array($customer)) {
                foreach ($customer as $cid) {
                    $selectedIds[] = (int)$cid;
                }
            } else {
                $selectedIds[] = $customer;
            }
        }

        /** @var Collection $collection */
        $collection = $this->priceListCustomersCollection->create();
        /** Get the collection of non existing ids in the new save */
        $collection->addFieldToFilter('price_list_customer_id', ['nin' => $selectedIds]);
        $collection->addFieldToFilter('price_list_id', $id);

        /** @var PriceListCustomersInterface $oldItem */
        foreach ($collection as $oldItem) {
            try {
                /** delete the old item via the repository interface */
                $this->priceListCustomersRepository->delete($oldItem->getDataModel());
            } catch (\Exception $E) {
            }
        }

        /** @var int $sid */
        foreach ($selectedIds as $sid) {
            /** @var PriceListCustomersInterface $item */
            $item = $this->priceListCustomers->create();
            try {
                /** @var Collection $collection */
                $collection = $this->priceListCustomersCollection->create();
                $collection->addFieldToFilter('price_list_customer_id', $sid);
                $collection->addFieldToFilter('price_list_id', $id);
                $original = $collection->getFirstItem();
                if ($original->getPriceListCustomerId()) {
                    /** Keep the original database id if it alredy exists as there should only be one product per list */
                    /** @var  PriceListProductsInterface $item */
                    $item = $original->getDataModel();
                }
            } catch (\Exception $e) {
            }

            $item->setPriceListCustomerId($sid);
            $item->setPriceListId($id);

            try {
                $this->priceListCustomersRepository->save($item);
            } catch (\Exception $E) {
            }
        }
    }
}
