<?php

namespace Amasty\UserName\Controller\Index;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class Index extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        Context $context
    ) {
        return parent::__construct($context);
    }

    public function execute()
    {
        echo "Привет друзья!<br>
              Привет Amasty!<br>
              Я буду рада с вами работать!)<br>";
    }
}
