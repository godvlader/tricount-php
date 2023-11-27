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
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
        <link rel="stylesheet" href="css/style.css">
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    </head>

    <body>
        <div class="appHeader">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">
                Delete your tricount ?
            </div>
            <div class="right">
                <a href="tricount/add" class="headerButton goBack" >
                    <ion-icon name="add-outline"></ion-icon>
                </a>
            </div>
        </div>
        <div class="confirm_delete_Tricount">
          <div class="confirmation-box">
            <div class="trash_confirm_delete_Tricount">
              <i class="bi bi-trash3"></i>
            </div>
          <hr>
            <h2>Etes-vous sûr de vouloir supprimer ce tricount " <?= $tricount->get_title()?> " ?</h2>
            <p>Cette action est irréversible et supprimera
              toutes les données associées au tricount.</p>
            <div class="button_delete_confirm">
              <form action="tricount/delete_confirm/<?= $tricount->get_id()?>" method="POST" method="post">
                <button class="delete-tricount-confirm" type="submit">Supprimer</button>
                <a href="tricount/index" class="cancel-tricount-confirm">Annuler</a>
              </form>
            </div>
          </div>
        </div>
        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <!-- Splide -->
        <script src="css/src/splide/splide.min.js"></script>
        <!-- Base Js File -->
        <script src="css/src/js/base.js"></script>
    </body>

</html>
