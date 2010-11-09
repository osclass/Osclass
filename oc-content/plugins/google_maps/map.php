<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=<?php echo $key; ?>" type="text/javascript"></script>
<div id="itemMap" style="width: 500px; height: 300px"></div>
<script type="text/javascript">
if(GBrowserIsCompatible()) {
    var map = new GMap2(document.getElementById("itemMap"));
    var geocoder = new GClientGeocoder();

    function showAddress(address, region) {
        geocoder.getLatLng(
            address,
            function(point) {
            if (!point) {
                var geocoderRegion = new GClientGeocoder();
                geocoderRegion.getLatLng(
                    region,
                    function(point) {
                        if (!point) {
                            alert(region + " not found");
                        } else {
                            map.setCenter(point, 13);
                            var marker = new GMarker(point);
                            map.addOverlay(marker);
                            //marker.openInfoWindowHtml(region);
                        }
                    }
                );
                } else {
                    map.setCenter(point, 13);
                    var marker = new GMarker(point);
                    map.addOverlay(marker);
                    marker.openInfoWindowHtml(address);
                }
            }
        );
    }
}
<?php 
    $address = sprintf('%s, %s %s, %s', $item['s_address'], $item['s_region'], $item['s_city'], $item['s_country']);
    echo 'showAddress(\''.$address.'\', \''.$item['s_region'].', '.$item['s_country'].'\');';
?>
</script>

