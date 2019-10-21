;(function($) {
    $.cseCitySelector = function(el, options) {

        var self = this;

        self.$el = $(el);
        self.id = self.$el.attr('id');

        self.$el.data("cseCitySelector", self);

        self.getCityAutocompleteParams = function(term, page)
        {
            var ajaxParams = {
                'country_id': $('#'+self.id+'-country_id').select2("val"),
                'query': term,
                'page': page,
                'path': true
            };
            if ($('#'+self.id+'-address-classifier-form').is(':visible')) {
                if ($('#' + self.id + '_classifier-region').is(':visible')) {
                    var region = $('#' + self.id + '-region_id').select2("val");
                    if (region) {
                        ajaxParams['region_id'] = region;
                        ajaxParams['path'] = false;
                    }
                }
                if ($('#' + self.id + '_classifier-area').is(':visible')) {
                    var area = $('#' + self.id + '-area_id').select2("val");
                    if (area) {
                        ajaxParams['area_id'] = area;
                        ajaxParams['path'] = false;
                    }
                }
            }

            return ajaxParams;
        }

        self.init = function(options) {
            self.options = $.extend({}, $.cseCitySelector.defaultOptions, options);

            $('#'+self.id+'-country_id').select2($.extend({}, self.options.select2DefaultOptions, {}))
                .on('change', function(e){
                    $('#'+self.id+'-city_id').select2("val", '');
                    if (self.options.ajaxCountryInfoUrl) {
                        $.ajax({
                            url: self.options.ajaxCountryInfoUrl,
                            dataType: 'json',
                            data: {
                                country_id: $(this).select2("val")
                            },
                            success: function (data) {
                                if (data.regions)
                                    $('#'+self.id+'_classifier-region').show();
                                else {
                                    $('#' + self.id + '_classifier-region').hide();
                                    $('#' + self.id + '-region_id').select2("val", '');
                                }
                                if (data.areas)
                                    $('#'+self.id+'_classifier-area').show();
                                else {
                                    $('#' + self.id + '_classifier-area').hide();
                                    $('#' + self.id + '-area_id').select2("val", '');
                                }

                                if (!data.areas && !data.regions) {
                                    $('#'+self.id+'-address-classifier').hide();
                                    $('#'+self.id+'-address-classifier-form').hide();
                                } else {
                                    var link = $('#'+self.id+'-address-classifier');
                                    link.show();
                                    if (link.hasClass('opened')) {
                                        $('#'+self.id+'-address-classifier-form').slideDown();
                                    }
                                }
                            }
                        });
                    }
                });

            $('#'+self.id+'-region_id').select2($.extend({}, self.options.select2DefaultOptions, {
                ajax: {
                    url: self.options.ajaxRegionAutocompleteUrl,
                    dataType: 'json',
                    data: function (term, page) {
                        return {
                            country_id: $('#'+self.id+'-country_id').select2("val"),
                            query: term,
                            page: page
                        }
                    },
                    results: function (data, page) {
                        var more = (page * self.options.autocompletePageSize) < data.total_count;
                        return {
                            results: data.items,
                            more: more
                        };
                    },
                    cache: true
                }
            })).on('change', function(e){
                    if ($('#' + self.id + '_classifier-area').is(':visible')) {
                        $('#' + self.id + '-area_id').select2("val", '');
                    }
            });;

            $('#'+self.id+'-area_id').select2($.extend({}, self.options.select2DefaultOptions, {
                ajax: {
                    url: self.options.ajaxAreaAutocompleteUrl,
                    dataType: 'json',
                    data: function (term, page) {
                        var data = {
                            country_id: $('#'+self.id+'-country_id').select2("val"),
                            query: term,
                            page: page
                        }
                        if ($('#' + self.id + '_classifier-region').is(':visible')) {
                            data['region_id'] = $('#' + self.id + '-region_id').select2("val");
                        }
                        return data;
                    },
                    results: function (data, page) {
                        var more = (page * self.options.autocompletePageSize) < data.total_count;
                        return {
                            results: data.items,
                            more: more
                        };
                    },
                    cache: true
                }
            }));

            $('#'+self.id+'-city_id').select2($.extend({}, self.options.select2DefaultOptions, {
                ajax: {
                    url: self.options.ajaxCityAutocompleteUrl,
                    dataType: 'json',
                    data: function(term, page){
                        return self.getCityAutocompleteParams(term, page);
                    },
                    results: function (data, page) {
                        var more = (page * self.options.autocompletePageSize) < data.total_count;
                        return {
                            results: data.items,
                            more: more
                        };
                    },
                    cache: true
                },
                initSelection: function (element, callback) {
                    var data = element.data('initvalue');
                    if (data) {
                        if (typeof data == "object") {
                            callback(data);
                        }
                    }
                }
            }));

            $('#'+self.id+'-address-classifier').click(function(e){
                var text = $(this).data('switch_text');
                if (text)
                    $(this).data('switch_text', $(this).text()).text(text);
                $('#'+self.id+'-address-classifier-form').slideToggle().toggleClass('opened');
                e.preventDefault();
                return false;
            });

        };

        self.init(options);
    };

    $.cseCitySelector.defaultOptions = {
        ajaxCountryInfoUrl: '',
        ajaxRegionAutocompleteUrl: '',
        ajaxAreaAutocompleteUrl: '',
        ajaxCityAutocompleteUrl: '',
        autocompletePageSize: 10,
        select2DefaultOptions: {
            width: 'responsive'
        }
    };

    $.fn.cseCitySelector = function(options){
        return this.each(function(){
            (new $.cseCitySelector(this, options));
        });
    };

})(jQuery);