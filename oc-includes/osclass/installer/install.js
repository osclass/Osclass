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
