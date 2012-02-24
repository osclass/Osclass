<?php

require_once dirname(__FILE__) . '/..'.'/oc-load.php';

if(PHP_SAPI == 'cli') {

    $workToDo = LocationsTmp::newInstance()->count() ;

    if( $workToDo > 0 ) {
        // there are wotk to do
        $aLocations = LocationsTmp::newInstance()->getLocations(1000) ;
        foreach($aLocations as $location) {
            $id     = $location['id_location'];
            $type   = $location['e_type'];
            $data   = 0; 
            // update locations stats
            switch ( $type ) {
                case 'COUNTRY':
                    $numItems = CountryStats::newInstance()->calculateNumItems( $id ) ;
                    $data = CountryStats::newInstance()->setNumItems($id, $numItems) ;
                    unset($numItems) ;
                break;
                case 'REGION' :
                    $numItems = RegionStats::newInstance()->calculateNumItems( $id ) ;
                    $data = RegionStats::newInstance()->setNumItems($id, $numItems) ;
                    unset($numItems) ;
                break;
                case 'CITY' :
                    $numItems = CityStats::newInstance()->calculateNumItems( $id ) ;
                    $data = CityStats::newInstance()->setNumItems($id, $numItems) ;
                    unset($numItems) ;
                break;
                default:
                break;
            }
            if($data >= 0) {
                LocationsTmp::newInstance()->delete(array('e_type' => $location['e_type'], 'id_location' => $location['id_location']) ) ;
            }
        }
        $file = __FILE__;
        exec("php $file >/dev/null &");
    }
}

?>
