<script type="text/javascript">
if(GBrowserIsCompatible()) {
    var map = new GMap2(document.getElementById("itemMap"));
    var geocoder = new GClientGeocoder();
   map.addControl(new GSmallMapControl());
    map.addControl(new GMapTypeControl());
    var title_ad_googlemap = "<?= $item['s_title'] ?>";
    var city_ad_googlemap = "<?= $item['s_city'] ?>";
   var description_ad_googlemap = title_ad_googlemap + ', ' + city_ad_googlemap;
   
   
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
                    marker.openInfoWindowHtml(description_ad_googlemap);
                }
            }
        );
    }
}
