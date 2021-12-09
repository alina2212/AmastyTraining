<?php

namespace Amasty\SecondUserName\Plugin;

class AddPlugin
{
    const FORM_ACTION = 'checkout/cart/add';

    public function afterGetFormAction($subject, $result)
    {
        return self::FORM_ACTION;
    }
}
