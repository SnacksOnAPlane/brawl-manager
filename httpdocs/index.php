<?php
$db = new PDO('mysql:dbname=fsbrawl_new;host=fsbrawldb.cjkdd9xya3gn.us-east-1.rds.amazonaws.com;charset=utf8', 'fsbrawl', 'Brawl2011');

$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function getConnectedPiIDs() {
	$output = `/usr/local/bin/lspis | tail -n +1 | awk {'print $4'}`;
	$connections = explode("\n", $output);
	$retme = array();
	foreach ($connections as $connection) {
		if (!$connection) {
			continue;
		}
		$parts = split(":",$connection);
		$port = intval($parts[1]) - 8000;
		$retme[]= $port;
	}
	return $retme;
}

function getAllHotspots() {
}
print_r(getConnectedPiIDs());
?>
