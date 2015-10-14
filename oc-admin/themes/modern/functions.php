<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');

osc_add_filter('admin_body_class', 'admin_modeCompact_class');
function admin_modeCompact_class($args){
    $compactMode = osc_get_preference('compact_mode','modern_admin_theme');
    if($compactMode == true){
        $args[] = 'compact';
    }
    return $args;
}
osc_add_hook('ajax_admin_compactmode','modern_compactmode_actions');
function modern_compactmode_actions(){
    $compactMode = osc_get_preference('compact_mode','modern_admin_theme');
    $modeStatus  = array('compact_mode'=>true);
    if($compactMode == true){
        $modeStatus['compact_mode'] = false;
    }
    osc_set_preference('compact_mode', $modeStatus['compact_mode'], 'modern_admin_theme');
    echo json_encode($modeStatus);
}

// favicons
function admin_header_favicons() {
    $favicons   = array();
    $favicons[] = array(
        'rel'   => 'shortcut icon',
        'sizes' => '',
        'href'  => osc_current_admin_theme_url('images/favicon-48.png')
    );
    $favicons[] = array(
        'rel'   => 'apple-touch-icon-precomposed',
        'sizes' => '144x144',
        'href'  => osc_current_admin_theme_url('images/favicon-144.png')
    );
    $favicons[] = array(
        'rel'   => 'apple-touch-icon-precomposed',
        'sizes' => '114x114',
        'href'  => osc_current_admin_theme_url('images/favicon-114.png')
    );
    $favicons[] = array(
        'rel'   => 'apple-touch-icon-precomposed',
        'sizes' => '72x72',
        'href'  => osc_current_admin_theme_url('images/favicon-72.png')
    );
    $favicons[] = array(
        'rel'   => 'apple-touch-icon-precomposed',
        'sizes' => '',
        'href'  => osc_current_admin_theme_url('images/favicon-57.png')
    );

    $favicons = osc_apply_filter('admin_favicons', $favicons);

    foreach($favicons as $f) { ?>
        <link <?php if($f['rel'] !== '') { ?>rel="<?php echo $f['rel']; ?>" <?php } if($f['sizes'] !== '') { ?>sizes="<?php echo $f['sizes']; ?>" <?php } ?>href="<?php echo $f['href']; ?>">
    <?php }
}
osc_add_hook('admin_header', 'admin_header_favicons');

// admin footer
function admin_footer_html() { ?>
    <div class="float-left">
        <?php printf(__('Thank you for using <a href="%s" target="_blank">Osclass</a>'), 'http://osclass.org/'); ?> -
        <a title="<?php _e('Documentation'); ?>" href="http://doc.osclass.org/" target="_blank"><?php _e('Documentation'); ?></a> &middot;
        <a title="<?php _e('Forums'); ?>" href="http://forums.osclass.org/" target="_blank"><?php _e('Forums'); ?></a> &middot;
        <a title="<?php _e('Feedback'); ?>" href="https://osclass.uservoice.com/" target="_blank"><?php _e('Feedback'); ?></a>
    </div>
    <div class="float-right">
        <strong>Osclass <?php echo preg_replace('|.0$|', '', OSCLASS_VERSION); ?></strong>
    </div>
    <a id="ninja" href="" class="ico ico-48 ico-dash-white"></a>
    <div class="clear"></div>
    <form id="donate-form" name="_xclick" action="https://www.paypal.com/in/cgi-bin/webscr" method="post" target="_blank">
       <input type="hidden" name="cmd" value="_donations">
       <input type="hidden" name="business" value="info@osclass.org">
       <input type="hidden" name="item_name" value="Osclass project">
       <input type="hidden" name="return" value="<?php echo osc_admin_base_url(); ?>">
       <input type="hidden" name="currency_code" value="USD">
       <input type="hidden" name="lc" value="US" />
    </form>

<script type="text/javascript">
        var $ninja = $('#ninja');

        $ninja.click(function(){
            jQuery('#donate-form').submit();
            return false;
        });
    </script><?php
}
osc_add_hook('admin_content_footer', 'admin_footer_html');

// scripts
function admin_theme_js() {
    osc_load_scripts();
}
osc_add_hook('admin_header', 'admin_theme_js', 9);

// css
function admin_theme_css() {
    osc_load_styles();
}
osc_add_hook('admin_header', 'admin_theme_css', 9);

function printLocaleTabs($locales = null) {
    if($locales==null) { $locales = osc_get_locales(); }
    $num_locales = count($locales);
    if($num_locales>1) {
    echo '<div id="language-tab" class="ui-osc-tabs ui-tabs ui-widget ui-widget-content ui-corner-all"><ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">';
        foreach($locales as $locale) {
            echo '<li class="ui-state-default ui-corner-top"><a href="#'.$locale['pk_c_code'].'">'.$locale['s_name'].'</a></li>';
        }
    echo '</ul></div>';
    };
}

function printLocaleTitle($locales = null, $item = null) {
    if($locales==null) { $locales = osc_get_locales(); }
    if($item==null) { $item = osc_item(); }
    $num_locales = count($locales);
    foreach($locales as $locale) {
        echo '<div class="input-has-placeholder input-title-wide"><label for="title">' . __('Enter title here') . ' *</label>';
        $title = (isset($item) && isset($item['locale'][$locale['pk_c_code']]) && isset($item['locale'][$locale['pk_c_code']]['s_title'])) ? $item['locale'][$locale['pk_c_code']]['s_title'] : '';
        if( Session::newInstance()->_getForm('title') != "" ) {
            $title_ = Session::newInstance()->_getForm('title');
            if( $title_[$locale['pk_c_code']] != "" ){
                $title = $title_[$locale['pk_c_code']];
            }
        }
        $name = 'title'. '[' . $locale['pk_c_code'] . ']';
        echo '<input id="' . $name . '" type="text" name="' . $name . '" value="' . osc_esc_html(htmlentities($title, ENT_COMPAT, "UTF-8")) . '"  />';
        echo '</div>';
    }
}

function printLocaleTitlePage($locales = null,$page = null) {
    if($locales==null) { $locales = osc_get_locales(); }
    $aFieldsDescription = Session::newInstance()->_getForm("aFieldsDescription");
    $num_locales = count($locales);
    echo '<label for="title">' . __('Title') . ' *</label>';

    foreach($locales as $locale) {
        $title = '';
        if(isset($page['locale'][$locale['pk_c_code']])) {
            $title = $page['locale'][$locale['pk_c_code']]['s_title'];
        }
        if( isset($aFieldsDescription[$locale['pk_c_code']]) && isset($aFieldsDescription[$locale['pk_c_code']]['s_title']) &&$aFieldsDescription[$locale['pk_c_code']]['s_title'] != '' ) {
            $title = $aFieldsDescription[$locale['pk_c_code']]['s_title'];
        }
        $name = $locale['pk_c_code'] . '#s_title';

        echo '<div class="input-has-placeholder input-title-wide"><label for="title">' . __('Enter title here') . ' *</label>';
        echo '<input id="' . $name . '" type="text" name="' . $name . '" value="' . osc_esc_html(htmlentities($title, ENT_COMPAT, "UTF-8")) . '"  />';
        echo '</div>';
    }
}

function printLocaleDescription($locales = null, $item = null) {
    if($locales==null) { $locales = osc_get_locales(); }
    if($item==null) { $item = osc_item(); }
    $num_locales = count($locales);
    foreach($locales as $locale) {
        $name = 'description'. '[' . $locale['pk_c_code'] . ']';

        echo '<div><label for="description">' . __('Description') . ' *</label>';
        $description = (isset($item) && isset($item['locale'][$locale['pk_c_code']]) && isset($item['locale'][$locale['pk_c_code']]['s_description'])) ? $item['locale'][$locale['pk_c_code']]['s_description'] : '';
        if( Session::newInstance()->_getForm('description') != "" ) {
            $description_ = Session::newInstance()->_getForm('description');
            if( $description_[$locale['pk_c_code']] != "" ){
                $description = $description_[$locale['pk_c_code']];
            }
        }
        echo '<textarea id="' . $name . '" name="' . $name . '" rows="10">' . $description . '</textarea></div>';
    }
}

function printLocaleDescriptionPage($locales = null, $page = null) {
    if($locales==null) { $locales = osc_get_locales(); }
    $aFieldsDescription = Session::newInstance()->_getForm("aFieldsDescription");
    $num_locales = count($locales);

    foreach($locales as $locale) {
        $description = '';
        if(isset($page['locale'][$locale['pk_c_code']])) {
            $description = $page['locale'][$locale['pk_c_code']]['s_text'];
        }
        if( isset($aFieldsDescription[$locale['pk_c_code']]) && isset($aFieldsDescription[$locale['pk_c_code']]['s_text']) &&$aFieldsDescription[$locale['pk_c_code']]['s_text'] != '' ) {
            $description = $aFieldsDescription[$locale['pk_c_code']]['s_text'];
        }
        $name = $locale['pk_c_code'] . '#s_text';
        echo '<div><label for="description">' . __('Description') . ' *</label>';
        echo '<textarea id="' . $name . '" name="' . $name . '" rows="10">' . $description . '</textarea></div>';
    }
}

function drawMarketItem($item,$color = false){
    //constants
    $updateClass      = '';
    $updateData       = '';
    $thumbnail        = false;
    $featuredClass    = '';
    $style            = '';
    $letterDraw       = '';
    $compatible       = '';
    $type             = strtolower($item['e_type']);
    $items_to_update  = json_decode(osc_get_preference($type.'s_to_update'),true);
    $items_downloaded = json_decode(osc_get_preference($type.'s_downloaded'),true);

    if($item['s_thumbnail']){
        $thumbnail = $item['s_thumbnail'];
    }
    if($item['s_banner']){
        if(@$item['s_banner_path']!=''){
            $thumbnail = $item['s_banner_path'] . $item['s_banner'];
        } else {
            $thumbnail = 'http://market.osclass.org/oc-content/uploads/market/'.$item['s_banner'];
        }
    }

    $downloaded = false;
    if(in_array($item['s_update_url'], $items_downloaded)) {
        if (in_array($item['s_update_url'], $items_to_update)) {
            $updateClass = 'has-update';
            $updateData  = ' data-update="true"';
        } else {
            // market item downloaded !
            $downloaded = true;
        }
    }

    //Check if is compatibleosc_version()
    if($type=='language') {
        if(!check_market_language_compatibility($item['s_update_url'], $item['s_version'])){
            $compatible = ' not-compatible';
        }
    } else {
        if(!check_market_compatibility($item['s_compatible'],$type)){
            $compatible = ' not-compatible';
        }
    }


    if(!$thumbnail && $color){
        $thumbnail = osc_current_admin_theme_url('images/gr-'.$color.'.png');
        $letterDraw = $item['s_update_url'][0];
        if($type == 'language'){
            $letterDraw = $item['s_update_url'];
        }
    }
    if ($item['b_featured']) {
        $featuredClass = ' is-featured';
        if($downloaded || $updateClass){
            $featuredClass .= '-';
        }
    }
    if($downloaded) {
        $featuredClass .= 'is-downloaded';
    }

    $buyClass = '';
    if($item['i_price'] != '' && (float)$item['i_price'] > 0  && $item['b_paid'] == 1) {
        $buyClass = ' is-buy ';
    }

        $style = 'background-image:url('.$thumbnail.');';
    echo '<a href="#'.$item['s_update_url'].'" class="mk-item-parent '.$featuredClass.$updateClass.$compatible.$buyClass.'" data-type="'.$type.'"'.$updateData.' data-gr="'.$color.'" data-letter="'.$item['s_update_url'][0].'">';
    echo '<div class="mk-item mk-item-'.$type.'">';
    echo '    <div class="banner" style="'.$style.'">'.$letterDraw.'</div>';
    echo '    <div class="mk-info"><i class="flag"></i>';
    echo '        <h3>'.$item['s_title'].'</h3>';
    echo '        <span class="downloads"><strong>'.$item['i_total_downloads'].'</strong> '.__('downloads').'</span>';
    echo '        <i>by '.$item['s_contact_name'].'</i>';
    echo '        <div class="market-actions">';
    echo '            <span class="more">'.__('View more').'</span>';
    if($item['i_price'] != '' && (float)$item['i_price'] > 0 && $item['b_paid'] == 0) {
        echo '            <span class="buy-btn' . $compatible . '" data-code="' . $item['s_buy_url'] . '" data-type="' . $type . '"' . '>' . sprintf(__('Buy $%s'), number_format($item['i_price']/1000000, 0, '.', ',')) . '</span>';
    } else {
        echo '            <span class="download-btn' . $compatible . '" data-code="' . $item['s_update_url'] . '" data-type="' . $type . '"' . '>' . __('Download') . '</span>';
    }
    echo '        </div>';
    echo '    </div>';
    echo '</div>';
    echo '</a>';
}

function check_market_language_compatibility($slug, $language_version) {
    return osc_check_language_update($slug);
}

function check_market_compatibility($versions) {
    $versions = explode(',',$versions);
    $current_version = OSCLASS_VERSION;

    foreach($versions as $_version) {
        $result = version_compare2(OSCLASS_VERSION, $_version);

        if( $result == 0 || $result == -1 ) {
            return true;
        }
    }
    return false;
}

function check_version_admin_footer() {
    if( (time() - osc_last_version_check()) > (24 * 3600) ) {
        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $.getJSON(
                    '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_version',
                    {},
                    function(data){}
                );
            });
        </script>
        <?php
    }
}
osc_add_hook('admin_footer', 'check_version_admin_footer');

function check_languages_admin_footer() {
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $.getJSON(
                '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_languages',
                {},
                function(data){}
            );
        });
    </script>
<?php
}

function check_themes_admin_footer() {
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $.getJSON(
                '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_themes',
                {},
                function(data){}
            );
        });
    </script>
<?php
}

function check_plugins_admin_footer() {
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $.getJSON(
                '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_plugins',
                {},
                function(data){}
            );
        });
    </script>
<?php
}

/* end of file */
