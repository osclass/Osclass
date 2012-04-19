<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript">
            function submitForm(frm, type) {
                frm.action.value = 'backup-' + type ;
                frm.submit() ;
            }
        </script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="tools"><?php _e('Backup') ; ?></h1>
                </div>
                <div class="FlashMessage error">
                    <a class="close" href="#">×</a>
                    <p>
                        <?php echo sprintf(__('Warning: Backup process could take up some time and space. Please, be aware that the process could fail, <a href="%s">know the reasons</a>.'), "http://doc.osclass.org/Reasons_why_the_backup_could_fail") ; ?>
                    </p>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- tools backup -->
                <div class="tools backup">
                    <p class="text">
                        <?php _e("<strong>WARNING</strong>: If you don't specify a backup folder, the backup files will be created in the root of your OSClass installation.") ; ?>
                    </p>
                    <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post" id="bckform" name="bckform" >
                        <input type="hidden" name="page" value="tools" />
                        <input type="hidden" name="action" value="" />
                        <fieldset>
                            <p class="text">
                                <?php _e('Backup folder') ; ?>
                                <input type="text" class="xxlarge" id="backup_dir" name="bck_dir" value="<?php echo osc_base_path() ; ?>" />
                            </p>
                            <p class="text">
                                <?php _e("This is the folder in which your backups will be created. We recommend that you choose a non-public path. For more information, please refer to OSClass' documentation.") ; ?>
                            </p>
                            <h3><?php _e('Back up database (.sql)') ; ?></h3>
                            <p class="text">
                                <input type="button" id="backup_sql" onclick="javascript:submitForm(this.form, 'sql');" value="<?php echo osc_esc_html( __('Backup (store on server)') ) ; ?>" >
                                <input type="button" id="backup_sql_file" onclick="javascript:submitForm(this.form, 'sql_file');" value="<?php echo osc_esc_html( __('Backup (download file)') ) ; ?>" >
                            </p>
                            <h3><?php _e('Back up OSClass installation') ; ?></h3>
                            <p class="text">
                                <input type="button" id="backup_zip" onclick="javascript:submitForm(this.form, 'zip');" value="<?php echo osc_esc_html( __('Backup (store on server)') ) ; ?>" >
                                <!--<input type="button" onclick="javascript:submitForm(this.form, 'zip_file');" value="<?php echo osc_esc_html( __('Backup (download file)') ) ; ?>" >-->
                            </p>
                        </fieldset>
                    </form>
                </div>
                <!-- /tools backup -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>