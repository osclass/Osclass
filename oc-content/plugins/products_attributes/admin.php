<script type="text/javascript">
    function checkAll (frm, check) {
        var aa = document.getElementById(frm);
        for (var i = 0 ; i < aa.elements.length ; i++) {
            aa.elements[i].checked = check;
        }
    }
    function checkCat(id, check) {
        var lay = document.getElementById("cat" + id);
        inp = lay.getElementsByTagName("input");
        for (var i = 0, maxI = inp.length ; i < maxI; ++i) {
            if(inp[i].type == "checkbox") {
                inp[i].checked = check;
            }
    }
	}

</script>

<?php
    $conn = getConnection();
    if(!isset($_GET['option'])) {
        $option = "none";
    } else {
        $option = $_GET['option'];
    }

    switch($option) {
        case 'none':
            //Select categories to show a tree
            $categories = Category::newInstance()->toTree();
            $selected = $conn->osc_dbFetchValues("SELECT fk_i_category_id FROM %st_plugin_category WHERE s_plugin_name = 'products_plugin'", DB_TABLE_PREFIX);
            $numCols = 1;
            $catsPerCol = round(count($categories)/$numCols);
    ?>
            <form id="frm3" action="plugins.php" method="post">
                <input type="hidden" name="action" value="renderplugin" />
                <input type="hidden" name="file" value="products_attributes/admin.php?option=stepone" />
                <?php _e('The plugin <strong>Products attributes</strong> adds a set of attributes to the categories you choose. Please select here all the categories where apply these attributes:'); ?>

                <table>
                    <tr style="vertical-align: top;">
                        <td style="font-weight: bold;" colspan="<?php echo $numCols; ?>">
                            <label for="categories"><?php _e('Presets categories'); ?></label><br />
                                <a style="font-size: x-small; color: gray;" href="#" onclick="checkAll('frm3', true); return false;"><?php _e('Check all'); ?></a> - <a style="font-size: x-small; color: gray;" href="#" onclick="checkAll('frm3', false); return false;"><?php _e('Uncheck all'); ?></a>
                        </td>
                        <?php for ($j = 0 ; $j < $numCols ; $j++) {?>
                        <td>
                            <?php for ($i = $catsPerCol*$j ; $i < $catsPerCol*($j+1) ; $i++) {?>
                                <?php if (is_array($categories[$i])) {?>
                                <br /><input type="checkbox" name="categories[]" value="<?php echo $categories[$i]['pk_i_id']; ?>" style="float:left;" onclick="javascript:checkCat('<?php echo $categories[$i]['pk_i_id'];?>', this.checked);"><span style="font-size:25px"><?php echo $categories[$i]['s_name']; ?></span></input><br />
                                <div id="cat<?php echo $categories[$i]['pk_i_id'];?>">
                                    <?php foreach($categories[$i]['categories'] as $sc) { ?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="categories[]" value="<?php echo $sc['pk_i_id']; ?>"><?php echo $sc['s_name']; ?></input><br />
                                    <?php } ?>
                                </div>
                                <?php } ?>
                            <?php } ?>
                        </td>
                        <?php }  ?>
                    </tr>
                </table>
                <p>
                    <input class="Button" type="button" onclick="window.history.go(-1);" value="<?php echo __('Cancel'); ?>" />
                    <input class="Button" type="submit" value="<?php echo __('Save'); ?>" />
                </p>
            </form>
<?php
        break;
        case 'stepone':
            //Assings the given categories (and its children) the plugins attributes
            $conn->osc_dbExec("DELETE FROM %st_plugin_category WHERE s_plugin_name = 'realstate_plugin'", DB_TABLE_PREFIX);
            if(isset($_POST['categories'])) {
                realstate_insertRecursive($_POST['categories']);
            }
?>
            <br/><?php _e('This plugin is now configured.'); ?>
            <br/>&nbsp;&nbsp;
            <a title="Log Out" href="plugins.php">
                <input class="Button" type="submit" value="<?php _e('Return to plugins\'s page'); ?>" />
            </a>
            <br /><br />
<?php
            break;
    } 
?>
