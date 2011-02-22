<?php

////////////////////////////////////////////////////////////////
// FUNCTIONS THAT RETURNS OBJECT FROM THE STATIC CLASS (VIEW) //
////////////////////////////////////////////////////////////////

function osc_pages_title() {
    if( View::newInstance()->_exists('page') ){
        $page = View::newInstance()->_get('page');
        return $page['s_title'] ;
    }
    return '' ;
}

function osc_pages_text() {
    if( View::newInstance()->_exists('page') ){
        $page = View::newInstance()->_get('page');
        return $page['s_text'] ;
    }
    return '' ;
}

?>