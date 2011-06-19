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
    this._idTable        = 'default' ;
    this._iDisplayLength = 0 ;
    this._iColumns       = 0 ;
    this._aoColumns      = new Array() ;
    this._sAjaxSource    = '' ;
    this._aoData         = new Array() ;
    this.loaded          = false;
    this._iTotalRecords         = -1;
    this._iTotalDisplayRecords  = -1;
    
    // funciones
    this.fnInit    = fnInit;
    this._fnHeader  = _fnHeader;
    this._fnBody    = _fnBody;
    this.fnDisplayTable  = fnDisplayTable;
}

function fnInit(json) {
    
    this._idTable        = json.idTable;
    this._iDisplayLength = json.iDisplayLength;
    this._iColumns       = json.iColumns;
    this._aoColumns      = json.aoColumns;
    this._sAjaxSource    = json.sAjaxSource;
    url  = this._sAjaxSource + "&iDisplayStart=1&iDisplayLength=10&iSortCol_0=0&sSortDir_0=desc&sEcho=2";

    var data,tr,tdr,l;
    /* Ensure that the json data is fully loaded sync*/
    var json_ = $.ajax({
        async: false,   // bloquea el navegador
        url: url,
        dataType: "json",
        success:  function(json_) {
            alert("LOADED");
        }
    }).responseText;//json

    json_ = eval( '(' + json_ + ')') ;

    this._aoData = json_.aaData;
    this._iTotalRecords = json_.iTotalRecords;
    this._iTotalDisplayRecords  = json_.iTotalDisplayRecords;
    this.loaded    = true;
    
    this.fnDisplayTable();
    
}

function fnDisplayTable()
{
    // show header
    console.log("IN "+this._aoColumns+"fnDisplayTable"+this._idTable);
    this._fnHeader() ;
    this._fnBody() ;
//    this._fnFooter() ;
}

function _fnHeader(){
    var _thead = $('<thead></thead>');
    for(var i=0; i < this._aoColumns.length; i++) {
        _thead.append( $('<th></th>').html(this._aoColumns[i].sTitle) );
    }
    $('#'+this._idTable).append(_thead);
}

function _fnBody(){
    var _tbody = $('<tbody></tbody>');
    alert(this._aoData.length + "  --  " + this._iColumns ) ;
    for(var i=0; i < this._aoData.length; i++){
        _row = $('<tr></tr>');
        for(var j = 0; j < this._aoData[i].length; j++){
            _row.append( $('<td></td>').html( this._aoData[i][j] ) );
        }
        _tbody.append(_row) ;
    }
    $('#'+this._idTable).append(_tbody);
}
//
//osc_datatable.prototype._fnFooter = function() {
//    var _tfooter = $('<div></div>');
//    _tfooter.addClass('bottom');
//    $('#'+_idTable).after(_tfooter);
//}
//

//
//
//function osc_datatable( json )
//{
//    this._idTable        = '' ;
//    this._iDisplayLength = 0 ;
//    this._iColumns       = 0 ;
//    this._aoColumns      = new Array() ;
//    this._sAjaxSource    = '' ;
//    this._aoData         = new Array() ;
//    this.loaded          = false;
//
//
//    // pasear json
//    this._idTable        = json.idTable;
//    this._iDisplayLength = json.iDisplayLength;
//    this._iColumns       = json.iColumns;
//    this._aoColumns      = json.aoColumns;
//    this._sAjaxSource    = json.sAjaxSource;
//
//    this._iTotalRecords         = 0;
//    this._iTotalDisplayRecords  = 0;
//
//    // private functions
//    this._fnHeader  = _fnHeader;
//    osc_datatable.prototype._fnBody    = _fnBody;
//    osc_datatable.prototype._fnFooter  = _fnFooter;
//
//
//    osc_datatable.prototype.fnDisplayTable = fnDisplayTable;
//
//    // get data from ajax
////    this._sAjaxSource += "&iDisplayStart=1&iDisplayLength="+this._iDisplayLength+"&iSortCol_0=0&sSortDir_0=desc&sEcho=2";
//    this._sAjaxSource += "&iDisplayStart=1&iDisplayLength=2&iSortCol_0=0&sSortDir_0=desc&sEcho=2";
//    $.getJSON(this._sAjaxSource , function(json){
//        this._aoData                = json.aaData;
//        this._iTotalRecords         = json.iTotalRecords;
//        this._iTotalDisplayRecords  = json.iTotalDisplayRecords;
//        this.loaded = true;
//
//    });
//
//    setTimeout( function(){ }, 200 );
//
//    if( this.loaded ) { alert("FOO"); }
//    else {alert("NO");}
//    fnDisplayTable();
////    $.get(this._sAjaxSource,
////        function(data){
////            var json = eval('(' + data + ')');
////            $('#'+this._idTable).append(var_dump(json));
////
////            this._aoData                = json.aaData ;
////            this._iTotalRecords         = json.iTotalRecords;
////            this._iTotalDisplayRecords  = json.iTotalDisplayRecords;
////            this.loaded = true;
////    });
//
//
//}
//
//
//
//// public functions
//function fnDisplayTable()
//{
//    /* Ensure that the table data is fully initialised */
//
//
//    // show header
//    this._fnHeader() ;
//    this._fnBody() ;
//    this._fnFooter() ;
//}
//
//function _fnHeader()
//{
//    alert(this._aoColumns) ;
//    var _thead = $('<thead></thead>');
//    for(var i=0; i < this._aoColumns.length; i++) {
//        _thead.append( $('<th></th>').html(this._aoColumns[i].sTitle) );
//    }
//    $('#'+this._idTable).append(_thead);
//}
//
//function _fnBody()
//{
//
//    var _tbody = $('<tbody></tbody>');
//    alert(this._iTotalDisplayRecords + "  --  " + this._iColumns ) ;
//    alert(this._aoData);
//    for(var i=0; i < this._iTotalDisplayRecords; i++){
//        _row = $('<tr></tr>');
//        for(var j = 0; j < this._iColumns; j++){
//            _row.append( $('<td></td>').html( this._aoData[i][j] ) );
//        }
//        _tbody.append(_row) ;
//    }
//    $('#'+this._idTable).append(_tbody);
//}
//function _fnFooter(){
//    var _tfooter = $('<div></div>');
//    _tfooter.addClass('bottom');
//    $('#'+this._idTable).after(_tfooter);
//}


//    this.text = "";
//    for(var i=0; i < this._aoColumns.length; i++) {
//        this.text += this._aoColumns[i].sTitle + "\n";
//    }
//    alert(this.text);