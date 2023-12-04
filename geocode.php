<?php
function geocode($address, $mapbox_token) {
	$response = file_get_contents("https://api.mapbox.com/geocoding/v5/mapbox.places/".urlencode($address).".json?limit=1&types=address&access_token=".$mapbox_token);
	return json_decode($response, true)["features"][0]["center"];
}
