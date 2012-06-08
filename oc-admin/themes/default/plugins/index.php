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

    function customPageHeader(){ ?>
        <h1><?php _e('Manage Plugins') ; ?>
            <a href="#" class="btn ico ico-32 ico-engine float-right"></a>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
            <a href="<?php echo osc_admin_base_url(true); ?>?page=pages&amp;action=add" class="btn btn-green ico ico-32 ico-add-white float-right"><?php _e('Create page') ; ?></a>
	</h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');
    //customize Head
    function customHead() { ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('input:hidden[name="installed"]').each(function() {
                    $(this).parent().parent().children().css('background', 'none') ;
                    if( $(this).val() == '1' ) {
                        if( $(this).attr("enabled") == 1 ) {
                            $(this).parent().parent().css('background-color', '#EDFFDF') ;
                        } else {
                            $(this).parent().parent().css('background-color', '#FFFFDF') ;
                        }
                    } else {
                        $(this).parent().parent().css('background-color', '#FFF0DF') ;
                    }
                }) ;
            });
            
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead');
   
    $iDisplayLength = __get('iDisplayLength');
    $aData          = __get('aPlugins'); 
?>
<?php osc_current_admin_theme_path( 'parts/header.php' ) ; ?>

<div id="help-box">
    <a href="#" class="btn ico ico-20 ico-close">x</a>
    <h3>What does a red highlight mean?</h3>
    <p>This is where I would provide help to the user on how everything in my admin panel works. Formatted HTML works fine in here too.
    Red highlight means that the listing has been marked as spam.</p>
</div>
        
<table class="table" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?php _e('Name') ; ?></th>
            <th colspan=""><?php _e('Description') ; ?></th>
            <th> &nbsp; </th>
            <th> &nbsp; </th>
            <th> &nbsp; </th>
        </tr>
    </thead>
    <tbody>
    <?php if(count($aData['aaData'])>0) : ?>
    <?php foreach( $aData['aaData'] as $array) : ?>
        <tr>
        <?php foreach($array as $key => $value) : ?>
            <td>
            <?php echo $value; ?>
            </td>
        <?php endforeach; ?>
        </tr>
    <?php endforeach;?>
    <?php else : ?>
    <tr>
        <td colspan="4" style="text-align: center;">
        <p><?php _e('No data available in table') ; ?></p>
        </td>
    </tr>
    <?php endif; ?>
    </tbody>
</table>
        
<div class="has-pagination">
<?php     
    $pageActual = Params::getParam('iPage') ;
    $urlActual = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];
    $urlActual = preg_replace('/&iPage=(\d)+/', '', $urlActual) ;
    $pageTotal = ceil($aData['iTotalDisplayRecords']/$aData['iDisplayLength']);
    
    $params = array('total'    => $pageTotal
                   ,'selected' => $pageActual-1
                   ,'url'      => $urlActual.'&iPage={PAGE}'
                   ,'sides'    => 5
        );
    $pagination = new Pagination($params);
    $aux = $pagination->doPagination();
    
    echo $aux;
?>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>