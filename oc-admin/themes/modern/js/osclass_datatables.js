    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
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
     */

var osc_datatable = function() {

    // private attr
    this._idTable        = 'default' ;
    this._sAjaxSource    = '' ;
    this._iDisplayLength = 5 ;
    this._iColumns       = 0 ;
    this._aoColumns      = new Array() ;
    this._aoData         = new Array() ;
    this._iTotalRecords         = -1;
    this._iTotalDisplayRecords  = -1;
    this._iDisplayStart         = 0;
    this._iSortableCol          = 0;
    this._sSortDir              = 'desc';

    // filters
    this._fUserId           = undefined;
    this._fCountryId        = undefined;
    this._fRegionId         = undefined;
    this._fCityId           = undefined;
    this._fCatId            = undefined;
    this._b_premium         = undefined;
    this._b_active          = undefined;
    this._b_enabled         = undefined;
    this._b_spam            = undefined;
   
    
    // array of class
    this.oClasses = new Array();
    this.oClasses.sPageButton   = "paginate_button";
    this.oClasses.sPageFirst    = "first";
    this.oClasses.sPagePrevious = "previous";
    this.oClasses.sPageNext     = "next";
    this.oClasses.sPageLast     = "last";
    this.oClasses.sPageButtonActive = "paginate_active";

    // public methods
    this.fnInit         = fnInit;
    this.fnDisplayTable = fnDisplayTable;
    // private methods
    this._fnHeader      = _fnHeader;

    this._fnBody        = _fnBody;
    this._fnReBody      = _fnReBody;
    
    this._fnFooter      = _fnFooter;
    this._fnReFooter    = _fnReFooter;

    this._paginate      = _paginate;
    this._fnUpdatePagination = _fnUpdatePagination;

    this._fnUpdateData  = _fnUpdateData;
    this._fnUpdateSort  = _fnUpdateSort;
    this._fnInitData    = _fnInitData;
    this._fnPageChange  = _fnPageChange;
    this._fnReDraw      = _fnReDraw;
    this._fnRecordsDisplay = _fnRecordsDisplay;

    this.applyFilters   = applyFilters;

    this.fnGetFilteredNodes = fnGetFilteredNodes;
}

/*
 * Initializing attributes from json
 */
function fnInit(json) {
    
    this._idTable        = json.idTable;
    this._iDisplayLength = json.iDisplayLength;
    this._iColumns       = json.iColumns;
    this._aoColumns      = json.aoColumns;
    this._sAjaxSource    = json.sAjaxSource;
    this._oLanguage      = json.oLanguage;

    if( IsNumeric(json.iDisplayStart)){
        this._iDisplayStart = parseInt( json.iDisplayStart );
    }

    // load data from sAjaxSource
    _this = this;
    this._fnInitData();
    $('#select_range').change(function(){
        _this._iDisplayLength = $(this).val();
        _this._fnReDraw();
    });
    // show table
    this.fnDisplayTable();
}

/*
 * Create the table structure header/body/footer.
 */
function fnDisplayTable()
{
    this._fnHeader() ;
    this._fnBody() ;
    this._fnFooter() ;
    // add list pages
    this._fnUpdatePagination();
    // add extra data
    // add label processing
    $('#'+this._idTable).before( $('<div id="' +this._idTable+ '_processing" class="dataTables_processing" style="display:none;">Processing...</div>') );
}

/*
 * Append to table thead element.
 */
function _fnHeader(){
    var n_cols = this._aoColumns.length;
    var style_first = "border-left: 1px solid rgb(170, 170, 170); -moz-border-radius-topleft: 4px;";
    var style_last  = "border-right: 1px solid rgb(170, 170, 170); -moz-border-radius-topright: 4px;";

    var _thead = $('<thead></thead>');
    
    for(var i=0; i < n_cols; i++) {
        var style = "";
        if(i == 0) {
            style = style_first ;
        } else if (i == n_cols-1 ){
            style = style_last ;
        }
        
        if( this._aoColumns[i].sWidth != '') {
            style += "width: " + (this._aoColumns[i].sWidth) + ";" ;
        }

        var _th = $('<th></th>').html(this._aoColumns[i].sTitle);
        // añadir clases a th  sorting_disabled / sorting / sorting_asc / sorting_desc
        if(this._aoColumns[i].bSortable == true){
            _th.addClass('sorting');

            _this = this;

            var colSort = i ;
            _th.bind( 'click', {msg: colSort}, function (event) {
                _this._fnUpdateSort(event.data.msg);
                
                if( $(this).hasClass('sorting') ){
                    $(this).removeClass('sorting');
                    _this._sSortDir = 'asc';
                } else if( $(this).hasClass('sorting_desc') ){
                    $(this).removeClass('sorting_desc');
                    _this._sSortDir = 'asc';
                } else if ( $(this).hasClass('sorting_asc') ) {
                    $(this).removeClass('sorting_asc');
                    _this._sSortDir = 'desc';
                }

                $('.sorting_asc').each(function(){$(this).removeClass('sorting_asc');$(this).addClass('sorting');});
                $('.sorting_desc').each(function(){$(this).removeClass('sorting_desc');$(this).addClass('sorting');});
                $(this).addClass('sorting_'+_this._sSortDir);
                _this._fnReDraw();
            } );
            
        }else{
            _th.addClass('sorting_disabled');
        }
        
        // adding style
        if(style != '') {
            _th.attr( 'style', style ) ;
        }
        // add th into thead
        _thead.append( _th );
    }
    $('#'+this._idTable).append(_thead);
}

function _fnUpdateSort(value){
    this._iDisplayStart = 0;
    this._iSortableCol  = value;
}
/*
 * Append to table, tbody element.
 */
function _fnBody(){
    var _tbody = $('<tbody></tbody>');

    for(var i=0; i < this._aoData.length; i++){
        _row = $('<tr></tr>');
        if(i%2) {_row.addClass('even');}
        else {_row.addClass('odd');}
        for(var j = 0; j < this._aoData[i].length; j++){
            _row.append( $('<td></td>').html( this._aoData[i][j] ) );
        }
        _tbody.append(_row) ;
    }
    $('#'+this._idTable).append(_tbody);
}

/*
 * Append to table footer element.
 */
function _fnFooter() {
    var _tfooter = $('<div></div>');
    _tfooter.addClass('bottom');

    var _div_info = $('<div></div>');
    _div_info.addClass('dataTables_info');
    _div_info.attr('id', this._idTable+'_info');
    
    var str = this._oLanguage.sInfo;
    // replace _START_, _END_, _TOTAL_
    str = str.replace("_START_", this._iDisplayStart+1 );
    str = str.replace("_END_",   this._iDisplayStart + this._aoData.length );

    // sInfoFiltered
    var str1 = "";
    if( this._iTotalRecords != this._iTotalDisplayRecords ) {
        str1 += this._oLanguage.sInfoFiltered;
        str1 = str1.replace("_MAX_", this._iTotalRecords );
        str = str.replace("_TOTAL_", this._iTotalDisplayRecords );
    } else {
        str = str.replace("_TOTAL_", this._iTotalRecords );
    }
    _div_info.html(str+" "+str1);
    
    _tfooter.append(_div_info);

    // create pagination
    var _node_pag = $('<div></div>');
    _node_pag.attr('id', this._idTable+'_paginate');
    this._paginate('full', _node_pag);
    _tfooter.append(_node_pag);

    var _div_clear = $('<div></div>');
    _div_clear.attr('style', 'clear:both;');
    _tfooter.append(_div_clear);

    $('#'+this._idTable).after(_tfooter);
    
}

/*
 * fill node_pad with pagination links and bind on click event
 */
function _paginate(type, node_pag) {

    if(type == 'full') {
        var nFirst      = $('<span></span>') ;
        var nPrevious   = $('<span></span>') ;
        var nList       = $('<span></span>') ;
        var nNext       = $('<span></span>') ;
        var nLast       = $('<span></span>') ;


        nFirst.html( this._oLanguage.oPaginate.sFirst ) ;
        nPrevious.html( this._oLanguage.oPaginate.sPrevious ) ;
        nNext.html( this._oLanguage.oPaginate.sNext) ;
        nLast.html( this._oLanguage.oPaginate.sLast) ;

        nFirst.addClass( this.oClasses.sPageButton+" "+this.oClasses.sPageFirst );
        nPrevious.addClass( this.oClasses.sPageButton+" "+this.oClasses.sPagePrevious ) ;
        nList.addClass( "list_pages" );
        nNext.addClass( this.oClasses.sPageButton+" "+this.oClasses.sPageNext ) ;
        nLast.addClass( this.oClasses.sPageButton+" "+this.oClasses.sPageLast ) ;

        node_pag.append(nFirst);
        node_pag.append(nPrevious);
        node_pag.append(nList);
        node_pag.append(nNext);
        node_pag.append(nLast);

        _this = this;

        nFirst.bind( 'click', function () {
            if ( _this._fnPageChange( "first" ) )
            {
                _this._fnReDraw();
            }
        } );

        nPrevious.bind( 'click', function() {
            if ( _this._fnPageChange( "previous" ) )
            {
                _this._fnReDraw();
            }
        } );

        nNext.bind( 'click', function() {

            if ( _this._fnPageChange( "next" ) )
            {
                _this._fnReDraw();
            }
        } );

        nLast.bind( 'click', function() {
            if ( _this._fnPageChange( "last" ) )
            {
                _this._fnReDraw();
            }
        } );

        node_pag.addClass( 'paging_full_numbers dataTables_paginate');
    }

}

/*
 * Update the body and footer content.
 */
function _fnReDraw(){

    // show processing
    $('#'+this._idTable+"_processing").show();
    // get data from sAjaxsource
    this._fnUpdateData();
    // redraw body and footer
    this._fnReBody();
    this._fnReFooter();
    this._fnUpdatePagination();
    $('#'+this._idTable+"_processing").hide();
}

/**
 * redraw body with new data.
 */
function _fnReBody(){
    // remove all rows form tbody

    $('#'+this._idTable+' > tbody > tr').each(function(index, value){
        $(this).remove();
    });
    var _tbody = $('#'+this._idTable+" > tbody");

    for(var i=0; i < this._aoData.length; i++){
        _row = $('<tr></tr>');
        if(i%2) {_row.addClass('even');}
        else {_row.addClass('odd');}
        for(var j = 0; j < this._aoData[i].length; j++){
            _row.append( $('<td></td>').html( this._aoData[i][j] ) );
        }
        _tbody.append(_row) ;
    }
}

/**
 * redraw footer with new data.
 */
function _fnReFooter() {

    var _div_info = $('div#'+this._idTable+'_info'); //Showing 1 to 10 of 13 entries

    var str = this._oLanguage.sInfo;
    // replace _START_, _END_, _TOTAL_
    str = str.replace("_START_", this._iDisplayStart+1 );
    str = str.replace("_END_",   this._iDisplayStart + this._aoData.length );
    
    // sInfoFiltered
    var str1 = "";
    if( this._iTotalRecords != this._iTotalDisplayRecords ) {
        str1 += this._oLanguage.sInfoFiltered;
        str1 = str1.replace("_MAX_", this._iTotalRecords );
        str = str.replace("_TOTAL_", this._iTotalDisplayRecords );
    } else {
        str = str.replace("_TOTAL_", this._iTotalRecords );
    }
    _div_info.html(str+" "+str1);

    // create pagination
    var _node_pag = $('div#'+this._idTable+'_paginate').html('');
    this._paginate('full', _node_pag);

}

function _fnInitData(){
     // get data
    url  = this._sAjaxSource + "&iDisplayStart=0&iDisplayLength=" + this._iDisplayLength ;//+ "&iSortCol_0=0&sSortDir_0=desc&sEcho=2";
    // add data for sorting
    for(var i = 0; i < this._aoColumns.length; i++){
        if(this._aoColumns[i]['bSortable'] == true){
            url += "&bSortable_"+i+"=true";
        } else {
            url += "&bSortable_"+i+"=false";
        }

        if(this._aoColumns[i]['defaultSortable'] == true){
            url += "&iSortCol_0=0";
            url += "&sSortDir_0="+this._sSortDir;
        }
    }
    
    /* Ensure that the json data is fully loaded sync*/
    var json = $.ajax({
        async: false,   // bloquea el navegador
        url: url,
        dataType: "json",
        success:  function(json) {

        }
    }).responseText;//json
    
    json = eval( '(' + json + ')') ;
    this._aoData        = json.aaData;
    this._iTotalRecords = json.iTotalRecords;
    this._iTotalDisplayRecords  = json.iTotalDisplayRecords;
}
/*
 * Get data from sAjaxSource
 *  _aoData
 *  _iTotalRecords
 *  _iTotalDisplayRecords
 */
function _fnUpdateData(){

    // get data
    url  = this._sAjaxSource + "&iDisplayStart=" + this._iDisplayStart + "&iDisplayLength=" + this._iDisplayLength ;
    // add data for sorting
    for(var i = 0; i < this._aoColumns.length; i++){
        if(this._aoColumns[i]['bSortable'] == true){
            url += "&bSortable_"+i+"=true";
        } else {
            url += "&bSortable_"+i+"=false";
        }
    }
    url += "&iSortCol_0="+this._iSortableCol;
    url += "&sSortDir_0="+this._sSortDir;
    var count = 0;
    // filters
    if(this._fUserId != undefined)         url += "&fCol_userIdValue="+this._fUserId;  
    if(this._fCountryId != undefined )     url += "&fCol_countryId="+this._fCountryId; 
    if(this._fRegionId != undefined )      url += "&fCol_regionId="+this._fRegionId; 
    if(this._fCityId != undefined )        url += "&fCol_cityId="+this._fCityId;  
    if(this._fCatId  != undefined )        url += "&fCol_catId="+this._fCatId;  
    // filters item table
    if(this._b_premium  != undefined )     url += "&fCol_bPremium="+this._b_premium;  
    if(this._b_active  != undefined )      url += "&fCol_bActive="+this._b_active;  
    if(this._b_enabled  != undefined )     url += "&fCol_bEnabled="+this._b_enabled;  
    if(this._b_spam  != undefined )        url += "&fCol_bSpam="+this._b_spam;  
    // filters item stat table
//    if(this._i_num_bad_classified != undefined ) url += "&stat=bad";
//    if(this._i_num_spam != undefined )           url += "&stat=spam";
//    if(this._i_num_repeated != undefined )       url += "&stat=duplicated";
//    if(this._i_num_offensive != undefined )      url += "&stat=offensive";
//    if(this._i_num_expired != undefined )        url += "&stat=expired";

    if(this._i_num_bad_classified != undefined ) url += "&bad=bad";
    if(this._i_num_spam != undefined )           url += "&spam=spam";
    if(this._i_num_repeated != undefined )       url += "&duplicated=duplicated";
    if(this._i_num_offensive != undefined )      url += "&offensive=offensive";
    if(this._i_num_expired != undefined )        url += "&expired=expired";
    // search
    if(this._sSearch != undefined)          url += "&sSearch="+this._sSearch;
    // url += ....
    
    /* Ensure that the json data is fully loaded sync*/
    var json = $.ajax({
        async: false,   // bloquea el navegador
        url: url,
        dataType: "json",
        success:  function(json) {

        }
    }).responseText;//json
    json = eval( '(' + json + ')') ;
    this._aoData        = json.aaData;
    this._iTotalRecords = json.iTotalRecords;
    this._iTotalDisplayRecords  = json.iTotalDisplayRecords;
}

/*
* Function: _fnPageChange
* Purpose: Alter the display settings to change the page
* Returns: bool:true - page has changed, false - no change (no effect) eg 'first' on page 1
* Inputs: object:oSettings - dataTables settings object
* string:sAction - paging action to take: "first", "previous", "next" or "last"
*/
function _fnPageChange ( sAction )
{
    var iOldStart = this._iDisplayStart;

    if ( sAction == "first" )
    {
        this._iDisplayStart = 0;
    }
    else if ( sAction == "previous" )
    {
        this._iDisplayStart = this._iDisplayLength>=0 ? this._iDisplayStart - this._iDisplayLength : 0;

        /* Correct for underrun */
        if ( this._iDisplayStart < 0 ){
            this._iDisplayStart = 0;
        }
    } else if ( sAction == "next" ){
        if ( this._iDisplayLength >= 0 ) {
            /* Make sure we are not over running the display array */
            if ( parseInt(this._iDisplayStart) + parseInt(this._iDisplayLength) < this._fnRecordsDisplay() ){
                this._iDisplayStart += parseInt(this._iDisplayLength);
            }
        }else{
            this._iDisplayStart = 0;
        }
    } else if ( sAction == "last" ){
        if ( this._iDisplayLength >= 0 ){
            var iPages = parseInt( (this._fnRecordsDisplay()-1) / this._iDisplayLength, 10 ) + 1;
            this._iDisplayStart = (iPages-1) * this._iDisplayLength;
        }else{
            this._iDisplayStart = 0;
        }
    }else{
        console.log("Unknown paging action: "+sAction ) ;
    }

    return iOldStart != this._iDisplayStart;
}

function _fnUpdatePagination() {
//    alert('updatePagination');
    var iPageCount = 5 ;
    var iPageCountHalf = Math.floor(iPageCount / 2);
    var iPages = Math.ceil((this._fnRecordsDisplay()) / this._iDisplayLength);
    var iCurrentPage = Math.ceil(this._iDisplayStart / this._iDisplayLength) + 1;
    var sList = "";
    var iStartButton, iEndButton, i, iLen;
    // oClasses

    /* Pages calculation */
    if (iPages < iPageCount) {
        iStartButton = 1;
        iEndButton = iPages;
    } else {
        if (iCurrentPage <= iPageCountHalf){
            iStartButton = 1;
            iEndButton = iPageCount;
        }else{
            if (iCurrentPage >= (iPages - iPageCountHalf)){
                iStartButton = iPages - iPageCount + 1;
                iEndButton = iPages;
            }else{
                iStartButton = iCurrentPage - Math.ceil(iPageCount / 2) + 1;
                iEndButton = iStartButton + iPageCount - 1;
            }
        }
    }

    /* Build the dynamic list */
    for ( i=iStartButton ; i<=iEndButton ; i++ ){
        if ( iCurrentPage != i ){
            sList += '<span class="'+this.oClasses.sPageButton+'">'+i+'</span>';
        }else{
            sList += '<span class="'+this.oClasses.sPageButtonActive+'">'+i+'</span>';
        }
    }

    var qjPaginateList = $('span.list_pages');
    qjPaginateList.html( sList );

    _this = this;

    $('span',qjPaginateList).bind( 'click', function () {
            var num = parseInt((this.innerHTML), 10) ;
            var target = num - 1 ;
            _this._iDisplayStart = target * _this._iDisplayLength;
            _this._fnReDraw();
        });
}

/*
 * Total number of records
 */
function _fnRecordsDisplay() {
    return this._iTotalDisplayRecords;
}


// utils functions
function IsNumeric(input)
{
    return (input - 0) == input && input.length > 0;
}

String.prototype.base64_encode = base64_encode;
function base64_encode( data ) {
    // Encodes string using MIME base64 algorithm
    //
    // version: 902.2516
    // discuss at: http://phpjs.org/functions/base64_encode
    // +   original by: Tyler Akins (http://rumkin.com)
    // +   improved by: Bayron Guevara
    // +   improved by: Thunder.m
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Pellentesque Malesuada
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // -    depends on: utf8_encode
    // *     example 1: base64_encode('Kevin van Zonneveld');
    // *     returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='
    // mozilla has this native
    // - but breaks in 2.0.0.12!
    //if (typeof window['atob'] == 'function') {
    //    return atob(data);
    //}

    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = ac = 0, enc="", tmp_arr = [];

    if (!data) {
        return data;
    }

//    data = utf8_encode(data+'');

    do { // pack three octets into four hexets
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);

        bits = o1<<16 | o2<<8 | o3;

        h1 = bits>>18 & 0x3f;
        h2 = bits>>12 & 0x3f;
        h3 = bits>>6 & 0x3f;
        h4 = bits & 0x3f;

        // use hexets to index into b64, and append result to encoded string
        tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
    } while (i < data.length);

    enc = tmp_arr.join('');

    switch( data.length % 3 ){
        case 1:
            enc = enc.slice(0, -2) + '==';
        break;
        case 2:
            enc = enc.slice(0, -1) + '=';
        break;
    }

    return enc;
}


// filters
function applyFilters(){

    // get filters
    
    this._fUserId       = ( $('#userId').val() == '' ) ? undefined : $('#userId').val() ;
    
    this._fCountryId    = ( $('#countryId').val() == '' ) ? undefined : $('#countryId').val() ;

    this._fRegionId     = ( $('#regionId').val() == '' ) ? undefined : $('#regionId').val();
    this._fCityId       = ( $('#cityId').val() == '' ) ? undefined : $('#cityId').val();
    
    this._fCatId        = ( $('#catId').val() == '' ) ? undefined : $('#catId').val();
    
    this._b_premium     = ( $('#b_premium').val() == '' ) ? undefined : $('#b_premium').val();
    this._b_active      = ( $('#b_active').val() == '' ) ? undefined : $('#b_active').val();
    this._b_enabled     = ( $('#b_enabled').val() == '' ) ? undefined : $('#b_enabled').val();
    this._b_spam        = ( $('#b_spam').val() == '' ) ? undefined : $('#b_spam').val();

    this._i_num_bad_classified  = ( $('#i_num_bad_classified').val() == '' ) ? undefined : $('#i_num_bad_classified').val();
    this._i_num_spam            = ( $('#i_num_spam').val() == '' ) ? undefined : $('#i_num_spam').val();
    this._i_num_repeated        = ( $('#i_num_repeated').val() == '' ) ? undefined : $('#i_num_repeated').val();
    this._i_num_offensive       = ( $('#i_num_offensive').val() == '' ) ? undefined : $('#i_num_offensive').val();
    this._i_num_expired         = ( $('#i_num_expired').val() == '' ) ? undefined : $('#i_num_expired').val();

    var string = $('#sSearch').val();
    this._sSearch   = string.base64_encode(string);
    if(this._sSearch == "") this._sSearch = undefined;
    this._iDisplayStart = 0;

    this._fnReDraw();
}

function fnGetFilteredNodes(){
    alert("entra");
    return $('#'+this._idTable+' input');
}