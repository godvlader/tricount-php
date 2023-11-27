<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />
    <meta name="viewport" +
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/ch-pass.css" rel="stylesheet" type="text/css" />

    <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>
    <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
    <script src="lib/just-validate-plugin-date-1.2.0.production.min.js" type="text/javascript"></script>
    <script src="lib/validationIT3.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="lib/sweetalert2@11.js" type="text/javascript"></script>

    <title>Change Password</title>
    <?php
    $justvalidate = Configuration::get("justvalidate");
    ?>
    <script>
        const useJustValidate = <?= json_encode($justvalidate === "on") ?>;
        if (useJustValidate) {
            window.onload = function () {
                JVChangePassword();
            };
        }
    </script>
</head>

<?php include('menu.php'); ?>

<script>
    function togglePasswordVisibility(passwordInputId) {
        var passwordField = document.getElementById(passwordInputId);

        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
    $(document).ready(function () {
        //pour it3
        var isModified = false;
        $("input[type='number'], input[type='checkbox'], input ").change(function () {
            isModified = true; console.log(isModified);
        });
        console.log(isModified);

        console.log(isModified);
        $(".backBtn").on("click", function (e) {
            if (isModified) {
                e.preventDefault();
                return confirmLeavePage();
            }
        });
    });
    function confirmLeavePage() {
        Swal.fire({
            title: 'Attention!',
            text: "Vous êtes sur le point de quitter la page sans enregistrer les modifications de la dépense. Voulez-vous vraiment quitter la page ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= $backValue; ?>";
            }
        })
    }
</script>

<body>
    </main>

    <div class="chpass_page">
        <div class="chpass-container">
            <form class="chpass-form" id="chpass-form" method="POST" enctype="multipart/form-data">

                <div class="chpass-title">
                    <h2 style="color:black">Change password for
                        <?= $user->getFullName() ?>
                    </h2>

                </div>
                <div class="password-input">

                    <input class="chpass-form-items" type="password" name="currentPassword" id="currentPassword"
                        value="<?= $currentPasswordValue ?>" placeholder="Current Password" required>
                    <button type="button" onclick="togglePasswordVisibility('currentPassword')">Show
                        Password</button>
                    <input type="hidden" name="user" value="<?= json_encode($user) ?>">
                </div>
                <div class="password-input">
                    <input class="chpass-form-items" type="password" placeholder="New Password" name="newPassword" +
                        id="newPassword" value='<?= $newPasswordValue ?>' required>
                    <button type="button" onclick="togglePasswordVisibility('newPassword')">Show Password</button>
                </div>
                <div class="password-input">
                    <input class="chpass-form-items" type="password" placeholder="Confirm Password" +
                        name="confirmPassword" id="confirmPassword" value='<?= $confirmPasswordValue ?>' required>
                    <button type="button" onclick="togglePasswordVisibility('confirmPassword')">Show
                        Password</button>
                </div>
                <input class="chpass-form-btn" type="submit" value="Save">

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
            <?php elseif (strlen($success) != 0): ?>
                <p><span class='success'>
                        <?= $success ?>
                    </span></p>

            <?php endif; ?>

        </div>
    </div>

</body>



</html>