<?php

    // check requirements
    if( !is_writable( ABS_PATH . 'oc-content/downloads/' ) ) {
        osc_add_flash_error_message( sprintf(_m('<code>downloads</code> folder has to be writable, i.e.: <code>chmod 0755 %soc-content/downloads/</code>'), ABS_PATH), 'admin');
    }

    // fancybox
    osc_enqueue_script('fancybox');
    osc_enqueue_style('fancybox', osc_assets_url('js/fancybox/jquery.fancybox.css'));

    osc_register_script('market-js', osc_current_admin_theme_js_url('market.js'), array('jquery', 'jquery-ui'));
    osc_enqueue_script('market-js');

    osc_add_hook('admin_header','add_market_jsvariables');

    function gradienColors(){
        $letters = str_split('abgi');
        shuffle($letters);
        return $letters;
    }
    if(!function_exists('addBodyClass')){
        function addBodyClass($array){
                   $array[] = 'market';
            return $array;
        }
    }
    osc_add_filter('admin_body_class','addBodyClass');


    function customPageHeader() {
        $action = Params::getParam("action"); ?>
        <div class="header-title-market">
            <h1><?php _e('Discover how to improve your Osclass!'); ?></h1>
            <h2>Osclass offers many templates and plugins.<br/>Turn your Osclass installation into a classifieds site in a minute!</h2>
        </div>
        <div class="banner-market">

        </div>
        <ul class="tabs">
            <li <?php if($action == ''){ echo 'class="active"';} ?>><a href="<?php echo osc_admin_base_url(true).'?page=market'; ?>"><?php _e('Market'); ?></a></li>
            <li <?php if($action == 'plugins'){ echo 'class="active"';} ?>><a href="<?php echo osc_admin_base_url(true).'?page=market&action=plugins'; ?>"><?php _e('Plugins'); ?></a></li>
            <li <?php if($action == 'themes'){ echo 'class="active"';} ?>><a href="<?php echo osc_admin_base_url(true).'?page=market&action=themes'; ?>"><?php _e('Themes'); ?></a></li>
            <li <?php if($action == 'languages'){ echo 'class="active"';} ?>><a href="<?php echo osc_admin_base_url(true).'?page=market&action=languages'; ?>"><?php _e('Languages'); ?></a></li>
            <?php if(osc_market_api_connect()=='') { ?>
                <li class="connect"><a id="market_connect" href="#"><?php _e('Connect'); ?></a></li>
            <?php } else { ?>
                <li class="purchases <?php if($action == 'purchases'){ echo 'active';} ?>"><a href="<?php echo osc_admin_base_url(true).'?page=market&action=purchases'; ?>"><?php _e('My purchases'); ?></a></li>
                <li class="disconnect"><a id="market_disconnect" href="#"><?php _e('Disconnect from Market'); ?></a></li>
            <?php }; ?>
        </ul>

        <script type="text/javascript">
            $(document).ready(function() {
                <?php if(osc_market_api_connect()=='') { ?>
                $("#dialog-connect").dialog({
                    width: '480',
                    autoOpen: false,
                    modal: true
                });
                $("#connect-submit").on('click', function() {
                    $('#connect_form').hide();
                    $('#connect_wait').show();
                    $.getJSON(
                        '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=market_connect',
                        {'s_email' : document.getElementById("connect_user").value, 's_password' : document.getElementById("connect_password").value},
                        function(data){
                            if(data==null) {
                                $('#connect_form').show();
                                $('#connect_wait').hide();
                                var data = new Object();
                                data.error = 1;
                                data.msg = '<?php _e('Sorry, the market is currently unavailable. Please try again in a few moments.'); ?>';
                            }
                            if(data.error==1) {
                                $('#connect_form').show();
                                $('#connect_wait').hide();
                                var flash = $("#flash_js");
                                var message = $('<div>').addClass('pubMessages').addClass(class_type).attr('id', 'flashmessage').text(data.msg);
                                flash.html(message);
                                $("#flashmessage").slideDown('slow').delay(3000).slideUp('slow');
                            } else {
                                window.location.reload(true);
                            }
                        }
                    );
                });

                $("#connect-cancel").on('click', function() {
                    $('#dialog-connect').dialog('close');
                });

                $("#market_connect").on('click', function() {
                    $('#dialog-connect').dialog('open');
                });

                <?php if(Params::getParam('open_market_connect')!='') { ?>

                        $('#dialog-connect').dialog('open');

                <?php } ?>

                <?php }; ?>

                $('#market_categories').bind("change", function() {
                    <?php if(Params::getParam('action')!='') { ?>
                        window.location = theme.marketCurrentURL + '&sCategory=' + $("#market_categories option:selected").prop('value');
                    <?php } else { ?>
                        window.location = theme.marketCurrentURL + $("#market_categories option:selected").attr('section-data') + '&sCategory=' + $("#market_categories option:selected").prop('value');
                    <?php };?>
                });

                $("#market_disconnect").on('click', function() {
                    var x = confirm('<?php _e('You are going to be disconnected from the Market, all your plugins and themes downloaded will remain installed and configured but you will not be able to update or download new plugins and themes. Are you sure?'); ?>');
                    if(x) {
                        window.location = '<?php echo osc_admin_base_url(true); ?>?page=settings&action=market_disconnect&<?php echo osc_csrf_token_url(); ?>&redirect=<?php echo base64_encode(osc_admin_base_url(true) . '?page=market&action=' . Params::getParam('action')); ?>';
                    }
                })

                $.getJSON(
                    '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=market_header',
                    function(data){
                        if(data.error===0) {
                            $('#content-head div.banner-market').html('<a target="_blank" href="'+oscEscapeHTML(data.url)+'"><img src="'+oscEscapeHTML(data.banner)+'"/></a>');
                        }
                    });
            });
        </script>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return __('Market');
    }
    osc_add_filter('admin_title', 'customPageTitle');
    osc_current_admin_theme_path( 'parts/header.php' );
?>
<?php if(osc_market_api_connect()=='') { ?>
<div id="dialog-connect" title="<?php _e('Connect'); ?>" class="has-form-actions hide">
    <?php if(ini_get('allow_url_fopen')==="0") { ?>
        <h1 class="header-title-market"><?php _e('MARKET CONNECT FAILED'); ?></h1>
        <p><?php _e('You need to enable <b>allow_url_fopen = On</b> at php.ini.');?> </p>
        <p><?php _e('If you need more information please contact your hosting provider.'); ?></p>
    <?php } else { ?>
    <div class="form-horizontal hide" id="connect_wait">
        <div class="form-row">
            <p>
                <?php _e('Connecting... please wait'); ?>
            </p>
        </div>
    </div>
    <div class="form-horizontal" id="connect_form">
        <div class="form-row">
            <?php echo sprintf(__('Connect your oc-admin to your <a target="_blank" href="%s">Market account!</a>'), 'http://market.osclass.org/'); ?>
            <p>
                <?php _e('Your purchased items on Osclass Market will automatically appear on My Purchases tab.'); ?>
            </p>
            <p>
                <input type="text" name="connect_user" id="connect_user" value="" placeholder="<?php _e('Your market email'); ?>"/>
                <input type="password" name="connect_password" id="connect_password" value="" placeholder="<?php _e('Your market password'); ?>" autocomplete="off" />
            </p>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a id="connect-cancel" class="btn" href="javascript:void(0);"><?php _e('Cancel'); ?></a>
                <a id="connect-submit" href="javascript:void(0);" class="btn" ><?php echo osc_esc_html( __('Connect') ); ?></a>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<?php }; ?>