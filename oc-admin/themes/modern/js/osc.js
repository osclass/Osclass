/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/* ===================================================
 * osc tooltip
 * ===================================================
 * Usage:
 * Display a custom tooltip on mouse over.
 * $(selector).tooltip(message, {options});
 *
 * options = {
 *     layout: ['gray-tooltip', 'black-tooltip','info-tooltip','warning-tooltip','success-tooltip','error-tooltip'],
 *     position: {
 *         x: ['left',right,'middle'],
 *         y: ['top','bottom','middle']
 *     }
 * }
 **/
osc.tooltip = function(message, options){
    defaults = {
        position:{
            y: 'middle',
            x: 'right'
        },
        layout:'black-tooltip'
    }
    var opts = $.extend({}, defaults, options);

    // check if exists tooltip
    var $tooltip = $('#osc-tooltip');
    if($tooltip.length == 0){
        $tooltip = $('<div id="osc-tooltip"></div>');
        $('body').append($tooltip);
    }

    //Add the message
    var hovered;
    $(this).hover(function(){
        hovered = true;
        var offset = $(this).offset();
        var $tooltipContainer = $('<div class="tooltip-message"></div>');
        $tooltipContainer.append(message);
        $tooltip.html($tooltipContainer).attr('class',opts.layout+' '+opts.position.x+'-'+opts.position.y).append('<div class="tooltip-arrow"></div>').show();
        switch (opts.position.y) {
            case 'top':
                positionTop = offset.top-($tooltip.outerHeight());
                break
            case 'middle':
                positionTop = offset.top-($tooltip.outerHeight()/2)+($(this).outerHeight()/2);
                break
            case 'bottom':
                positionTop = offset.top+$(this).outerHeight();
                break
        }
        switch (opts.position.x) {
            case 'left':
                positionLeft = offset.left-$tooltip.outerWidth();
                break
            case 'middle':
                positionLeft = offset.left-($tooltip.outerWidth()/2)+($(this).outerWidth()/2);
                break
            case 'right':
                positionLeft = offset.left+$(this).width();
                break
        }
        $tooltip.css({
            left: positionLeft,
            top:  positionTop
        });

    },function(){
        hovered = false;
        setTimeout(function(){
        if(!hovered) {
            $tooltip.hide();
        }}, 100);
    });

    jQuery("#osc-tooltip").mouseover(function(){
        hovered = true;
    }).mouseout(function(){
        hovered = false;
        setTimeout(function(){
        if(!hovered) {
            $tooltip.hide();
        }}, 100);
    });
};

//extend
$.fn.osc_tooltip = osc.tooltip


var OSC_ESC_MAP = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;'
};

function oscEscapeHTML(str) {
    if(str!=undefined) {
        return str.toString().replace(/[&<>'"]/g, function(c) {
            return OSC_ESC_MAP[c];
        });
    }
    return "";
}