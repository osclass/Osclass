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
    for (var i = 0 ; i < aa.elements.length ; i++) {
        aa.elements[i].checked = check;
    }
}

function check_cat(id, check) {
    var lay = document.getElementById("cat" + id);
    inp = lay.getElementsByTagName("input");

    for (var i = 0, maxI = inp.length ; i < maxI; ++i) {
        if(inp[i].type == "checkbox") {
            inp[i].checked = check;
        }
    }
}

function check(id) {
    if( !$('#'+id).attr('checked') )
        $('#'+id).attr('checked',true);

    var category_id = id.replace('category-','');
    var categories = $("#cat" + category_id + " input");
    var sum = 0;
    $.each(categories, function(i, val){
       if(val.checked)
           sum++;
    });
    if(sum == 0)
        $("#category-" + category_id ).attr('checked', false);
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
        num_error = num_error + 1;
    }

    if( $('#skip-location-h').val() == 0 ) {
        if($("#d_country span").length < 1) {
            $("#d_country").css('border', '2px solid red');
            num_error = num_error + 1;
        }
    } else {
        if( !$('#skip-location').attr('checked') ) {
            num_error = num_error + 1;
        }
    }
    
    var pattern_notnull=/^[a-zA-Z0-9]+$/;
    if( !pattern_notnull.test(admin_user.value) ) {
        error_admin_user.setAttribute('style', 'display:block;');
        num_error = num_error + 1;
    }

    if(num_error > 0) {
        return false;
    }

    var input = $("#target_form input");
    $("#lightbox").css('display','');

    if( $('input[name=c_country]:checked').val() == 'International' ) {
        alert('You\'ve chosen worlwide, it might take a while')
    }

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: 'install-location.php',
        data: input,
        timeout: 600000,
        success: function(data) {
            if(data.status == 200) {
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
            $("#lightbox").css('display','none');
        }
    });
    return false;
}

$(function(){
    //choose country
    $("#d_country").click(function(){
       $("#t_country").focus();
       $("#d_country").css('border', '');
    });

    if($("#t_country").length != 0) {
        $("#t_country").autocomplete({
            open: function(event, ui) {
                $('#a_country ul').attr('style','');
                $('#country-error').css('display','none');
                $('#region-info').css('display','none');
            },

            source: function(text, add){
                $.jsonp({
                    "url": "http://geo.osclass.org/geo.services.php?callback=?&action=country&max=5",
                    "data": text,
                    "success": function(json) {
                        var suggestions = [];
                        if( json.length > 0 ) {
                            $.each(json, function(i, val){
                                suggestions.push(val.name);
                            });
                        } else {
                            suggestions.push('No matches found');
                        }
                        add(suggestions);
                    },
                    "error": function(d,msg) {
                        no_internet();
                    }
                });
            },

            select: function(e, ui) {
                if(!ui.item.value.match(/No matches found/)) {
                    var country = ui.item.value,
                        span = $("<span>").text(country),
                        a = $("<a>").addClass("remove").attr({
                            href: "javascript:",
                            title: "Remove " + country
                        }).text("x").appendTo(span);
                        input = $("<input>").attr({
                            type: "hidden",
                            name: "country[]",
                            value: country
                        }).appendTo(span);
                    span.insertBefore("#t_country");
                    $("#region-div").css('display','block');
                    $("#region-box").css('display','none');
                }
            },

            close: function(event, ui) {
                $("#t_country").val("");
                $("#t_country").attr('size', 1);
                if ($("#d_country span").length > 1) {
                    $("#region-div").css('display','none');
                    $("#country-error").css('display','block');
                    var l_span = $("#d_location span");
                    l_span.remove();
                } else if($("#d_country span").length == 1) {
                    $('#region-info').css('display','block');
                }
            },

            appendTo: '#a_country',

            delay: 400,

            selectFirst: true
        });
    }
    
    $(".remove", document.getElementById("d_country")).live("click", function(){
        if($(this).attr('title').match(/Remove All countries and territories/)) {
            $("input[value=Country]").attr('checked',true);
            $("#t_country").removeAttr("disabled");
            $("#t_country").focus();
        }
        $(this).parent().remove();
        var l_span = $("#d_location span");
        l_span.remove();
        if($("#d_country span").length === 0) {
            $("#region-div").css('display','none');
            $("#region-box").css('display','none');
            $("#t_country").css("top", 0);
        } else if($("#d_country span").length == 1) {
            $("#region-div").css('display','block');
            $("#country-error").css('display','none');
            $('#region-info').css('display','block');
        }
    });

    $('#t_country').keydown(function(event){
        if( (event.keyCode == 8) && ($('#t_country').val() == 0) ) {
            var sel_countries = $('#d_country span:last');
            sel_countries.remove();
            if($("#d_country span").length === 0) {
                $("#region-div").css('display','none');
                $("#region-box").css('display','none');
                $("#t_country").css("top", 0);
            } else if($("#d_country span").length == 1) {
                $("#region-div").css('display','block');
                $("#country-error").css('display','none');
                $('#region-info').css('display','block');
            }
        }
    });

    //chouse region or city
    $("#d_location").click(function(){
       $("#t_location").focus();
    });

    if( $("#t_location").length != 0 ) {
        $("#t_location").autocomplete({
            open: function(event, ui) {
                $('#a_location ul').attr('style','');
            },

            source: function(text, add){
                var type = '';
                if($("input[name=c_location]:checked").val().match(/Region/i)) {
                    type = 'region';
                } else {
                    type = 'city';
                }
                var country = $("#d_country span input").attr('value');
                var url = 'http://geo.osclass.org/geo.services.php?callback=?&action=' + type + '&max=5&country=' + country;
                $.jsonp({
                    "url": url,
                    "data": text,
                    "success": function(json) {
                        var suggestions = [];
                        if( json.length > 0 ) {
                            $.each(json, function(i, val){
                                suggestions.push(val.name);
                            });
                        } else {
                            suggestions.push('No matches found');
                        }
                        add(suggestions);
                    },
                    "error": function(d,msg) {
                        no_internet();
                    }
                });
            },

            select: function(e, ui) {
                if(!ui.item.value.match(/No matches found/)) {
                    if($("input[name=c_location]:checked").val().match(/Region/i)) {
                        var region = ui.item.value,
                            span = $("<span>").text(region),
                            a = $("<a>").addClass("remove").attr({
                                href: "javascript:",
                                title: "Remove " + region
                            }).text("x").appendTo(span);
                            input = $("<input>").attr({
                                type: "hidden",
                                name: "region[]",
                                value: region
                            }).appendTo(span);
                        span.insertBefore("#t_location");
                    } else {
                        var city = ui.item.value,
                            span = $("<span>").text(city),
                            a = $("<a>").addClass("remove").attr({
                                href: "javascript:",
                                title: "Remove " + city
                            }).text("x").appendTo(span);
                            input = $("<input>").attr({
                                type: "hidden",
                                name: "city[]",
                                value: city
                            }).appendTo(span);
                        span.insertBefore("#t_location");
                    }
                }
            },

            close: function(event, ui) {
                $("#t_location").val("");
                $("#t_location").attr('size', 1);

            },

            appendTo: '#a_location',

            selectFirst: true
        });
    }

    $('#t_location').keydown(function(event){
        if( (event.keyCode == 8) && ($('#t_location').val() == 0) ) {
            var sel_regions = $('#d_location span:last');
            sel_regions.remove();
        }
    });

    $(".remove", document.getElementById("d_location")).live("click", function(){
        $(this).parent().remove();
        if($("#d_location span").length === 0) {
            $("#t_location").css("top", 0);
        }
    });
});

function no_internet() {
    $('#t_country').attr('disabled' , true )
                   .val('');
    $('#icountry').attr('disabled', true );
    $('#worlwide').attr('disabled', true );
    $('#d_country span').remove();
    $('#location-error').css('display','block');
    $('#skip-location-d').css('display','block');
    $('#skip-location-h').attr('value','1');
}

function more_size(input, event) {
    if(event.keyCode==8) {
        input.setAttribute("size", (parseInt(input.getAttribute('size')) - 1) );
    } else {
        input.setAttribute("size", (parseInt(input.getAttribute('size')) + 1) );
    }
}

function change_to_international(input) {
    if(input.checked) {
        $("#d_country").css('border', '');
        var c_span = $("#d_country span");
        c_span.remove();
        var l_span = $("#d_location span");
        l_span.remove();
        var country = 'All countries and territories',
            span = $("<span>").text(country),
            a = $("<a>").addClass("remove").attr({
                href: "javascript:",
                title: "Remove " + country
            }).text("x").appendTo(span);
        span.insertBefore("#t_country");
        $("#t_country").attr('disabled', true);
        $("#region-div").css('display','none');
        $("#region-box").css('display','none');
        $.jsonp({
            "url": "http://geo.osclass.org/geo.services.php?callback=?&action=country&max=1&term=",
            "error": function(d,msg) {
                no_internet();
            }
        });
    }
}

function change_to_country(input) {
    if(input.checked) {
        var c_span = $("#d_country span");
        c_span.remove();
        var l_span = $("#d_location span");
        l_span.remove();
        $("#t_country").removeAttr("disabled");
        $("#region-div").css('display','none');
        $("#region-box").css('display','none');
    }
}

$(document).ready(function(){
    $("#email").focus(function() {
        $("#email").attr('style', '');
        $('#email-error').attr('style', 'display:none;');
    });

    $("#admin_user").focus(function() {
        $('#admin-user-error').attr('style', 'display:none;');
    });
});

/* Extension of jQuery */
(function( $ ) {
    $( ".ui-autocomplete-input" ).live( "autocompleteopen", function() {
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
