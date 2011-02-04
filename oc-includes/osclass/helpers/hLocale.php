<?php

    function osc_all_enabled_locales($indexed_by_pk = false) {
        return ( Locale::newInstance()->listAllEnabled(false, $indexed_by_pk)) ;

    }

    function osc_all_enabled_locales_for_admin($indexed_by_pk = false) {
        return ( Locale::newInstance()->listAllEnabled(true, $indexed_by_pk)) ;
    }

?>