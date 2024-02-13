<?php

$CLIENT_PATH = "spreadsheet2json";

function agd_get_spreadsheet2json_path() {
	return dirname(dirname(__FILE__))."/spreadsheet2json";
}

function agd_detect_triplet() {
	$os = PHP_OS;
	$triplet = null;
	switch($os) {
	case "Linux":
		$uname_data = explode(" ", php_uname());
		$arch = $uname_data[count($uname_data) - 1];
		$triplet = $arch."-unknown-linux-musl";
	}
	return $triplet;
}

function agd_client_is_present() {
	return false;
}

function agd_download_client($url) {
	$path = agd_get_spreadsheet2json_path();
	try {
		$zip = gzdecode(file_get_contents($url));
		file_put_contents($path.".tar", $zip);
		$phar = new PharData($path.".tar");
		if(is_file($path)){
			unlink($path);
		}
		$phar->extractTo(dirname($path), "spreadsheet2json");
		unlink($path.".tar");
	} catch (\Throwable $th) {
		throw $th;
	}
}

function agd_client_get_version() {
	return explode(" ", exec(agd_get_spreadsheet2json_path()." --version"))[1];
}

function agd_ensure_updated_version() {
	$opts = [
		'http' => [
				'method' => 'GET',
				'header' => ['User-Agent: spreadsheet2json_downloader github.com/eutampieri/agesci-groups-data']
		]
	];

	$context = stream_context_create($opts);
	$new_version_data = json_decode(file_get_contents("https://api.github.com/repos/eutampieri/spreadsheet2json/releases/latest", false, $context), true);
	$new_version = $new_version_data["name"];
	$new_version = str_replace("v", "", $new_version);
	$path = agd_get_spreadsheet2json_path();
	$triplet = agd_detect_triplet();
	$url = null;
	foreach($new_version_data["assets"] as $a) {
		if($a["size"] > 400 && strpos($a["name"], $triplet) !== false && strpos($a["name"], ".tar.gz") !== false) {
			$url = $a["browser_download_url"];
		}
	}
	if(!is_file($path)) {
		agd_download_client($url);
	} else {
		$version = agd_client_get_version();

		if(version_compare($new_version, $version) > 0) {
			agd_download_client($url);
		}
	}
}

function agd_load_data($path) {
	ensure_updated_version();
	return json_decode(exec(agd_get_spreadsheet2json_path()." --filename ".$path));
}
