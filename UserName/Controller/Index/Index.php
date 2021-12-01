<?php

namespace Amasty\UserName\Controller\Index;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class Index extends \Magento\Framework\App\Action\Action
{
    /** @var ScopeConfigInterface
     */
    private $scopeConfig;

    public $pageFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->pageFactory = $pageFactory;
        $this->scopeConfig = $scopeConfig;
        return parent::__construct($context);
    }

    public function execute()
    {
       if($this->scopeConfig->isSetFlag('beautiful_config/general/enabled')){
           return $this->pageFactory->create();
       }else{
           die('Sorry');
       }
    }
}
