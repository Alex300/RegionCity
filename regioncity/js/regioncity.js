/**
 * Region City plugin for Cotonti
 *
 * @package Region City
 * @author Alex - Studio Portal30
 * @copyright Portal30 2013 http://portal30.ru
 */


(function( $ ){

    var methods = {
        init : function( options ) {
            'use strict';

            return this.each(function(options){

                var $this = $(this);
                var element_data = $this.data('params');
                var params = element_data.params;
                params.initSelection =
                    function (element, callback) {
                        callback(element_data.initSelection);
                    }

                params.ajax = {
                    url: element_data.ajax_url || "index.php?e=regioncity&a=ajxSuggestCity",
                    dataType: 'json',

                    data: function (term, page) {
                        return {
                            q: term,
                            page_limit: 10,
                            page: page
                        };
                    },
                    results: function (data, page) {
                        var more = (page * 10) < data.total;
                        return {results: data.data, more: more};
                    }
                }

                $this.select2(params);
                $this.select2('val', [
                    {id: null, text: null}
                ]);

                $($this).on("change", function(e) {
                    var id = $(this).attr('id');
                    var val = e.val;

                    if(val == ''){
                        $('#'+ id +'_name').val('');
                    }else{
                        $('#'+ id +'_name').val(e.added.text);
                    }
                })

            });
        }
    };

    /**
     * Renders a Select2 city dropdown
     *
     * Select2 must be installed on your site
     * @see http://ivaynberg.github.io/select2/
     *
     * @param method
     * @returns {*}
     */
    $.fn.select2City = function( method ) {
        'use strict';

        // логика вызова метода
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Метод с именем ' +  method + ' не существует для jQuery.select2City' );
        }
    };
})( jQuery );

$(document).on("change", ".rec_country", function(){
    var parent = $(this).parent();
    var id = $(this).attr('id');
    var x = $('input[name=x]').val();
    var country = $(this).val();
    var elemsNum = id.replace('rec_country_', '');
    var regId = 'rec_region_' + elemsNum;
    var cityId = 'rec_city_' + elemsNum;
    var regParent = $('#'+regId).parent();

    var lLeft = regParent.width() / 2 - 110;
    var lTop = regParent.height() / 2 + 9;
    if (lTop > (regParent.height() / 2)) lTop = 2;

    var regionOptionFirst = $('#'+ regId).find('option:first').text();
    var cityOptionFirst = $('#'+cityId).find('option:first').text();

    $('#'+ regId).html('<option value="0">' + regionOptionFirst + '</option>');
    $('#'+cityId).html('<option value="0">' + cityOptionFirst + '</option>');

    $('#'+regId).attr('disabled', 'disabled');
    $('#'+cityId).attr('disabled', 'disabled');

    $('#'+regId+'_name').val('');
    $('#'+cityId+'_name').val('');

    $('#loading').remove();

    if (country != '0'){
        var bgspan = $('<span>', {
            id: "loading",
            class: "loading"
        })  .css('position', 'absolute')
            .css('left',lLeft + 'px')
            .css('top', lTop  + 'px');
        bgspan.html('<img src="./images/spinner.gif" alt="loading"/>');
        regParent.append(bgspan).css('position', 'relative').css('opacity', 0.4);
        $('#'+regId).html('<option>........</option>');

        $.post('index.php?e=regioncity&a=axjGetRegions', { country: country, x: x }, function(data) {
            var opts = '';
            $.each(data.regions, function(index, value) {
               opts = opts + '<option value="'+index+'">'+value+'</option>';
            });
            $('#'+regId).html(opts);
            if (data.disabled == 0){
                $('#'+regId).attr('disabled', null);
            }
            bgspan.remove();
            regParent.css('opacity', 1);
        }, "json");
    }else{

    }
});

$(document).on("change", ".rec_region", function(){
    var parent = $(this).parent();
    var id = $(this).attr('id');
    var x = $('input[name=x]').val();
    var region = $(this).val();
    var elemsNum = id.replace('rec_region_', '');
    var cityId = 'rec_city_' + elemsNum;

    var cityParent = $('#'+cityId).parent();

    var lLeft = cityParent.width() / 2 - 110;
    var lTop = cityParent.height() / 2 + 9;
    if (lTop > (cityParent.height() / 2)) lTop = 2;

    var cityOptionFirst = $('#'+cityId).find('option:first').text();
    $('#'+cityId).html('<option value="0">' + cityOptionFirst + '</option>');

    $('#'+ id +'_name').val($('#'+ id + ' option:selected').text());
    $('#'+cityId+'_name').val('');

    $('#loading').remove();

    $('#'+cityId).attr('disabled', 'disabled');

    if (region != '0'){
        var bgspan = $('<span>', {
            id: "loading",
            class: "loading"
        })  .css('position', 'absolute')
            .css('left',lLeft + 'px')
            .css('top', lTop  + 'px');
        bgspan.html('<img src="./images/spinner.gif" alt="loading"/>');
        cityParent.append(bgspan).css('position', 'relative').css('opacity', 0.4);
        $('#'+cityId).html('<option>........</option>');

        $.post('index.php?e=regioncity&a=axjGetCities', { region: region, x: x }, function(data) {
            var opts = '';
            $.each(data.cities, function(index, value) {
                opts = opts + '<option value="'+index+'">'+value+'</option>';
            });
            $('#'+cityId).html(opts);
            if (data.disabled == 0){
                $('#'+cityId).attr('disabled', null);
            }
            bgspan.remove();
            cityParent.css('opacity', 1);
        }, "json");
    }else{

    }
});

$(document).on("change", ".rec_city", function(){
    var id = $(this).attr('id');
    $('#'+ id +'_name').val($('#'+ id + ' option:selected').text());
});