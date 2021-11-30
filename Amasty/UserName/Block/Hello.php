<?php

namespace Amasty\UserName\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Hello extends Template
{
    private $scopeConfig;

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function getHelloWorld()
    {
        return 'Hello World';
    }

    public function sayHelloConfig()
    {
       return $this->scopeConfig->getValue('beautiful_config/general/greeting_text');
    }
}
