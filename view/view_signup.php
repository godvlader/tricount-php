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
    <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>
    <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
    <script src="lib/just-validate-plugin-date-1.2.0.production.min.js" type="text/javascript"></script>
    <script src="lib/validationIT3.js" type="text/javascript"></script>
    <?php
        $justvalidate = Configuration::get("justvalidate");
    ?>
     <script>
        const useJustValidate = <?= json_encode($justvalidate === "on") ?>;
        if (useJustValidate) {
            window.onload = function() {
                justValidate();
            };
        }
    </script>
</head>

    <body>

        <div class="navbar">
            <div class="navbar-container">
                <div class="logo-container">
                    <h1 class="logo"><i class="fa fa-credit-card" aria-hidden="true"
                            onclick='window.location.reload(true);'></i> Tricount</h1>
                </div>
                <a class="backBtn" href="main/index">Back</a>
            </div>
        </div>

    
    <div class="signup_page">
        <div class="sign-up-container">
            <form class="signup-form" id="signup-form" method="post" action="main/signup" enctype="multipart/form-data">
                <div class="signup-title">
                    <h2>Sign up</h2>
                </div>
                <ul>
                    <li><input class="signup-form-items" type="email" placeholder="Email" name="mail" id="mail"
                            value="<?= $mail ?>" required></li>
                    <li><input class="signup-form-items" type="text" placeholder="Full Name" name="full_name"
                            id="full_name" value="<?= $full_name ?>" required></li>
                    <li><input class="signup-form-items" type="int" pattern="[a-zA-Z]+\d\d(\s([0-9]+\s)+)\d\d\d\d"
                            placeholder="IBAN ex : AA99 9999 9999 9999" name="iban" id="iban" value="<?= $iban ?>"></li>
                    <li><input class="signup-form-items" type="password" placeholder="Password" name="password"
                            id="password" value="<?= $password ?>" required></li>
                    <li><input class="signup-form-items" type="password" placeholder="Confirm Password"
                            name="password_confirm" id="password_confirm" value="<?= $password_confirm ?>" required>
                    </li>
                    <li><input class="signup-form-btn" id="submit" type="submit" value="SIGN UP"></li>
                    <li><a class="cancel-sign-up-btn" href="main/index">CANCEL</a></li>
                </ul>

            </form>
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li>
                                <?= $error ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>