    function check_form_country() {
        var error = 0;
        if ( $("#c_country").val().length != 2 ) {
            $("#c_country").css('border','1px solid red') ;
            error = error + 1;
        }
        if ( $("#country").val().length < 2 ) {
            $("#country").css('border','1px solid red') ;
            error = error + 1;
        }
        if(error > 0) {
            $('#c_code_error').css('display','block');
            return false;
        }
        return true;
    }

    function edit_countries(element) {
        var d_country = $('#d_edit_country');

        d_country.css('display','block');
        $('#fade').css('display','block');

        $("input[name=country_code]").val(element.attr('code'));
        $("input[name='e_country']").val(element.attr('data'));
        renderEditCountry();
        return false;
    }

    function edit_region(element, id) {
        var d_region = $('#d_edit_region');

        d_region.css('display','block');
        $('#fade').css('display','block');

        $("input[name=region_id]").val(id);
        $("input[name=e_region]").val(element.html());

        renderEditRegion();
        return false;
    }

    function edit_city(element, id) {
        var d_city = $('#d_edit_city');

        d_city.css('display','block');
        $('#fade').css('display','block');

        $("input[name=city_id]").val(id);
        $("input[name=e_city]").val(element.html());
        renderEditCity();
        return false;
    }

    function show_region(c_code, s_country) {
        $.ajax({
            "url": base_url + "index.php?page=ajax&action=regions&countryId=" + c_code,
            "dataType": 'json',
            success: function( json ) {
                var div_regions = $("#i_regions").html('');
                $('#i_cities').html('');
                $.each(json, function(i, val){
                    var clear = $('<div>').css('clear','both');
                    var container = $('<div>');
                    var s_country = $('<div>').css('float','left');
                    var more_region = $('<div>').css('float','right');
                    var link = $('<a>').addClass('view-more');

                    s_country.append('<a class="close" onclick="return delete_dialog(\'' + val.pk_i_id + '\', \'delete_region\');" href="' + base_url + 'index.php?page=settings&action=locations&type=delete_region&id=' + val.pk_i_id + '"><img src="' + base_url + 'images/close.png" alt="' + s_close + '" title="' + s_close + '" /></a>');
                    s_country.append('<a href="javascript:void(0);" class="edit" onclick="edit_region($(this), ' + val.pk_i_id + ');" style="padding-right: 15px;">' + val.s_name + '</a>');
                    link.attr('href', 'javascript:void(0)');
                    link.click(function(){
                        show_city(val.pk_i_id);
                    });
                    link.append(s_view_more + ' &raquo;');
                    more_region.append(link);
                    container.append(s_country).append(more_region);
                    div_regions.append(container);
                    div_regions.append(clear);
                });
                resetLayout();
            }
        });
        $('input[name=country_c_parent]').val(c_code);
        $('input[name=country_parent]').val(s_country);
        $('#b_new_region').css('display','block');
        $('#b_new_city').css('display','none');
        return false;
    }

    function show_city(id_region) {
        $.ajax({
            "url": base_url + "index.php?page=ajax&action=cities&regionId=" + id_region,
            "dataType": 'json',
            success: function( json ) {
                var div_regions = $("#i_cities").html('');
                $.each(json, function(i, val){
                    var clear = $('<div>').css('clear','both');
                    var container = $('<div>');
                    var s_region = $('<div>').css('float','left');
                    s_region.append('<a class="delete" class="close" onclick="return delete_dialog(\'' + val.pk_i_id + '\', \'delete_city\');"  href="' + base_url + 'index.php?page=settings&action=locations&type=delete_city&id=' + val.pk_i_id + '"><img src="' + base_url + 'images/close.png" alt="' + s_close + '" title="' + s_close + '" /></a>');//OJO ELIMINADI ID REPEDITO
                    s_region.append('<a href="javascript:void(0);" class="edit" onclick="edit_city($(this), ' + val.pk_i_id + ');" style="padding-right: 15px;">' + val.s_name + '</a>');
                    container.append(s_region);
                    div_regions.append(container);
                    div_regions.append(clear);
                });
                resetLayout();
            }
        });
        $('#b_new_city').css('display','block');
        $('input[name=region_parent]').val(id_region);
        return false;
    }

    $(document).ready(function(){
        $("#c_country").focus(function(){
            $('#c_code_error').css('display','none');
           $(this).css('border','');
        });

        $("#country").focus(function(){
           $(this).css('border','');
        });

        $("#country").keyup(function(){
            if($('#country').val().length == 0) {
               $('input[name=c_manual]').val('1');
            }
        });

        var countries ;
        $("#country").autocomplete({
            source: function( text, add ) {
                $.ajax({
                    "url": "http://geo.osclass.org/geo.services.php?callback=?&action=country&max=5",
                    "dataType": "jsonp",
                    "data": text,
                    success: function( json ) {
                        var suggestions = [];
                        if( json.length > 0 ) {
                            countries = new Array();
                            $.each(json, function(i, val){
                                suggestions.push(val.name);
                                countries[val.name] = val.code;
                                $('input[name=c_manual]').val('0');
                            });
                        } else {
                            countries = new Array();
                            suggestions.push(text.term);
                            $('input[name=c_manual]').val('1');
                        }
                        add(suggestions);
                    }
                });
            },

            select: function(e, ui) {
                if ( typeof countries[ui.item.value] !== "undefined" && countries[ui.item.value]) {
                    $("#c_country").val(countries[ui.item.value]);
                } else {
                    $("#c_country").val('');
                }
            },

            selectFirst: true
        });

        var regions ;
        $("#region").autocomplete({
            source: function( text, add ) {
                $.ajax({
                    "url": 'http://geo.osclass.org/geo.services.php?callback=?&action=region&max=5&country=' + $('input[name=country_parent]').val(),
                    "dataType": "jsonp",
                    "data": text,
                    success: function( json ) {
                        var suggestions = [];
                        if( json.length > 0 ) {
                            regions = new Array();
                            $.each(json, function(i, val){
                                suggestions.push(val.name);
                                regions[val.name] = val.code;
                                $('input[name=r_manual]').val('0');
                            });
                        } else {
                            regions = new Array();
                            suggestions.push(text.term);
                            $('input[name=r_manual]').val('1');
                        }
                        add(suggestions);
                    }
                });
            },

            selectFirst: true
        });

        var cities ;
        $("#city").autocomplete({
            source: function( text, add ) {
                $.ajax({
                    "url": 'http://geo.osclass.org/geo.services.php?callback=?&action=city&max=5&country=' + $('input[name=country_parent]').val(),
                    "dataType": "jsonp",
                    "data": text,
                    success: function( json ) {
                        var suggestions = [];
                        if( json.length > 0 ) {
                            cities = new Array();
                            $.each(json, function(i, val){
                                suggestions.push(val.name);
                                cities[val.name] = val.code;
                                $('input[name=ci_manual]').val('0');
                            });
                        } else {
                            cities = new Array();
                            suggestions.push(text.term);
                            $('input[name=ci_manual]').val('1');
                        }
                        add(suggestions);
                    }
                });
            },

            selectFirst: true
        });

        $("#b_new_country").click(function(){
            renderNewCountry();
        });
        $("#b_new_region").click(function(){
            renderAddRegion();
        });
        $("#b_new_city").click(function(){
            renderAddCity();
        });
    });
    function renderNewCountry(){
        $( "#d_add_country" ).dialog({
            width: 250,
            modal: true,
            title: addNewCountryText,
        });
    }
    
    function renderEditCountry(){
        var buttonsActions = {};
        buttonsActions[editText] = function() { 
            $("#d_edit_country_form").submit(); 
        }
        buttonsActions[cancelText] = function() { 
            $(this).dialog("close"); 
        }
        $( "#d_edit_country" ).dialog({
            width: 250,
            modal: true,
            title: editNewCountryText
        });
    }
    function renderAddRegion(){
        $( "#d_add_region" ).dialog({
            width: 400,
            modal: true,
            title: addNewRegionText
        });
    }
    function renderEditRegion(){
        $( "#d_edit_region" ).dialog({
            width: 400,
            modal: true,
            title: editNewRegionText
        });
    }
    function renderAddCity(){
        $( "#d_add_city" ).dialog({
            width: 400,
            modal: true,
            title: addNewCityText
        });
    }
    function renderEditCity(){
        $( "#d_edit_city" ).dialog({
            width: 400,
            modal: true,
            title: editNewCityText
        });
    }