<?php
function agd_geocode($address_number, $street, $place, $postcode, $province, $mapbox_token) {
	$response = file_get_contents("https://api.mapbox.com/search/geocode/v6/forward?q=".urlencode(implode(" ", [$street, $address_number, $postcode, $place, "(".$province."),", "IT"]))."&limit=1&access_token=".$mapbox_token);
	return json_decode($response, true)["features"][0]["geometry"]["coordinates"];
}
