<!doctype html>
<html lang="en">

<head>
    <base href="<?= $web_root ?>" />
    <meta charset="UTF-8">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
    <script src="lib/just-validate-plugin-date-1.2.0.production.min.js" type="text/javascript"></script>
    <script src="lib/validationIT3.js" type="text/javascript"></script>

    <title>
        <?= $user->getFullName() ?>'s Tricount!
    </title>
    
    <?php
    $justvalidate = Configuration::get("justvalidate");
    ?>
    <script>
        const useJustValidate = <?= json_encode($justvalidate === "on") ?>;
        if (useJustValidate) {
            window.onload = function () {
                JVAddTricount();
            };
        }
    </script>
</head>

<body>
    <form id="addTricount" action="tricount/add" method="post">
        <div class="appHeader">
            <div class="left">
                <a href="tricount/index" class="headerButton goBack">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <div class="pageTitle">
                Add new Tricount
            </div>
            <div class="right">
                <button type="submit" value="add" class="addTricount_btn">
                    <i class="bi bi-save"></i>
                </button>
            </div>
        </div>

        <!-- * tricount block -->

        <div class="section mt-2 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="form-group boxed">
                        <div class="input-wrapper position-relative">
                            <label id="title" class="label" for="text4b">Title</label>
                            <input type="text" name="title" class="form-control" id="text4b"
                             <?php if (isset($tricount) && !empty($tricount)){echo 'value='.$tricount->get_title();}else {echo 'placeholder="Your Title here!"';} ?>>
                            <div class="icon-overlay" id="text4b-icon" style="display: none;"></div>
                            <input type="hidden" name="creator" value="<?php echo $user->getUserId(); ?>">
                            <span id="text4b-error" class="text-danger"></span>
                            <span id="text4b-success" class="text-success"></span>
                        </div>
                    </div>
                    <div class="form-group boxed">
                        <div class="input-wrapper position-relative">
                            <label class="label" for="textarea4b">Description (optional)</label>
                            <textarea id="textarea4b" name="description" rows="2" class="form-control"
                                placeholder="Your description here!"></textarea>
                            <div class="icon-overlay" id="textarea4b-icon" style="display: none;"></div>
                            <span id="textarea4b-error" class="text-danger"></span>
                            <span id="textarea4b-success" class="text-success"></span>
                        </div>
                    </div>
                </div>

            </div>

            <?php if (count($errors) != 0): ?>
                <div class='errors'>

                        <br><br><p>Please correct the following error(s) :</p>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                </div>
            <?php endif; ?>
        </div>
    </form>
    </div>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="css/src/splide/splide.min.js"></script>
    <!-- Base Js File -->
    <script src="css/src/js/base.js"></script>

    <script>
       
        $(document).ready(function () {
            $(".errors").hide();
            function validateTitle(title, callback) {
                $.ajax({
                    url: "tricount/get_title_service",
                    type: "POST",
                    data: {
                        title: title,
                        creator: $('input[name="creator"]').val()
                    },
                    dataType: "json",
                    success: function (response) {
                        // Assuming the server returns a JSON object with a 'unique' property
                        if (response.unique) {
                            callback(true);
                        } else {
                            callback(false);
                        }
                    },
                    error: function () {
                        alert("An error occurred while validating the title. Please try again.");
                    }
                });
            }

            function showError(inputId, errorMessage) {
                $(`#${inputId}`).addClass('input-error').removeClass('input-success');
                $(`#${inputId}-icon`).html('<i class="bi bi-exclamation-circle-fill text-danger"></i>').show();
                $(`#${inputId}-error`).text(errorMessage).show();
                $(`#${inputId}-success`).text('').hide();
            }

            function showSuccess(inputId, successMessage) {
                $(`#${inputId}`).addClass('input-success').removeClass('input-error');
                $(`#${inputId}-icon`).html('<i class="bi bi-check-circle-fill text-success"></i>').show();
                $(`#${inputId}-success`).text(successMessage).show();
                $(`#${inputId}-error`).text('').hide();
            }

            function clearMessages(inputId) {
                $(`#${inputId}`).removeClass('input-error input-success');
                $(`#${inputId}-error`).text('').hide();
                $(`#${inputId}-success`).text('').hide();
            }

            function validateInputs() {
                const title = $("#text4b").val().trim();
                const description = $("#textarea4b").val().trim();

                if (title.length < 3) {
                    showError('text4b', 'Title must have at least 3 characters.');
                } else {
                    validateTitle(title, function (isTitleValid) {
                        if (!isTitleValid) {
                            showError('text4b', 'Title must be unique for the creator.');
                        } else {
                            showSuccess('text4b', 'Looks good!');
                        }
                    });
                }

                if (description && description.length < 3) {
                    showError('textarea4b', 'Description must have at least 3 characters if provided.');
                } else {
                    showSuccess('textarea4b', 'Looks good!');
                }
            }

            $("#text4b, #textarea4b").on('input', validateInputs);

            $("#addTricount").submit(function (e) {
                e.preventDefault();

                validateInputs();

                const hasError = $(".input-error").length > 0;

                // submit if validations passed
                if (!hasError) {
                    $("#addTricount")[0].submit();
                }
            });
        });

    </script>

</body>

</html>