<?php

namespace Amasty\UserName\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Form extends Template
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

    public function isVisibleQty()
    {
        return $this->scopeConfig->getValue('beautiful_config/general/is_visible_qty');
    }

    public function isVisibleName()
    {
        return $this->scopeConfig->getValue('beautiful_config/general/is_visible_name');
    }

    public function getDefaultQty()
    {
        return $this->scopeConfig->getValue('beautiful_config/general/qty_value');
    }
}
