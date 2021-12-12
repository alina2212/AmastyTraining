<?php

namespace Amasty\UserName\Model\ResourceModel\Foo;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Amasty\UserName\Model\Foo as Model;
use Amasty\UserName\Model\ResourceModel\Foo as ResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
