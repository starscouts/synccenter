<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php";

$nodes = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/nodes.json"), true);
header("Content-Type: application/json");

if (isset($_GET["node"]) && isset($nodes[$_GET["node"]])) {
    $data = file_get_contents((isset($nodes[$_GET["node"]][3]) ? "https://" : "http://") . $nodes[$_GET["node"]][1] . "/rest/system/status", false, stream_context_create([
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
        json_decode($data);

        if (json_last_error() === JSON_ERROR_NONE) {
            die('{"online":true}');
        } else {
            die('{"online":false}');
        }
    } else {
        die('{"online":false}');
    }
}