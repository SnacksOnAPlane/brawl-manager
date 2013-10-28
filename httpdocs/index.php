<?php

function getConnectedPiIDs() {
	$output = `/usr/local/bin/lspis | tail -n +3 | awk {'print $4'}`;
	$connections = explode("\n", $output);
	$retme = [];
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

print_r(getConnectedPiIDs());
?>
