<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Controller\Adminhtml\PriceList;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use SuttonSilver\PriceLists\Controller\Adminhtml\PriceList;

/**
 * Class Edit
 * @package SuttonSilver\PriceLists\Controller\Adminhtml\PriceList
 */
class Edit extends PriceList
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('pricelist_id');
        $model = $this->_objectManager->create(\SuttonSilver\PriceLists\Model\PriceList::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Pricelist no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('suttonsilver_pricelists_pricelist', $model);

        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Pricelist') : __('New Pricelist'),
            $id ? __('Edit Pricelist') : __('New Pricelist')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Pricelists'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Pricelist %1', $model->getId()) : __('New Pricelist'));
        return $resultPage;
    }
}
