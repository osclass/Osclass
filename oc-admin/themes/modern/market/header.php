<?php

    // check requirements
    if( !is_writable( ABS_PATH . 'oc-content/downloads/' ) ) {
        osc_add_flash_error_message( sprintf(_m('<code>downloads</code> folder has to be writable, i.e.: <code>chmod a+w %soc-content/downloads/</code>'), ABS_PATH), 'admin');
    }

    // fancybox
    osc_enqueue_script('fancybox');
    osc_enqueue_style('fancybox', osc_assets_url('js/fancybox/jquery.fancybox.css'));

    osc_register_script('market-js', osc_current_admin_theme_js_url('market.js'), array('jquery', 'jquery-ui'));
    osc_enqueue_script('market-js');

    osc_add_hook('admin_header','add_market_jsvariables');
    function add_market_jsvariables(){
        $marketPage = Params::getParam("mPage");
        $version_length = strlen(osc_version());
        $main_version = substr(osc_version(),0, $version_length-2).".".substr(osc_version(),$version_length-2, 1);


        if($marketPage>=1) $marketPage--;
        $action = Params::getParam("action");

        $js_lang = array(
                'by'                 => __('by'),
                'ok'                 => __('Ok'),
                'error_item'         => __('There was a problem, try again later please'),
                'wait_download'      => __('Please wait until the download is completed'),
                'downloading'        => __('Downloading'),
                'close'              => __('Close'),
                'download'           => __('Download'),
                'update'             => __('Update'),
                'last_update'        => __('Last update'),
                'downloads'          => __('Downloads'),
                'requieres_version'  => __('Requires at least'),
                'compatible_with'    => __('Compatible up to'),
                'screenshots'        => __('Screenshots'),
                'preview_theme'      => __('Preview theme'),
                'download_manually'  => __('Download manually'),
                'proceed_anyway'     => sprintf(__('Warning! This package is not compatible with your current version of Osclass (%s)'), $main_version),
                'sure'               => __('Are you sure?'),
                'proceed_anyway_btn' => __('Ok, proceed anyway'),
                'not_compatible'     => sprintf(__('Warning! This theme is not compatible with your current version of Osclass (%s)'), $main_version),
                'themes'             => array(
                                         'download_ok' => __('The theme has been downloaded correctly, proceed to activate or preview it.')
                                     ),
                'plugins'            => array(
                                         'download_ok' => __('The plugin has been downloaded correctly, proceed to install and configure.')
                                     ),
                'languages'          => array(
                                         'download_ok' => __('The language has been downloaded correctly, proceed to activate.')
                                     )

            );
        ?>
        <script type="text/javascript">
            var theme = window.theme || {};
            theme.adminBaseUrl  = "<?php echo osc_admin_base_url(true); ?>";
            theme.marketAjaxUrl = "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=market&<?php echo osc_csrf_token_url(); ?>";
            theme.themUrl       = "<?php echo osc_current_admin_theme_url(); ?>";
            theme.langs         = <?php echo json_encode($js_lang); ?>;
            theme.CSRFToken     = "<?php echo osc_csrf_token_url(); ?>";

            var osc_market = {};
            osc_market.main_version = <?php echo $main_version; ?>;
        </script>
        <?php
    }
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
                <li><a id="market_connect" href="#"><?php _e('Connect'); ?></a></li>
            <?php }; ?>
        </ul>

        <script type="text/javascript">
            $(document).ready(function() {
                <?php if(osc_market_api_connect()=='') { ?>
                $("#dialog-connect").dialog({
                    autoOpen: false,
                    modal: true
                });
                $("#connect-submit").on('click', function() {
                    $.getJSON(
                        '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=market_connect',
                        {'s_email' : $('#connect_user').attr('value'), 's_password' : $('#connect_password').attr('value')},
                        function(data){
                            console.log(data);
                            if(data==null) {
                                var data = new Object();
                                data.error = 1;
                                data.msg = '<?php _e('Sorry, the market is currently unavailable. Please try again in a few moments.'); ?>';
                            }
                            if(data.error==1) {
                                alert(data.msg);
                                var flash = $("#flash_js");
                                var message = $('<div>').addClass('pubMessages').addClass(class_type).attr('id', 'flashmessage').html(data.msg);
                                flash.html(message);
                                $("#flashmessage").slideDown('slow').delay(3000).slideUp('slow');
                            } else {
                                $('#content-head div.banner-market').html(data.html);
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

                <?php }; ?>

                $.getJSON(
                    '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=market_header',
                    function(data){
                        if(data.error==1) {
                        } else {
                            $('#content-head div.banner-market').html(data.html);
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
    <div class="form-horizontal">
        <div class="form-row">
            <p>
                <input type="text" name="connect_user" id="connect_user" value="" placeholder="<?php _e('Your market email'); ?>"/>
            </p>
            <p>
                <input type="password" name="connect_password" id="connect_password" value="" placeholder="<?php _e('Your market password'); ?>"/>
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
</div>
<?php }; ?>