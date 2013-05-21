function db_admin(){
    var checkbox = document.getElementById('createdb');
    var admin_username = document.getElementById('admin_username_row');
    var admin_password = document.getElementById('admin_password_row');
    var input_username = document.getElementById('admin_username');
    var input_password = document.getElementById('admin_password');

    if(checkbox.checked) {
        admin_username.removeAttribute('class');
        admin_password.removeAttribute('class');
        input_username.disabled = false;
        input_password.disabled = false;
    } else {
        admin_username.setAttribute('class', 'disabled');
        admin_password.setAttribute('class', 'disabled');
        input_username.disabled = true;
        input_password.disabled = true;
    }
}

function check_all (frm, check) {
    var aa = document.getElementById(frm);
    for (var i = 0; i < aa.elements.length; i++) {
        aa.elements[i].checked = check;
    }
}

function check_cat(id, check) {
    var lay = document.getElementById("cat" + id);
    inp = lay.getElementsByTagName("input");

    for (var i = 0, maxI = inp.length; i < maxI; ++i) {
        if(inp[i].type == "checkbox") {
            inp[i].checked = check;
        }
    }
}

function check(id) {
    if( !$('#'+id).prop('checked') )
        $('#'+id).prop('checked',true);

    var category_id = id.replace('category-','');
    var categories = $("#cat" + category_id + " input");
    var sum = 0;
    $.each(categories, function(i, val){
       if(val.checked)
           sum++;
    });
    if(sum == 0)
        $("#category-" + category_id ).prop('checked', false);
}

function validate_form() {
    admin_user        = document.getElementById('admin_user');
    error_admin_user  = document.getElementById('admin-user-error');
    email = document.getElementById('email');
    error = document.getElementById('email-error');
    var pattern=/^([a-zA-Z0-9_\.\-\+])+@([a-zA-Z0-9_\.\-])+\.([a-zA-Z])+([a-zA-Z])+$/;
    var num_error = 0;
    if( !pattern.test(email.value) ) {
        email.setAttribute('style', 'color:red;');
        error.setAttribute('style', 'display:block;');
        error.setAttribute('aria-hidden', 'false');
        num_error = num_error + 1;
    }

    
    var pattern_notnull=/^[a-zA-Z0-9]+$/;
    if( !pattern_notnull.test(admin_user.value) ) {
        error_admin_user.setAttribute('style', 'display:block;');
        error_admin_user.setAttribute('aria-hidden', 'false');
        num_error = num_error + 1;
    }

    if(num_error > 0) {
        return false;
    }

    var input = $("#target_form input");
    $("#lightbox").css('display','');


    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: 'install-location.php',
        data: input,
        timeout: 600000,
        success: function(data) {
            if(data.status == true) {
                var form = document.createElement("form");
                form.setAttribute("method", 'POST');
                form.setAttribute("action", 'install.php');

                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", 'step');
                hiddenField.setAttribute("value", '4');
                form.appendChild(hiddenField);

                hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", 'result');
                hiddenField.setAttribute("value", data.email_status);
                form.appendChild(hiddenField);

                hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", 'password');
                hiddenField.setAttribute("value", data.password);
                form.appendChild(hiddenField);

                document.body.appendChild(form);
                form.submit();

            } else {
                alert("Error:<br/>"+data);
                window.location = 'install.php?step=4&error_location=1';
            }
        },
        error: function(data) {
            $("#lightbox").css('display','none').attr('aria-hidden', 'true');
        }
    });
    return false;
}


function no_internet() {
    $('#t_country').attr('disabled' , true )
                   .val('');
    $('#icountry').attr('disabled', true );
    $('#worlwide').attr('disabled', true );
    $('#d_country span').remove();
    $('#location-error').css('display','block').attr('aria-hidden', 'false');
}

function more_size(input, event) {
    if(event.keyCode==8) {
        input.setAttribute("size", (parseInt(input.getAttribute('size')) - 1) );
    } else {
        input.setAttribute("size", (parseInt(input.getAttribute('size')) + 1) );
    }
}

function get_regions(country) {
    $("#country-input").attr("value", country);
    $("#region-input").attr("value", "all");
    $("#city-input").attr("value", "all");
    $('#city_select').hide();
    $('#no_city_text').hide();
    $('#skip-location-input').attr('value','0');
    if(country=="skip") {
        $('#skip-location-input').attr('value','1');
    } else if(country=='all') {
        $('#region_select').hide();
        $('#no_region_text').hide();
    } else {
        $.getJSON(
            "http://geo.osclass.org/newgeo.services.php?callback=?&action=regions",
            {'country' : country},
            function(json) {
                if( json.length > 0 ) {
                    $('#region_select').show();
                    $('#no_region_text').hide();
                    $(".region_select").remove();
                    $.each(json, function(i, val){
                        $("#region_select").append('<option value="'+val.code+'" class="region_select" >'+val.s_name+'</option>');
                    });
                } else {
                    $('#region_select').hide();
                    $('#no_region_text').show();
                };
            }
        );
    }
}

function get_cities(region) {
    $("#region-input").attr("value", region);
    $("#city-input").attr("value", "all");
    if(region=='all') {
        $('#city_select').hide();
        $('#no_city_text').hide();
    } else {
        $.getJSON(
            "http://geo.osclass.org/newgeo.services.php?callback=?&action=cities",
            {'region' : region},
            function(json) {
                if( json.length > 0 ) {
                    $('#city_select').show();
                    $('#no_city_text').hide();
                    $(".city_select").remove();
                    $.each(json, function(i, val){
                        $("#city_select").append('<option value="'+val.code+'" class="city_select" >'+val.s_name+'</option>');
                    });
                } else {
                    $('#city_select').hide();
                    $('#no_city_text').show();
                };
            }
        );
    }
}

$(document).ready(function(){
    $("#email").focus(function() {
        $("#email").attr('style', '');
        $('#email-error').attr({ 
            'style'         : 'display:none;',
            'aria-hidden'   : 'true'
        });
    });

    $("#admin_user").focus(function() {
        $('#admin-user-error').attr({ 
            'style'         : 'display:none;',
            'aria-hidden'   : 'true'
        });
    });
    
    $("#country_select").change(function(){
        get_regions($("#country_select option:selected").attr("value"));
    });

    $("#region_select").change(function(){
        get_cities($("#region_select option:selected").attr("value"));
    });
    
    $("#city_select").change(function(){
        $("#city-input").attr("value", $("#city_select option:selected").attr("value"));
    });

    get_regions($("#country_select option:selected").attr("value"));
    
});

/* Extension of jQuery */
(function( $ ) {
    $( ".ui-autocomplete-input" ).on( "autocompleteopen", function() {
        var autocomplete = $( this ).data( "autocomplete" ),
        menu = autocomplete.menu;
        if ( !autocomplete.options.selectFirst ) {
            return;
        }
        menu.activate( $.Event({
            type: "mouseenter"
        }), menu.element.children().first() );
    });
}( jQuery ));
