<!DOCTYPE html>

<head>
    <base href="<?= $web_root ?>" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/edit-prf.css" rel="stylesheet" type="text/css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
    <script src="lib/just-validate-plugin-date-1.2.0.production.min.js" type="text/javascript"></script>
    <script src="lib/validationIT3.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="lib/sweetalert2@11.js" type="text/javascript"></script>

    <?php
    $justvalidate = Configuration::get("justvalidate");
    ?>
    <script>
        const useJustValidate = <?= json_encode($justvalidate === "on") ?>;
        if (useJustValidate) {
            window.onload = function () {
                JVEditProfile();
            };
        }
    </script>
    <title>edit profile</title>
</head>

<script>
    $(document).ready(function() {
        //pour it3
        var isModified = false;
        $("input[type='number'], input[type='checkbox'], input ").change(function() {
            isModified = true;        console.log(isModified);
        });
        console.log(isModified);
        
        console.log(isModified);
        $(".backBtn").on("click", function(e) {
            if(isModified){
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
    <?php include 'menu.php' ?>
    <div class="prf-body">
        <div class="edit_profile">
            <div class="chprof-form-items">
                <div class="title">
                    <?= $user->getFullName() ?>
                </div>
                <!-- Form pour collecter les données du user -->
                <form id="edit_profile" action="profile/edit_profile" method="post">
                    <!-- récup son pseudo -->
                    <label for="fullName">Name:</label><br>
                    <input type="text" id="fullName" name="fullName" value="<?= isset($user) ? $user->getFullName() : $_SESSION['full_name'] ?>"><br>

                    <!-- récup son mail -->
                    <label for="mail">Email:</label><br>
                    <input type="email" id="mail" name="mail" value="<?php echo $user->getMail(); ?>"><br>

                    <!-- récup son iban (peut être null) -->
                    <label for="iban">IBAN:</label><br>
                    <input type="text" id="iban" name="iban" pattern='[BE]+\d\d(\s([0-9]+\s)+)\d\d\d\d'
                        placeholder="IBAN ex : BE99 9999 9999 9999" value="<?php echo $user->getIban(); ?>"><br><br>

                    <!-- Submit button to send the form data -->

                    <input type="submit" value="submit">
                    
                </form>
                <a href="profile/change_password">change your password</a>

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
    </div>

</body>

</html>