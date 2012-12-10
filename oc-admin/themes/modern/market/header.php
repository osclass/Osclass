<?php
    osc_enqueue_style('market', osc_current_admin_theme_styles_url('market.css'));

    /*
    */
    osc_add_hook('admin_header','addMarketJSON');
    function addMarketJSON(){
        $marketPage = Params::getParam("mPage");
        if($marketPage>=1) $marketPage-- ;
        $action = Params::getParam("action");

        $out    = osc_file_get_contents(osc_market_url($action)."page/".$marketPage);
        echo '<script type="text/javascript">var marketData='.$out.'</script>';
        $js_lang = array(
                'by'                => __('by'),
                'download'          => __('Download'),
                'downloads'         => __('Downloads'),
                'requieres_version' => __('Requires at least'),
                'compatible_with'   => __('Compatible up to'),
                'screenshots'       => __('Screenshots'),
                'download_manually' => __('Download manually'),
                'themes' => array(
                                'download_ok' => __('The theme has been downloaded correctly, proceed to activate or preview it.')
                            ),
                'plugins' => array(
                                'download_ok' => __('The plugin has been downloaded correctly, proceed to install and configure.')
                            )

            );
        ?>
        <script type="text/javascript">
            var theme = window.theme || {};
            theme.langs = <?php echo json_encode($js_lang); ?>
        </script>
        <?php
    }


    function drawMarketItem($item){
        $thumbnail = '';
        if($item['s_thumbnail']){
            $thumbnail = $item['s_thumbnail'];
        }
        if($item['s_banner']){
            $thumbnail = 'http://market.osclass.org/oc-content/uploads/market/'.$item['s_banner'];
        }
        $item['total_downloads'] = 335;
        echo '<a href="#'.$item['s_update_url'].'">';
        echo '<div class="mk-item mk-item-'.strtolower($item['e_type']).'" data-type="'.strtolower($item['e_type']).'">';
        echo '    <div class="banner" style="background-image:url('.$thumbnail.');"></div>';
        echo '    <div class="mk-info">';
        echo '        <h3>'.$item['s_title'].'</h3>';
        echo '        <i>by '.$item['s_contact_name'].'</i>';
        echo '        <div>';
        echo '            <span class="more">'.__('View more').'</span>';
        echo '            <span class="downloads"><strong>'.$item['total_downloads'].'</strong>'.__('downloads').'</span>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
        echo '</a>';
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
        <h1><?php _e('Market') ; ?></h1>
        <ul class="tabs">
            <li <?php if($action == ''){ echo 'class="active"';} ?>><a href="<?php echo osc_admin_base_url(true).'?page=market'; ?>"><?php _e('Market'); ?></a></li>
            <li <?php if($action == 'plugins'){ echo 'class="active"';} ?>><a href="<?php echo osc_admin_base_url(true).'?page=market&action=plugins'; ?>"><?php _e('Plugins'); ?></a></li>
            <li <?php if($action == 'themes'){ echo 'class="active"';} ?>><a href="<?php echo osc_admin_base_url(true).'?page=market&action=themes'; ?>"><?php _e('Themes'); ?></a></li>
            <li <?php if($action == 'languages'){ echo 'class="active"';} ?>><a href="<?php echo osc_admin_base_url(true).'?page=market&action=languages'; ?>"><?php _e('Languages'); ?></a></li>
        </ul>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return __('Market');
    }
    osc_add_filter('admin_title', 'customPageTitle');
    osc_current_admin_theme_path( 'parts/header.php' ) ;
?>
