<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Plugin\Magento\Catalog\Controller\Category;

use Magento\Customer\Model\SessionFactory as Session;
use Magento\Customer\Model\Url;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
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
     * @param Url $customerUrl
     * @param Session $customerSession
     * @param PriceListData $priceListData
     */
    public function __construct(
        Url $customerUrl,
        Session $customerSession,
        PriceListData $priceListData
    ) {
        $this->customerUrl = $customerUrl;
        $this->customerSession = $customerSession;
        $this->priceListData = $priceListData;
    }

    /**
     * Authenticate user
     *
     * @param ActionInterface $subject
     * @param RequestInterface $request
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeDispatch(ActionInterface $subject, RequestInterface $request)
    {
        /** Only redirect if enabled in the config */
        if ($this->priceListData->getGeneralConfig('categories_logged_in') &&
            $this->priceListData->getGeneralConfig('enable')
        ) {
            $loginUrl = $this->customerUrl->getLoginUrl();

            if (!$this->customerSession->create()->authenticate($loginUrl)) {
                $subject->getActionFlag()->set('', $subject::FLAG_NO_DISPATCH, true);
            }
        }
    }
}
