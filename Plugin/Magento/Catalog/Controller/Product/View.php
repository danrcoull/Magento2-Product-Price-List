<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Plugin\Magento\Catalog\Controller\Product;

use Magento\Backend\App\AbstractAction;
use Magento\Customer\Model\SessionFactory as Session;
use Magento\Customer\Model\Url;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface;
use SuttonSilver\PriceLists\Model\PriceListData;

class View
{

    /**
     * @var Url
     */
    protected $customerUrl;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var PriceListData
     */
    protected $priceListData;
    /**
     * @var ResultFactory
     */
    protected $resultFactory;
    /**
        * @var RedirectInterface
        */
    protected $redirect;
    /**
        * @var ManagerInterface
        */
    protected $messageManager;

    /**
     * @param Url $customerUrl
     * @param Session $customerSession
     * @param PriceListData $priceListData
     * @param ResultFactory $resultFactory
     * @param RedirectInterface $redirect
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Url $customerUrl,
        Session $customerSession,
        PriceListData $priceListData,
        ResultFactory $resultFactory,
        RedirectInterface $redirect,
        ManagerInterface $messageManager

    ) {
        $this->customerUrl = $customerUrl;
        $this->customerSession = $customerSession;
        $this->priceListData = $priceListData;
        $this->resultFactory = $resultFactory;
        $this->redirect = $redirect;
        $this->messageManager = $messageManager;
    }

    /**
     * @param AbstractAction $subject
     * @param \Closure $proceed
     * @param RequestInterface $request
     *
     * @return void
     */
    public function aroundDispatch($subject, callable $proceed, $request)
    {

        $result =  $proceed($request);

        /** Only redirect if enabled in the config */
        if ($this->priceListData->getGeneralConfig('restrict_product_lists') &&
            $this->priceListData->getGeneralConfig('enable')
        ) {
            $pIds = $this->priceListData->getCustomerProductIds();

            $currentId = $request->getParam('id');

            if ($currentId != null) {
                if (!in_array($currentId, $pIds)) {
                    $resultRedirect = $this->resultFactory->create(
                        ResultFactory::TYPE_REDIRECT
                    );
                    $this->messageManager->addErrorMessage('Sorry you dont have permission to view this product, please contact support.');
                    // if you want to redirect to the previous page
                    $result = $resultRedirect->setUrl($this->redirect->getRefererUrl());
                }
            }

        }
        return $result;
    }
}
