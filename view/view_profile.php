<!doctype html>
<html lang="en">

    <head>
        <base href="<?= $web_root ?>" />
        <meta charset="UTF-8">
        <title>
            <?= $user->getFullName() ?>'s Tricount!
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link
            href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    </head>

    <body>
        <?php include 'menu.php' ?>
        <div class="prf-body">
            <div class="title">
                <?= $user->getFullName() ?>'s Tricount!
            </div>
            <div class="profile">
                <div class="name">
                    <h1>PROFILE</h1>
                    <div>
                        <p>Hey
                            <?php echo $user->getFullName(); ?> !
                        </p>
                    </div>
                    <p>I know your email address is <span style="color:#e34646">
                            <?php echo $user->getMail(); ?>
                        </span>.</p>
                    <p>What can i do for you?</p>
                    <!-- LE ID S'AFFICHE QUE SI ON LOGOUT ET RE LOGIN pas besoin de lui la mais pour savoir-->
                </div>
            </div>
            <div class="profile-buttons">
                <ul><li><a class="create" href="tricount/index">Tricount list</a></li>
                    <li><a class="create" href="tricount/add">Create tricount</a></li>
                    <li><a class="edit" href="Profile/edit_profile">Edit profile</a></li>
                    <li><a class="change" href="profile/change_password">Change password</a></li>
                    <li><a class="logout" href="user/logout">Logout</a></li>
                </ul>
            </div>
        </div>


    </body>

</html>
