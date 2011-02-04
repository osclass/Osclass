<?php

    function osc_all_enabled_locales() {
        $locales = Locale::newInstance()->listAllEnabled(false, true)
    }

?>
