define(['uiComponent', 'jquery'], function (Component, $){
    return Component.extend({
        defaults: {
            searchSku: ''
        },

        initObservable: function () {
            this._super();
            this.observe(['searchSku']);

            return this;
        },

        initialize: function () {
            this._super();
            this.searchSku.subscribe(this.search);
        },

        search: function (searchValue) {
            if (searchValue.length >= 3) {
                $.ajax({
                    url: BASE_URL + 'username/index/search',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        searchValue: searchValue
                    },
                    success: function (data) {
                        console.log(data);
                        $('.products').html('');
                        $('.products').show();
                        $.each(data, function( index, value ) {
                            $('.products').append('<div class="product">'+'<div>'+'Name: '+value.name+'</div>'+'<div class="sku">'+value.sku+'</div>'+'</div>');
                        });
                        $('.product').click(function () {
                            $('#sku').val($(this).children('.sku').text());
                            $('.products').hide('fast');
                        })
                    }.bind(this)
                });
            }
        },

    });
});
