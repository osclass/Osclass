<?php

    function osc_latest_items() {
        return (Item::newInstance()->listLatest( osc_max_latest_items() )) ;
    }

?>