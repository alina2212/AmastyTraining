<?php

namespace Amasty\UserName\Model;

use Magento\Framework\Model\AbstractModel;
use Amasty\UserName\Model\ResourceModel\Foo as FooResource;

class Foo extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(FooResource::class);
    }
}
