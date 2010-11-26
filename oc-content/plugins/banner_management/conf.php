<?php
/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?>
<script type="text/javascript" src="<?php echo WEB_PATH; ?>/oc-includes/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({ 
        //cleanup: false,
        //plugins : 'codeprotect',
        remove_linebreaks : true,
        dialog_type : "modal",
        //editor_selector : "html",
        //force_p_newlines : false,
        forced_root_block : false,
        mode : "textareas",
        theme : "advanced",
        skin: "o2k7",
                width: "100%",
                height: "180px",
        skin_variant : "silver",
        theme_advanced_buttons1 : "bold,italic,underline,separator,undo,redo,separator,justifycenter,justifyright,justifyfull,separator,bullist,numlist,separator,link,unlink,separator,image,media",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_align : "left",
        theme_advanced_toolbar_location : "top",
        plugins : "media",
    });

function toggleEditor(id) {
    if (!tinyMCE.getInstanceById(id))
        tinyMCE.execCommand('mceAddControl', false, id);
    else
        tinyMCE.execCommand('mceRemoveControl', false, id);
}
</script>
<?php 

    $conn = getConnection() ;
    
    if(isset($_REQUEST['plugin_action'])) 
    {
        switch($_REQUEST['plugin_action']) 
        {
            case("banner_delete"):    if(isset($_REQUEST['id']) && $_REQUEST['id']!="") {
                                        $conn->osc_dbExec('DELETE FROM %st_bm_banner_campaign WHERE fk_i_banner_id = %d', DB_TABLE_PREFIX, $_REQUEST['id']);
                                        $conn->osc_dbExec('DELETE FROM %st_bm_banner WHERE pk_i_id = %d', DB_TABLE_PREFIX, $_REQUEST['id']);
                                    }
            break;
            case("banner_add"):       if(isset($_REQUEST['s_name']) && isset($_REQUEST['s_code'])) {
                                        $conn->osc_dbExec("INSERT INTO `%st_bm_banner` ( `s_name`, `s_code` ) VALUES ( '%s', '%s')", DB_TABLE_PREFIX, $_REQUEST['s_name'], $_REQUEST['s_code']);
                                    }
            break;
            case("banner_edit"):      if(isset($_REQUEST['s_name']) && isset($_REQUEST['s_code'])) {
                                            $conn->osc_dbExec("UPDATE  `%st_bm_banner` SET  `s_name` =  '%s', `s_code` = '%s'  WHERE  `pk_i_id` = %d ;", DB_TABLE_PREFIX, $_REQUEST['s_name'], $_REQUEST['s_code'], $_REQUEST['id']);
                                    }
            break;
            case("campaign_delete"):   if(isset($_REQUEST['id']) && $_REQUEST['id']!="") {
                                        $conn->osc_dbExec('DELETE FROM %st_item_car_model_attr WHERE pk_i_id = %d', DB_TABLE_PREFIX, $_REQUEST['id']);
                                    }
            break;

            case("campaign_add"):      
                                    if(isset($_REQUEST['s_name']) && $_REQUEST['s_name']!="" && isset($_REQUEST['s_code']) && $_REQUEST['s_code']!="") {
                                        $conn->osc_dbExec("INSERT INTO `%st_bm_campaign` ( `s_campaign_name`, `s_code`) VALUES ( '%s', '%s')", DB_TABLE_PREFIX, $_REQUEST['s_name'], $_REQUEST['s_code']);
                                    }
            break;
            case("campaign_edit"):     if(isset($_REQUEST['makeId']) && $_REQUEST['makeId']!="" && isset($_REQUEST['model']) && is_array($_REQUEST['model'])) {
                                        foreach($_REQUEST['model'] as $k => $v) {
                                            $conn->osc_dbExec("UPDATE  `%st_item_car_model_attr` SET  `s_name` =  '%s' WHERE  `pk_i_id` = %d AND `fk_i_make_id` = %d;", DB_TABLE_PREFIX, $v, $k, $_REQUEST['makeId']);
                                        }
                                    }
            break;
            case("bc_delete"):    if(isset($_REQUEST['id']) && $_REQUEST['id']!="") {
                                        $conn->osc_dbExec('DELETE FROM %st_bm_banner_campaign WHERE fk_i_banner_id = %d AND fk_i_campaign_id = %d', DB_TABLE_PREFIX, $_REQUEST['id'], $_REQUEST['campaignId']);
                                    }
            break;
            case("bc_add"):       if(isset($_REQUEST['id']) && isset($_REQUEST['campaignId'])) {
                                        $conn->osc_dbExec("INSERT INTO `%st_bm_banner_campaign` ( `fk_i_banner_id`, `fk_i_campaign_id` ) VALUES ( %d, %d)", DB_TABLE_PREFIX, $_REQUEST['id'], $_REQUEST['campaignId']);
                                    }
            break;
        }
    }
    
    switch($_REQUEST['section']) 
    {
        default:
        case("banner"): ?>
    
                            <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                                <div style="padding: 20px;">
                                    <div style="float: left; width: 50%;">
                                        <fieldset>
                                        <legend><?php echo __('Banners'); ?></legend>
                                        <ul>
                                        <?php
                                            $banners = $conn->osc_dbFetchResults('SELECT * FROM %st_bm_banner', DB_TABLE_PREFIX);
                                            foreach($banners as $banner) {
                                                echo '<li>'.$banner['s_name'].' | <a href="plugins.php?action=renderplugin&file=banner_management/conf.php?section=banner&plugin_action=banner_edit&id='.$banner['pk_i_id'].'" >'.__('Edit').'</a> | <a href="plugins.php?action=renderplugin&file=banner_management/conf.php?section=banner&plugin_action=banner_delete&id='.$banner['pk_i_id'].'" >'.__('Delete').'</a> </li>';
                                            }
                                        ?>
                                        </ul>
                                        </fieldset>
                                    </div>
                                    <?php if(isset($_REQUEST['plugin_action']) && $_REQUEST['plugin_action']=='banner_edit') {
                                        $banner = $conn->osc_dbFetchResult('SELECT * FROM %st_bm_banner WHERE pk_i_id = %d', DB_TABLE_PREFIX, $_REQUEST['id']); ?>
                                    <div style="float: left; width: 50%;">
                                        <fieldset>
                                        <legend><?php echo __('Edit banner'); ?></legend>
                                        <form name="bm_form" id="bm_form" action="plugins.php" method="GET" enctype="multipart/form-data" >
                                        <input type="hidden" name="action" value="renderplugin" />
                                        <input type="hidden" name="file" value="banner_management/conf.php" />
                                        <input type="hidden" name="section" value="banner" />
                                        <input type="hidden" name="id" value="<?php echo isset($_REQUEST['id'])?$_REQUEST['id']:'';?>" />
                                        <input type="hidden" name="plugin_action" value="banner_edit" />
                            
                                        <label><?php _e('Short name to help you identify this ad'); ?></label><br />
                                        <input name="s_name" id="s_name" value="<?php echo $banner['s_name'];?>" /><br />
                                        <label><?php _e('HTML code for the ad '); ?></label><br />
                                        <div style="width=100%;margin-right:10px;">
                                            <textarea name="s_code" style="width:100%;height:180px;" ><?php echo $banner['s_code'];?></textarea>
                                            <a style="align:right;" href="javascript:toggleEditor('s_code');"><?php _e('[On/Off] HTML editor');?></a>
                                        </div>
                                        <button type="submit" ><?php echo  __('Save'); ?></button>
                                        </form>
                                        </fieldset>
                                    </div>
                                    <?php } else { ?>
                                    <div style="float: left; width: 50%;">
                                        <fieldset>
                                        <legend><?php echo __('Add new banner'); ?></legend>
                                        <form name="bm_form" id="bm_form" action="plugins.php" method="GET" enctype="multipart/form-data" >
                                        <input type="hidden" name="action" value="renderplugin" />
                                        <input type="hidden" name="file" value="banner_management/conf.php" />
                                        <input type="hidden" name="section" value="banner" />
                                        <input type="hidden" name="plugin_action" value="banner_add" />
                            
                                        
                                        <label><?php _e('Short name to help you identify this ad'); ?></label><br />
                                        <input name="s_name" id="s_name" value="" /><br />
                                        <label><?php _e('HTML code for the ad '); ?></label><br />
                                        <div style="width=100%;margin-right:10px;">
                                            <textarea name="s_code" style="width:100%;height:180px;" ><?php echo __('Insert your image / media here. You could use the HTML button to edit directly the HTML code of this banner.');?></textarea>
                                            <a style="align:right;" href="javascript:toggleEditor('s_code');"><?php _e('[On/Off] HTML editor');?></a>
                                        </div>
                                        <button type="submit" ><?php echo  __('Add new'); ?></button>
                                        </form>
                                        </fieldset>
                                    </div>
                                    <?php }; ?>
                                    <div style="clear: both;"></div>
                                                                            
                                </div>
                            </div>
        <?php 
        break;
        case ("campaign"): ?>
                            <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                                <div style="padding: 20px;">
                                    <div style="float: left; width: 50%;">
                                        <fieldset>
                                        <legend><?php echo __('Banners in campaign'); ?></legend>
                                        <?php $campaign = $conn->osc_dbFetchResults('SELECT * FROM %st_bm_campaign', DB_TABLE_PREFIX); ?>
                                        <select name="campaign" id="campaign" onchange="location.href = 'plugins.php?action=renderplugin&file=banner_management/conf.php?section=campaign&campaignId=' + this.value" >
                                            <option value=""><?php echo  __('Select a campaign'); ?></option>
                                            <?php foreach($campaign as $a): ?>
                                            <option value="<?php echo $a['pk_i_id']; ?>" <?php if(isset($_REQUEST['campaignId']) && $_REQUEST['campaignId']!="" && $_REQUEST['campaignId']==$a['pk_i_id']) { echo 'selected'; };?>><?php echo $a['s_campaign_name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <form name="bm_form" id="bm_form" action="plugins.php" method="GET" enctype="multipart/form-data" >
                                        <input type="hidden" name="action" value="renderplugin" />
                                        <input type="hidden" name="file" value="banner_management/conf.php" />
                                        <input type="hidden" name="section" value="campaign" />
                                        <?php if(isset($_REQUEST['campaignId']) && $_REQUEST['campaignId']!="") { ?>
                                            <input type="hidden" name="campaignId" value="<?php echo  $_REQUEST['campaignId'];?>" />
                                        <?php }; ?>
                                        <ul>
                                        <?php
                                            if(isset($_REQUEST['campaignId']) && $_REQUEST['campaignId']!="") {
                                                $banners = $conn->osc_dbFetchResults('SELECT * FROM %st_bm_campaign WHERE fk_i_campaign_id = %d ', DB_TABLE_PREFIX, $_REQUEST['campaignId']);
                                                foreach($banners as $banner) {
                                                    echo '<li><label>'.$banner['s_campaign_name'].'</label> <a href="plugins.php?action=renderplugin&file=cars_attributes/conf.php?section=models&plugin_action=model_delete&makeId='.$_REQUEST['campaignId'].'&id='.$banner['fk_i_bannner_id'].'" ><button>'.__('Delete').'</button></a> </li>';
                                                }
                                            } else {
                                                echo '<li>Select a campaign first.</li>';
                                            }
                                        ?>
                                        </ul>
                                        <button type="submit"><?php echo  __('Edit');?></button>
                                        </form>
                                        </fieldset>
                                    </div>
                            
                                    <div style="float: left; width: 50%;">
                                        <fieldset>
                                        <legend><?php echo __('Add banners to your campaign'); ?></legend>
                                        <ul>
                                        <?php
                                            if(isset($_REQUEST['campaignId'])) {
                                                $banners = $conn->osc_dbFetchResults('SELECT * FROM %st_bm_banner', DB_TABLE_PREFIX);
                                                $bcs = $conn->osc_dbFetchResults('SELECT * FROM %st_bm_banner_campaign WHERE fk_i_campaign_id = %d', DB_TABLE_PREFIX, $_REQUEST['campaignId']);
                                                foreach($banners as $banner) {
                                                    $str = '<a href="plugins.php?action=renderplugin&file=banner_management/conf.php?section=campaign&plugin_action=bc_add&campaignId='.$_REQUEST['campaignId'].'&id='.$banner['pk_i_id'].'" >'.__('Add').'</a>';
                                                    foreach($bcs as $bc) {
                                                        if($bc['fk_i_banner_id']==$banner['pk_i_id']) {
                                                            $str = '<a href="plugins.php?action=renderplugin&file=banner_management/conf.php?section=campaign&plugin_action=bc_delete&campaignId='.$_REQUEST['campaignId'].'&id='.$banner['pk_i_id'].'" >'.__('Remove').'</a>';
                                                            break;
                                                        }
                                                    }
                                                    echo '<li>'.$banner['s_name'].' '.$str.' </li>';
                                                }
                                            } else {
                                                echo __('Select a campaign to ad/remove banners, first.');
                                            }
                                        ?>
                                        </ul>
                                        </fieldset>


                                        <fieldset>
                                        <legend><?php echo __('Create new campaign'); ?></legend>
                                        <form name="bm_form" id="bm_form" action="plugins.php" method="GET" enctype="multipart/form-data" >
                                        <input type="hidden" name="action" value="renderplugin" />
                                        <input type="hidden" name="file" value="banner_management/conf.php" />
                                        <input type="hidden" name="section" value="campaign" />
                                        <input type="hidden" name="plugin_action" value="campaign_add" />

                                        <label><?php _e('Write a name for your campaign');?></label><br />
                                        <input name="s_name" id="s_name" value="" /><br />
                                        <label><?php _e('HTML code for the campaign. User \'[banner]\' for insert random banners or \'[banner#banner_name]\' for specific abnner.'); ?></label><br />
                                        <div style="width=100%;margin-right:10px;">
                                            <textarea name="s_code" style="width:100%;height:180px;" ><?php echo __('Example of banner inserting. <ul><li>[banner#example]</li><li>[banner]</li></ul>');?></textarea>
                                            <a style="align:right;" href="javascript:toggleEditor('s_code');"><?php _e('[On/Off] HTML editor');?></a>
                                        </div>
                                        <button type="submit" ><?php echo  __('Add new'); ?></button>
                                        </form>
                                        </fieldset>
                                    </div>
                            
                                    <div style="clear: both;"></div>
                                                                            
                                </div>
                            </div>
        <?php 
        break;
    } ?>
