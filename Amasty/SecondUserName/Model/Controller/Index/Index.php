<?php

namespace Amasty\SecondUserName\Model\Controller\Index;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var PageFactory
     */
    public $pageFactory;

    /**
     * @var Session
     */
    public $customerSession;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        ScopeConfigInterface $scopeConfig,
        Session $customerSession
    ) {
        $this->pageFactory = $pageFactory;
        $this->scopeConfig = $scopeConfig;
        $this->customerSession = $customerSession;
        return parent::__construct($context);
    }

    public function execute()
    {
        if ($this->scopeConfig->isSetFlag('beautiful_config/general/enabled') &&  $this->customerSession->isLoggedIn()) {
            return $this->pageFactory->create();
        } else {
            die('Sorry');
        }
    }
}
