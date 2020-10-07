<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Controller\Adminhtml\PriceList;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use SuttonSilver\PriceLists\Controller\Adminhtml\PriceList;

/**
 * Class Delete
 * @package SuttonSilver\PriceLists\Controller\Adminhtml\PriceList
 */
class Delete extends PriceList
{
    /** @var \SuttonSilver\PriceLists\Model\ResourceModel\PriceListCustomers\Collection */
    protected $customerCollection;

    /** @var \SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts\Collection */
    protected $productsCollection;

    /**
     * Delete constructor.
     * @param \SuttonSilver\PriceLists\Model\ResourceModel\PriceListCustomers\Collection $customerCollection
     * @param \SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts\Collection $productsCollection
     */
    public function __construct(
        \SuttonSilver\PriceLists\Model\ResourceModel\PriceListCustomers\Collection $customerCollection,
        \SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts\Collection $productsCollection,
        Context $context,
        Registry $coreRegistry
    ) {
        $this->customerCollection = $customerCollection;
        $this->productsCollection = $productsCollection;

        parent::__construct($context, $coreRegistry);
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('pricelist_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\SuttonSilver\PriceLists\Model\PriceList::class);
                $model->load($id);

                // Delete customer and product rows
                $this->customerCollection->addFieldToFilter('price_list_id', $id)->walk('delete');
                $this->productsCollection->addFieldToFilter('price_list_id', $id)->walk('delete');

                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Pricelist.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['pricelist_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Pricelist to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
