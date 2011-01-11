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

<?php
    $conn = getConnection();
if(isset($_REQUEST['plugin_action'])) {
    if($_REQUEST['plugin_action']=="type_delete") {
        if(isset($_REQUEST['id']) && $_REQUEST['id']!="") {
            $conn->osc_dbExec('DELETE FROM %st_item_house_property_type_attr WHERE pk_i_id = %d', DB_TABLE_PREFIX, $_REQUEST['id']);
        }
    } else if($_REQUEST['plugin_action']=="type_add") {
        $dataItem = array();
        foreach ($_REQUEST as $k => $v) {
            if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                $dataItem[$m[1]][$m[2]] = $v;
            }
        }
        // insert locales
        $lastId = $conn->osc_dbFetchResult('SELECT pk_i_id FROM %st_item_house_property_type_attr ORDER BY pk_i_id DESC LIMIT 1', DB_TABLE_PREFIX);
        $lastId = $lastId['pk_i_id'] + 1 ;
        foreach ($dataItem as $k => $_data) {
            $conn->osc_dbExec("REPLACE INTO %st_item_house_property_type_attr (pk_i_id, fk_c_locale_code, s_name) VALUES (%d, '%s', '%s')", DB_TABLE_PREFIX, $lastId, $k, $_data['property_type'] );
        }
    } else if($_REQUEST['plugin_action']=="type_edit") {
        foreach($_REQUEST['property_type'] as $k => $v) {
            foreach($v as $kj => $vj) {
                $conn->osc_dbExec("REPLACE INTO %st_item_house_property_type_attr (pk_i_id, fk_c_locale_code, s_name) VALUES (%d, '%s', '%s')", DB_TABLE_PREFIX, $k, $kj, $vj );
            }
        }
    }
}
?>

<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
    <div style="padding: 20px;">
        <div style="float: left; width: 50%;">
            <fieldset>
                <legend><?php _e('Property types'); ?></legend>
                    <form name="propertys_form" id="propertys_form" action="plugins.php" method="GET" enctype="multipart/form-data" >
                <div class="tabber">
                <?php $locales = Locale::newInstance()->listAllEnabled(true);
                    $property_type = $conn->osc_dbFetchResults('SELECT * FROM %st_item_house_property_type_attr', DB_TABLE_PREFIX);
                    $data = array();
                    foreach ($property_type as $c) {
                        $data[$c['fk_c_locale_code']][] = array('pk_i_id' => $c['pk_i_id'], 's_name' => $c['s_name']);
                    }
                    $default = current($data);
                    if(is_array($default)) {
                    foreach($default as $d) {
                        $data['new'][] = array('pk_i_id' => $d['pk_i_id'], 's_name' => '');
                    }}
                ?>
                    <?php foreach($locales as $locale) {?>
                        <div class="tabbertab">
                            <h2><?php echo $locale['s_name']; ?></h2>
                                <input type="hidden" name="action" value="renderplugin" />
                                <input type="hidden" name="file" value="realstate_attributes/conf.php" />
                                <input type="hidden" name="section" value="types" />
                                <input type="hidden" name="plugin_action" value="type_edit" />
                                <ul>
                                <?php
                                    if(count($data)>0) {
                                        foreach(isset($data[$locale['pk_c_code']])?$data[$locale['pk_c_code']]:$data['new'] as $property_type) { ?>
                                            <li><input name="property_type[<?php echo  $property_type['pk_i_id'];?>][<?php echo  $locale['pk_c_code'];?>]" id="<?php echo $property_type['pk_i_id'];?>" type="text" value="<?php echo  $property_type['s_name'];?>" /> <a href="plugins.php?action=renderplugin&file=realstate_attributes/conf.php?plugin_action=type_delete&id=<?php echo  $property_type['pk_i_id'];?>" ><button><?php _e('Delete');?></button></a> </li>
                                        <?php };
                                    }; ?>
                                </ul>
                        </div>
                        <?php }; ?>
                        <button type="submit"><?php echo  __('Edit');?></button>
                    </form>
                </div>
            </fieldset>
        </div>
        <div style="float: left; width: 50%;">
            <fieldset>
                <legend><?php _e('Add new property types'); ?></legend>
                <form name="propertys_form" id="propertys_form" action="plugins.php" method="GET" enctype="multipart/form-data" >
                    <input type="hidden" name="action" value="renderplugin" />
                    <input type="hidden" name="file" value="realstate_attributes/conf.php" />
                    <input type="hidden" name="plugin_action" value="type_add" />

                    <div class="tabber">
                    <?php $locales = Locale::newInstance()->listAllEnabled(true);
                        $property_type = $conn->osc_dbFetchResults('SELECT * FROM %st_item_house_property_type_attr', DB_TABLE_PREFIX);
                        $data = array();
                        foreach ($property_type as $c) {
                            $data[$locale['pk_c_code']] = array('pk_i_id' => $c['pk_i_id'], 's_name' => $c['s_name']);
                        }
                    ?>
                    <?php foreach($locales as $locale) {?>
                        <div class="tabbertab">
                            <h2><?php echo $locale['s_name']; ?></h2>
                            <input name="<?php echo  $locale['pk_c_code'];?>#property_type" id="property_type" type="text" value="" />
                        </div>
                    <?php }; ?>
                    </div>
                    <button type="submit" ><?php echo  __('Add new'); ?></button>
                </form>
            </fieldset>
        </div>
        <div style="clear: both;"></div>										
    </div>
</div>
<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
    <div style="padding: 20px;">
        <div style="float: left; width: 100%;">
            <fieldset style="border: 1px solid #ff0000;">
                <legend><?php _e('Warning'); ?></legend>
                <p>
                    <?php _e('Deleting property types may end in errors. Some of those property types could be attached to some actual items.') ; ?>
                </p>
            </fieldset>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
