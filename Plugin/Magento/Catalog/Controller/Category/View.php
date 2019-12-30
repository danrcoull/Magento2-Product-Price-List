<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Plugin\Magento\Catalog\Controller\Category;

use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;

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
     * @param Url $customerUrl
     * @param Session $customerSession
     */
    public function __construct(
        Url $customerUrl,
        Session $customerSession
    ) {
        $this->customerUrl = $customerUrl;
        $this->customerSession = $customerSession;
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
        $loginUrl = $this->customerUrl->getLoginUrl();

        if (!$this->customerSession->authenticate($loginUrl)) {
            $subject->getActionFlag()->set('', $subject::FLAG_NO_DISPATCH, true);
        }
    }
}
