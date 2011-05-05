<div style="width: 100%; float:left; margin:20px 0 10px 60px"> 
    <div id="itemMap"></div>
</div>
<?php if($item['d_coord_lat'] != '' && $item['d_coord_long'] != '') {?>
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
<?php } else { ?>
    <script type="text/javascript"> 
        var map = null;
        var geocoder = null;
     
        if (GBrowserIsCompatible()) {
            map = new GMap2(document.getElementById("itemMap"), { size: new GSize(480,240) });
            map.setCenter(new GLatLng(37.4419, -122.1419), 13);
            geocoder = new GClientGeocoder();
        }
     
        function showAddress(address) {
            if (geocoder) {
                geocoder.getLatLng(
                    address,
                    function(point) {
                        if (!point) {
                            //alert(address + " not found");
                        } else {
                            map.setCenter(point, 13);
                            var marker = new GMarker(point);
                            map.addOverlay(marker);
                            // As this is user-generated content, we display it as
                            // text rather than HTML to reduce XSS vulnerabilities.
                            marker.openInfoWindow(document.createTextNode(address));
                        }
                    }
                );
            }
        }
        
        <?php
            $addr = array();
            if($item['s_address']!='' && $item['s_address']!=null) { $addr[] = $item['s_address']; };
            if($item['s_city']!='' && $item['s_city']!=null) { $addr[] = $item['s_city']; };
            if($item['s_zip']!='' && $item['s_zip']!=null) { $addr[] = $item['s_zip']; };
            if($item['s_region']!='' && $item['s_region']!=null) { $addr[] = $item['s_region']; };
            if($item['s_country']!='' && $item['s_country']!=null) { $addr[] = $item['s_country']; };
            $address = implode(", ", $addr);
        ?>
        $(document).ready(function(){
            showAddress('<?php echo $address; ?>');
        });

    </script>
<?php }; ?>
