<?php
    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2014 OSCLASS
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
$loopClass = '';
$type = 'items';
if(View::newInstance()->_exists('listType')){
    $type = View::newInstance()->_get('listType');
}
if(View::newInstance()->_exists('listClass')){
    $loopClass = View::newInstance()->_get('listClass');
}
?>
<ul class="listing-card-list <?php echo $loopClass; ?>" id="listing-card-list">
    <?php
        $i = 0;

        if($type == 'latestItems'){
            while ( osc_has_latest_items() ) {
                $class = '';
                if($i%3 == 0){
                    $class = 'first';
                }
                bender_draw_item($class);
                $i++;
            }
        } elseif($type == 'premiums'){
            while ( osc_has_premiums() ) {
                $class = '';
                if($i%3 == 0){
                    $class = 'first';
                }
                bender_draw_item($class,false,true);
                $i++;
                if($i == 3){
                    break;
                }
            }
        } else {
            search_ads_listing_top_fn();
            while(osc_has_items()) {
                $i++;
                $class = false;
                if($i%4 == 0){
                    $class = 'last';
                }
                $admin = false;
                if(View::newInstance()->_exists("listAdmin")){
                    $admin = true;
                }

                bender_draw_item($class,$admin);

                if(bender_show_as()=='gallery') {
                    if($i%8 == 0){
                        osc_run_hook('search_ads_listing_medium');
                    }
                } else if(bender_show_as()=='list') {
                    if($i%6 == 0){
                        osc_run_hook('search_ads_listing_medium');
                    }
                }
          }
        }
    ?>
</ul>