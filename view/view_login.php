<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Log In</title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
    <script src="lib/just-validate-plugin-date-1.2.0.production.min.js" type="text/javascript"></script>
    <script src="lib/validationIT3.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php
    $justvalidate = Configuration::get("justvalidate");
    ?>
    <script>
        const useJustValidate = <?= json_encode($justvalidate === "on") ?>;
        if (useJustValidate) {
            window.onload = function () {
                JVLogin();
            };
        }
    </script>
</head>
<div class="navbar">
    <div class="navbar-container">
        <div class="logo-container">
            <h1 class="logo"><i class="fa fa-credit-card" aria-hidden="true"
                    onclick='window.location.reload(true);'></i> Tricount</h1>
        </div>
    </div>
</div>
</div>

<body>
    <div class="page" id="box">
        <div class="form">
            <form id="login-form" action="main/login" method="post">
                <div class="login-title">
                    <h2>Log in</h2>
                </div>
                <table>
                    <tr>
                        <td><input class="sign" id="mail" name="mail" placeholder="example@email.com" type="email"
                                value="<?= $mail ?>"></td>
                    </tr>
                    <tr>
                        <td><input class="sign" id="password" placeholder="Password" name="password" type="password"
                                value="<?= $password ?>"></td>
                    </tr>
                </table>
                <input class="login-button" type="submit" value="Log In"><br>
                <div class="sign-link">
                    <a class="login-icon" href="main/signup">Or sign up</a>

                </div>

            </form>
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li>
                                <p>
                                    <?= $error ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>