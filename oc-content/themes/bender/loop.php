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
                $i++;
            }
        } else {
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
          }
        }
    ?>
</ul>