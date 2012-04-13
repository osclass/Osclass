$.fn.dataTableExt.oApi.fnGetFilteredNodes = function ( oSettings ) {
    var anRows = [];
    for ( var i=0, iLen = oSettings.aiDisplay.length ; i < iLen ; i++ ) {
        var nRow = oSettings.aoData[ oSettings.aiDisplay[i] ].nTr;
        anRows.push( nRow );
    }
    return anRows;
};

$.extend( $.fn.dataTableExt.oStdClasses, {
    "sWrapper": "dataTables_wrapper form-inline"
} );