<!doctype html>
<html lang="en">

<head>
    <base href="<?= $web_root ?>" />
    <meta charset="UTF-8">
    <title>
        <?= $user->getFullName() ?>'s Tricount!
    </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <link rel="stylesheet" href="css/style.css">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
    <!-- App Header -->
    <div class="appHeader">
        <!-- Back Button -->
        <div class="left">
            <a href="user/profile" class="headerButton goBack">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>

        <!-- Page Title -->
        <div class="pageTitle">
            Your tricount
        </div>

        <!-- Add Button -->
        <div class="right">
            <a href="tricount/add" class="headerButton goBack">
                <i class="bi bi-plus-lg"></i>
            </a>
        </div>
    </div>

    <!-- List of Tricounts -->
    <div id="listTricount">
        <div class="section mt-2">
            <!-- Tricount Block -->
            <?php foreach ($tricounts_list as $tl): ?>
                <div class="card-block mb-2">

                        <a href="operation/expenses/<?= $tl->get_id()?>" class="button-card">
                            <div class="card-main">
                                <!-- Tricount Title and Description -->
                                <div class="balance">
                                    <span class="label"><?= $tl->get_title() ?></span>
                                    <h1 class="title">
                                        <?= $tl->get_description() == null ? "No description" : $tl->get_description() ?>
                                    </h1>
                                </div>

                                <!-- Number of Friends in Tricount -->
                                <div class="in">
                                    <div class="card-number">
                                        <span class="label">
                                            <?php
                                            echo $tl->number_of_friends($tl->get_id()) == 0
                                                ? "you're alone!"
                                                : "with " . $tl->number_of_friends($tl->get_id()) . " friends";
                                            ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Hidden Tricount ID -->
                                <input type='text' name="id" id="id" value="<?= $tl->get_id() ?>" hidden>
                            </div>
                        </button>
            </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- * tricount block -->

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <!-- Base Js File -->
    <script src="css/src/js/base.js"></script>
</body>

</html>
