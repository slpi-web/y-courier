;(function($) {
    $.cseAddressBook = function(options) {

        var self = this;

        self.getSaveParams = function(type) {
            var data = {};
            if (self.options.userSelect != false) {
                var value = $('#'+self.options.modelName+'_user_id').val();
                if (!value)
                    return false;

                data[self.options.addressBookModelName+'[user_id]'] = value;
            }

            $('input[name^="'+self.options.modelName+'['+type+'"]').each(function(){
                var name = $(this).attr('name');
                if (name == self.options.modelName+'['+type+']')
                    name = self.options.addressBookModelName+'[name]';
                else
                    name = name.replace(self.options.modelName, self.options.addressBookModelName).replace(type+'_', '');

                data[name] = $(this).val();
            });


            data[self.options.addressBookModelName+'[type]'] = type;

            return data;
        }

        self.getOpenParams = function(type) {
            var data = {};
            if (self.options.userSelect != false) {
                var value = $('#'+self.options.modelName+'_user_id').val();
                if (!value)
                    return false;

                data['user_id'] = value;
            }

            data['type'] = type;

            return data;
        }

        self.bindFormSubmit = function() {
            var modal = $('#addressbook-modal');
            $('#addressbook-modal form #popup-submit').ladda("bind");
            $('#addressbook-modal form').ajaxForm({
                success: function(response) {
                    if (response) {
                        modal.html(response).modal();
                        self.bindFormSubmit();
                    } else {
                        modal.modal('hide');
                    }
                }
            });
        }

        self.bindGrid = function(type) {
            $(document).off("click", "#addressbook-grid a.delete");
            $(document).off("click", "#addressbook-grid a.use");
            jQuery('[data-toggle=popover]').popover();
            jQuery('[data-toggle=tooltip]').tooltip();
            jQuery(document).on('click', '#addressbook-grid a.delete', function () {
                if (!confirm('Вы уверены, что хотите удалить данный элемент?')) return false;
                var th = this,
                    afterDelete = function () {
                    };
                jQuery('#addressbook-grid').yiiJsonGridView('update', {
                    type: 'POST',
                    url: jQuery(this).attr('href'),
                    success: function (data) {
                        jQuery('#addressbook-grid').yiiJsonGridView('update');
                        afterDelete(th, true, data);
                    },
                    error: function (XHR) {
                        return afterDelete(th, false, XHR);
                    }
                });
                return false;
            });
            jQuery(document).on('click', '#addressbook-grid a.use', function () {
                var data = $(this).data('formdata');
                $.each(data, function(name, value){
                    var search = type+'_'+name;
                    if (name == 'name')
                        search = type;
                    if (name == 'country_id') {
                        $('form#cse-delivery-form #'+type+'-'+name).select2('val', value);
                    } else if (name == 'city') {
                        $('form#cse-delivery-form #'+type+'-'+name+'_id').select2('data', value);
                    } else {
                        var element = $('form#cse-delivery-form [name$="[' + search + ']"]');
                        if (element.length) {
                            element.val(value).attr('value', value);
                        }
                    }
                });

                var modal = $('#addressbook-modal');
                modal.modal('hide');
                return false;
            });
            jQuery('#addressbook-grid').yiiJsonGridView({
                'ajaxUpdate': ['addressbook-grid'],
                'ajaxVar': 'ajax',
                'pagerClass': 'no-class',
                'summaryClass': 'summary',
                'loadingClass': 'grid-view-loading',
                'filterClass': 'filters',
                'tableClass': 'items table table-striped table-bordered table-condensed',
                'selectableRows': 0,
                'enableHistory': false,
                'updateSelector': '{page}, {sort}',
                'cacheTTL': 0,
                'cacheTTLType': 's',
                'localCache': true,
                'pageVar': 'CseAddressBook_page',
                'afterAjaxUpdate': function () {
                    jQuery('.popover').remove();
                    jQuery('[data-toggle=popover]').popover();
                    jQuery('.tooltip').remove();
                    jQuery('[data-toggle=tooltip]').tooltip();
                }
            });
        }

        self.init = function(options) {
            self.options = $.extend({}, $.cseAddressBook.defaultOptions, options);

            $('.cse-addressbook-save').click(function(e){
                var type = $(this).data('type');
                if (type) {
                    var data = self.getSaveParams(type);
                    if (data) {
                        var modal = $('#addressbook-modal');

                        $.ajax({
                            url: self.options.saveUrl,
                            data: data,
                            method: 'post',
                            dataType: 'html',
                            success: function(response) {
                                modal.html(response).modal();
                                self.bindFormSubmit();
                            }
                        });
                    }
                }
                e.preventDefault();
                return false;
            });

            $('.cse-addressbook-open').click(function(e){
                var type = $(this).data('type');
                if (type) {
                    var data = self.getOpenParams(type);
                    if (data) {
                        var modal = $('#addressbook-modal');

                        $.ajax({
                            url: self.options.openUrl,
                            data: data,
                            method: 'get',
                            dataType: 'html',
                            beforeSend: function (jqXHRm, settings) {
                                jqXHRm.setRequestHeader('X-Requested-With', {toString: function(){ return ''; }});
                            },
                            success: function(response) {
                                modal.html(response).modal({width: '900px'});
                                self.bindGrid(type);
                            }
                        });
                    }
                }
                e.preventDefault();
                return false;
            });
        }

        self.init(options);
    };

    $.cseAddressBook.defaultOptions = {
        saveUrl: '',
        openUrl: '',
        userSelect: false,
        modelName: '',
        addressBookModelName: 'CseAddressBook'
    };

    $.fn.cseAddressBook = function(options){
        new $.cseAddressBook(options);
    };

})(jQuery);