<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; ?>
<!doctype html>
<html lang="en" style="overflow: auto;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Synccenter</title>
    <link rel="stylesheet" href="/assets/bootstrap.css">
    <script src="/assets/bootstrap.min.js"></script>
</head>
<body style="overflow: hidden;">
    <div class="container">
        <br>
        <h1>Welcome back!</h1>

        <p>Here is a list of the available nodes:</p>

        <div class="list-group">
            <?php $nodes = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/nodes.json"), true); foreach ($nodes as $name => $data): ?>
                <a onclick="parent.location.hash = '#/<?= $name ?>'" class="list-group-item list-group-item-action" style="cursor: pointer;">
                    <?= $data[0] ?>
                    <span style="float: right;" id="meta-<?= $name ?>"></span>
                </a>
            <?php endforeach; ?>
        </div>

        <script>
            <?php $nodes = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/nodes.json"), true); foreach ($nodes as $name => $data): ?>
            async function message_<?= $name ?>() {
                let result = await (await fetch("/folders/?node=<?= $name ?>")).json();
                let state = 0;

                for (let item of Object.values(result)) {
                    if (item === "scan-waiting") {
                        if (state < 3) state = 3;
                    } else if (item !== "idle" && item !== "syncing" && item !== "sync-preparing" && item !== "scanning" && item !== "scan-waiting") {
                        if (state < 2) state = 2;
                    } else if (item !== "idle") {
                        if (state < 1) state = 1;
                    }
                }

                document.getElementById("meta-<?= $name ?>").innerText = state === 1 ? "Syncing" : (state === 2 ? "Failed" : (state === 3 ? "Queued" : "Up to date"));
                document.getElementById("meta-<?= $name ?>").classList.remove("text-primary");
                document.getElementById("meta-<?= $name ?>").classList.remove("text-danger");
                document.getElementById("meta-<?= $name ?>").classList.remove("text-success");
                document.getElementById("meta-<?= $name ?>").classList.add(state === 1 ? "text-primary" : (state === 2 ? "text-danger" : (state === 3 ? "text-warning" : "text-success")));
            }

            message_<?= $name ?>();

            setInterval(() => {
                message_<?= $name ?>();
            }, 10000);
            <?php endforeach; ?>
        </script>
    </div>

    <br><br><br>
</body>
</html>