<?php
function agd_geocode($address_number, $street, $place, $postcode, $mapbox_token) {
	$response = file_get_contents("https://api.mapbox.com/search/geocode/v6/forward?&address_number=".urlencode($address_number)."&street=".urlencode($street)."&postcode=".urlencode($postcode)."&place=".urlencode($place)."&country=IT&proximity=ip&limit=1&access_token=".$mapbox_token);
	return json_decode($response, true)["features"][0]["geometry"]["coordinates"];
}
