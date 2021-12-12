<?php

namespace Amasty\UserName\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;

class Form extends Template
{
    const FORM_ACTION = 'username\index\cart';

    /**
     * @var ScopeConfig
     */
    private $scopeConfig;

    /**
     * @var EventManager
     */
    private $eventManager;

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        //EventManager $eventManager,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
       // $this->eventManager = $eventManager;
        parent::__construct($context, $data);
    }

    public function isVisibleQty(): bool
    {
        return $this->scopeConfig->getValue('beautiful_config/general/is_visible_qty');
    }

    public function isVisibleName(): bool
    {
        return $this->scopeConfig->getValue('beautiful_config/general/is_visible_name');
    }

    public function getDefaultQty() :int
    {
        return $this->scopeConfig->getValue('beautiful_config/general/qty_value') ?: 1;
    }

    public function getFormAction() :string
    {
        return self::FORM_ACTION;
    }

   /* public function getData()
    {
        $this->eventManager->dispatch(
            'amasty_username_check_product',
            ['name_to_check'=>$]
        );
        return ;
    }*/
}
