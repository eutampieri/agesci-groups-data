<?php
function agd_geocode($address, $mapbox_token) {
	$response = file_get_contents("https://api.mapbox.com/search/geocode/v6/forward?&address_number=".urlencode($address["civico_gruppo"])."&street=".urlencode($address["indirizzo_gruppo"])."&postcode=".urlencode($address["cap_gruppo"])."&country=IT&proximity=ip&limit=1&access_token=".$mapbox_token);
	return json_decode($response, true)["features"][0]["geometry"]["coordinates"];
}
