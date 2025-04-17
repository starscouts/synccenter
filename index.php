<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Synccenter</title>
    <link rel="stylesheet" href="/assets/bootstrap.css">
    <script src="/assets/bootstrap.min.js"></script>
    <style>
        @media (max-width: 700px) {
            .nav-item.hide-mobile {
                display: none;
            }
        }
    </style>
</head>
<body style="overflow: hidden;">
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" data-tab-id="home" aria-current="page" href="#/home">Home</a>
    </li>
    <?php $nodes = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/nodes.json"), true); foreach ($nodes as $name => $data): ?>
    <li class="nav-item hide-mobile">
        <a class="nav-link" data-tab-id="<?= $name ?>" href="#/<?= $name ?>">
            <span id="status-<?= $name ?>" class="bg-secondary" style="border-radius: 999px; display: inline-block; width: 8px; height: 8px; position: relative; top: -2px;"></span>
            <span><?= $data[0] ?></span>
        </a>
    </li>
    <script>
        async function update_<?= $name ?>() {
            let result = await (await fetch("/status/?node=<?= $name ?>")).json();

            if (result['online']) {
                document.getElementById("status-<?= $name ?>").classList.remove("bg-secondary");
                document.getElementById("status-<?= $name ?>").classList.remove("bg-danger");
                document.getElementById("status-<?= $name ?>").classList.remove("bg-success");
                document.getElementById("status-<?= $name ?>").classList.add("bg-success");
            } else {
                document.getElementById("status-<?= $name ?>").classList.remove("bg-secondary");
                document.getElementById("status-<?= $name ?>").classList.remove("bg-danger");
                document.getElementById("status-<?= $name ?>").classList.remove("bg-success");
                document.getElementById("status-<?= $name ?>").classList.add("bg-danger");
            }
        }

        update_<?= $name ?>();

        setInterval(() => {
            update_<?= $name ?>();
        }, 10000);
    </script>
    <?php endforeach; ?>
</ul>

<iframe id="frame" src="/home" style="width: 100%; height: calc(100vh - 42px);"></iframe>
<div id="loader" style="position: fixed; bottom: 0; left: 0; right: 0; top: 42px; background: rgba(255, 255, 255, .5);"></div>

<script>
    location.hash = "#/home";
    let nodes = JSON.parse(`<?= json_encode($nodes) ?>`);

    window.onhashchange = () => {
        document.getElementById("loader").style.display = "";
        console.log(location.hash);
        let id = location.hash.substring(2);

        Array.from(document.getElementsByClassName("nav-link")).forEach(i => i.classList.remove("active"));
        Array.from(document.getElementsByClassName("nav-link")).filter(i => i.getAttribute("data-tab-id") === id)[0].classList.add("active");

        if (nodes[id]) {
            document.getElementById("frame").src = "/_upstream/" + id;
        } else {
            document.getElementById("frame").src = "/" + id;
        }

        document.title = Array.from(document.getElementsByClassName("nav-link")).filter(i => i.getAttribute("data-tab-id") === id)[0].innerText.trim() + " | Synccenter";
    }

    document.getElementById("frame").onload = () => {
        document.getElementById("loader").style.display = "none";
    }

    document.title = "Home | Synccenter";
</script>
</body>
</html>