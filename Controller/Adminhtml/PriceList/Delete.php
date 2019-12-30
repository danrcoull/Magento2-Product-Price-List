<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Controller\Adminhtml\PriceList;

use SuttonSilver\PriceLists\Controller\Adminhtml\PriceList;

/**
 * Class Delete
 * @package SuttonSilver\PriceLists\Controller\Adminhtml\PriceList
 */
class Delete extends PriceList
{

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
