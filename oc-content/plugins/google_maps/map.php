<div style="width: 100%; float:left; margin:20px 0 10px 60px"> 
    <div id="itemMap"></div>
</div>
<script type="text/javascript">
    if(GBrowserIsCompatible()) {
        var map   = new GMap2(document.getElementById("itemMap"), { size: new GSize(480,240) });
        var point = new GLatLng(<?php echo $item['d_coord_lat']; ?>, <?php echo $item['d_coord_long']; ?>);
        map.setCenter(point, 13);
        var marker = new GMarker(point);
        map.addOverlay(marker);
        marker.show();
    }
</script>