<?php

$CLIENT_PATH = "spreadsheet2json";

function client_is_present() {
	return false;
}

function download_client($url) {
}

function client_get_version() {
	return "0.0.0";
}

function client_get_latest_version() {
	return "1.0.1";
}

function ensure_updated_version() {
	$latest = client_get_latest_version();
	$latest_url = $latest[1];
	if(client_is_present()) {
		$current = client_get_version();
		$latest_version = $latest[0];
		if(version_compare($latest_version, $current) > 0) {
			download_client($latest_url);
		}
	} else {
		download_client($latest_url);
	}
}

function load_data($path) {
	ensure_updated_version();
	return json_decode(exec("./");
}
