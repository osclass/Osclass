/**
 * Osclass – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2012 OSCLASS
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
 **/

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