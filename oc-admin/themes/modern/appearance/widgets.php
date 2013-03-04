<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
    /**
     * Osclass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2012 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    osc_enqueue_script('jquery-validate');

    $info = __get("info");

    function addHelp() {
        echo '<p>' . __("Modify your site's header or footer here. Only works with compatible themes, such as those available in the market.") . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Appearance'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
    <?php
    }

    //customize Head
    function customHead() { ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#dialog-widget-delete").dialog({
                    autoOpen: false,
                    modal: true,
                    title: '<?php echo osc_esc_js( __('Delete widget') ); ?>'
                });
            });

            // dialog delete function
            function delete_dialog(widget_id) {
                $("#dialog-widget-delete input[name='id']").attr('value', widget_id);
                $("#dialog-widget-delete").dialog('open');
                return false;
            }

        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead', 10);

    function customPageTitle($string) {
        return sprintf(__('Appearance &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<div id="appearance-page">
    <div class="appearance">
        <h2 class="render-title"><?php _e('Manage Widgets'); ?> </h2>
    </div></div></div> <!-- -->
        <div class="grid-system">
            <?php if(isset($info['locations']) && is_array($info['locations'])) { ?>
                <?php foreach($info['locations'] as $location) { ?>
                    <div class="grid-row grid-50">
                        <div class="row-wrapper">
                            <div class="widget-box">
                                <div class="widget-box-title"><h3><?php printf( __('Section: %s'), $location ); ?> &middot; <a id="add_widget_<?php echo $location;?>" href="<?php echo osc_admin_base_url(true); ?>?page=appearance&amp;action=add_widget&amp;location=<?php echo $location; ?>" class="btn float-right"><?php _e('Add HTML widget'); ?></a></h3></div>
                                <div class="widget-box-content">
                                    <?php $widgets = Widget::newInstance()->findByLocation($location); ?>
                                    <?php if( count($widgets) > 0 ) {
                                        $countEvent = 1; ?>
                                        <table class="table" cellpadding="0" cellspacing="0">
                                            <tbody>
                                            <?php foreach($widgets as $w) { ?>
                                                <tr<?php if($countEvent%2 == 0){ echo ' class="even"';} if($countEvent == 1){ echo ' class="table-first-row"';} ?>>
                                                    <td><?php echo __('Widget'). ' ' . $w['pk_i_id']; ?></td>
                                                    <td><?php printf( __('Description: %s'), $w['s_description'] ); ?></td>
                                                    <td>
                                                        <?php printf('<a href="%1$s?page=appearance&amp;action=edit_widget&amp;id=%2$s&amp;location=%3$s">' . __('Edit') .'</a>', osc_admin_base_url(true), $w['pk_i_id'], $location); ?>
                                                        <a href="<?php printf('%s?page=appearance&amp;action=delete_widget&amp;id=%d"', osc_admin_base_url(true), $w['pk_i_id']);?>" onclick="return delete_dialog('<?php echo $w['pk_i_id']; ?>');"><?php _e('Delete');?></a>
                                                    </td>
                                                </tr>
                                            <?php
                                            $countEvent++;
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="grid-row grid-50">
                    <div class="row-wrapper">
                        <div class="widget-box">
                            <div class="widget-box-title"><h3><?php _("Current theme does not support widgets"); ?></h3></div>
                            <div class="widget-box-content">
                                <?php _e("Current theme does not support widgets"); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="clear"></div>
        </div>
    </div>
</div>
<form id="dialog-widget-delete" method="get" action="<?php echo osc_admin_base_url(true); ?>" class="has-form-actions hide">
    <input type="hidden" name="page" value="appearance" />
    <input type="hidden" name="action" value="delete_widget" />
    <input type="hidden" name="id" value="" />
    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to delete this widget?'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
            <a class="btn" href="javascript:void(0);" onclick="$('#dialog-widget-delete').dialog('close');"><?php _e('Cancel'); ?></a>
            <input id="widget-delete-submit" type="submit" value="<?php echo osc_esc_html( __('Delete') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>
<div class="grid-system">
            <div class="grid-row grid-100">
                <div class="row-wrapper">
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>