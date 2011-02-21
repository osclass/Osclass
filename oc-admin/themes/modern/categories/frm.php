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

    $categories = $this->_get("categories");
    $category = $this->_get("category");
    $languages = $this->_get("languages");

    if(isset($category['pk_i_id'])) {
        //editing...
        $edit = true ;
        $title = "Edit category" ;
        $action_frm = "edit_post" ;
    } else {
        //adding...
        $edit = false ;
        $title = "Add a category" ;
        $action_frm = "add_post" ;
    }
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <div class="Header"><?php _e('Categories'); ?></div>

        <div id="content">
            
            <div id="separator"></div>

            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>

            <div id="right_column">
                <div id="content_header" class="content_header">
                        <div style="float: left;"><img src="<?php echo osc_current_admin_theme_url() ; ?>images/cat-icon.png" /></div>
                        <div id="content_header_arrow">&raquo; <?php _e($title); ?></div>
                        <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>
                
                <?php osc_show_flash_message() ; ?>

                <div id="settings_form">
			
			        <form action="<?php echo osc_admin_base_url(true); ?>?page=categories" method="post">
				        <input type="hidden" name="action" value="<?php echo $action_frm; ?>" />
				        <?php if ($edit) {
                            CategoryForm::primary_input_hidden($category) ;
                        } ?>
				
				        <div class="FormElement">
				            <div class="FormElementName"><?php _e('Parent category'); ?></div>
					        <div class="FormElementInput">
					           <?php CategoryForm::parent_category_select($categories, $category, __("- No parent -"), "fk_i_parent_id") ; ?>
					        </div>
				        </div>
				        <div class="FormElement">
				            <div class="FormElementName"><?php _e('Expirations days'); ?> <?php _e('(0 = no expiration)'); ?></div>
					        <div class="FormElementInput">
					           <?php CategoryForm::expiration_days_input_text($category); ?>
					        </div>
				        </div>
				        <div class="FormElement">
				            <div class="FormElementName"><?php _e('Position (order in relation to other categories)'); ?></div>
					        <div class="FormElementInput">
					           <?php CategoryForm::position_input_text($category); ?>
                            </div>
				        </div>
				
				        <div class="FormElement">
				            <div class="FormElementName"></div>
					        <div class="FormElementInput">
					           <?php CategoryForm::enabled_input_checkbox($category); ?>&nbsp;<?php _e('Enabled'); ?>
					        </div>
				        </div>
				
				        <div class="clear20"></div>
				
				        <?php 
				            $locales = Locale::newInstance()->listAllEnabled();
                            CategoryForm::multilanguage_name_description($locales, $category) ; 
                        ?>
                        
                        <div class="FormElement">
					        <div class="FormElementName"></div>
					        <div class="FormElementInput">
						        <button class="formButton" type="button" onclick="window.location='<?php echo osc_admin_base_url(true); ?>?page=categories';" ><?php _e('Cancel'); ?></button>
						        <button class="formButton" type="submit"><?php _e('Save'); ?></button>
					        </div>
				        </div>
				
			        </form>
		        </div>       
	        </div>
            <div style="clear: both;"></div>
        </div> <!-- end of container -->
        <?php osc_current_admin_theme_url('footer.php') ; ?>
    </body>
</html>				
