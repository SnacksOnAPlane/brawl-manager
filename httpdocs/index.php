<?php
$db = new PDO('mysql:dbname=fsbrawl_new;host=fsbrawldb.cjkdd9xya3gn.us-east-1.rds.amazonaws.com;charset=utf8', 'fsbrawl_read', 'reader');

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
	global $db;
	$sth = $db->prepare("SELECT * FROM Hotspots");
	$sth->execute();
	return $sth->fetchAll(PDO::FETCH_ASSOC);
}

function getHotspotStatus($hotspot_id) {
	global $connected;
	return in_array(intval($hotspot_id), $connected) ? "ON" : "OFF";
}
$connected = getConnectedPiIDs();
?>
<table>
	<theader>
		<th>ID</th>
		<th>Name</th>
		<th>Status</th>
	</theader>
	<tbody>
<?php
foreach (getAllHotspots() as $hotspot) {
	echo "<tr><td>";
	echo $hotspot['Hotspot_ID'];
	echo "</td><td>";
	echo $hotspot['Name'];
	echo "</td><td>";
	echo getHotspotStatus($hotspot['Hotspot_ID']);
	echo "</td></tr>";
}
?>
	</tbody>
</table>
