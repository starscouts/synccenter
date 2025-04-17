<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php";

$nodes = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/nodes.json"), true);
header("Content-Type: application/json");
$list = [];

if (isset($_GET["node"]) && isset($nodes[$_GET["node"]])) {
    $data = file_get_contents((isset($nodes[$_GET["node"]][3]) ? "https://" : "http://") . $nodes[$_GET["node"]][1] . "/rest/stats/folder", false, stream_context_create([
        "http" => [
            "method" => "GET",
            "header" => "X-API-Key: " . $nodes[$_GET["node"]][2] . "\r\n" . (isset($nodes[$_GET["node"]][3]) ? "Authorization: Basic " . $nodes[$_GET["node"]][3] . "\r\n" : "")
        ],
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false
        ]
    ]));

    if ($data !== "") {
        foreach (array_keys(json_decode($data, true)) as $folder) {
            $list[$folder] = null;

            $data = file_get_contents("http://" . $nodes[$_GET["node"]][1] . "/rest/db/status?folder=" . $folder, false, stream_context_create([
                "http" => [
                    "method" => "GET",
                    "header" => "X-API-Key: " . $nodes[$_GET["node"]][2] . "\r\n" . (isset($nodes[$_GET["node"]][3]) ? "Authorization: Basic " . $nodes[$_GET["node"]][3] . "\r\n" : "")
                ],
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false
                ]
            ]));

            $list[$folder] = json_decode($data, true)["state"];
        }

        die(json_encode($list, JSON_PRETTY_PRINT));
    } else {
        die('{}');
    }
}