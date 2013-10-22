<?php

function test_check_open_tag($html, $tag = 'form') {

    $start_tag = 0;
    if(preg_match_all("/<".$tag."(.*?)>/is", $html, $matches_start, PREG_SET_ORDER)) {
        $start_tag = count($matches_start);
    }
    $end_tag = 0;
    if(preg_match_all("/<\\/".$tag.">/is", $html, $matches_end, PREG_SET_ORDER)) {
        $end_tag = count($matches_end);
    }

    return ($start_tag==$end_tag);

}

function test_check_all_open_tags($html) {
    $tags = array('form', 'div', 'p', 'span');
    $success = array();
    foreach($tags as $tag) {
        if(!test_check_open_tag($html, $tag)) {
            $success[] = $tag;
        }
    }
    return $success;
}


?>