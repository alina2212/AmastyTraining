<?php

namespace Amasty\UserName\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Foo extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('amasty_username_blacklist', 'entity_id');
    }
}
