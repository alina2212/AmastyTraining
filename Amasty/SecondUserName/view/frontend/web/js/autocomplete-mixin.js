define(function () {
    'use strict';

    var mixin = {

        checkValue: function (value) {
            var result = false;

            if (value.length >= 5) {
                result = true;
            }

            return result;
        }
    };

    return function (target) { // target == Result that Magento_Ui/.../columns returns.
        return target.extend(mixin); // new result that all other modules receive
    };
});
