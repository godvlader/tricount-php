<!DOCTYPE html>
<html>

<head>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/add-exp.css" rel="stylesheet" type="text/css" />
    <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>
    <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
    <script src="lib/just-validate-plugin-date-1.2.0.production.min.js" type="text/javascript"></script>
    <script src="lib/validationIT3.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="lib/sweetalert2@11.js" type="text/javascript"></script>

    <title>
        <?php echo isset($operation) ? 'Edit Expense' : 'Add Expense'; ?>
    </title>

    <?php
    $justvalidate = Configuration::get("justvalidate");
    ?>
    <script>
        const useJustValidate = <?= json_encode($justvalidate === "on") ?>;
        if (useJustValidate) {
            window.onload = function () {
                JVAddOperation();
            };
        }
    </script>

    <script>
        function updateAmount(userCheckbox, totalAmount, totalWeight) {
            var user = userCheckbox.val();
            var weightInput = userCheckbox.closest('.check-input').find('input[type="number"]');
            var weight = parseFloat(weightInput.val());
            var amount = 0;
            if (userCheckbox.is(":checked") && weight > 0) {
                amount = weight * (totalAmount / totalWeight);
            }
            $("#" + user + "_amount").val(amount.toFixed(2));
        }

        function calculateAmounts() {
            var totalAmount = parseFloat($("#amount").val());
            var weights = {};
            var totalWeight = 0;

            $("input[type='checkbox']:not(#save):checked").each(function () {
                var userId = $(this).val();
                var weight = parseFloat($(`input[type="number"][name="w[${userId}]"]`).val());
                weights[userId] = weight;
                totalWeight += weight;
            });

            $("input[type='checkbox']:not(#save)").each(function () {
                var userCheckbox = $(this);
                updateAmount(userCheckbox, totalAmount, totalWeight);
            });
        }

        $(document).ready(function () {
            const repartitionTemplate = $('#repartitionTemplate');
            const refreshBtn = $('#refreshBtn');
            var isModified = false;

            repartitionTemplate.on('change', function () {
                refreshBtn.hide();

                const selectedTemplateId = repartitionTemplate.val();
                console.log(selectedTemplateId);
                $.ajax({
                    url: 'operation/get_template_service/' + selectedTemplateId,
                    method: 'GET',
                    dataType: 'json',
                    success: function (templateData) {
                        updateInputsAndCheckboxes(templateData);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error fetching template data:', textStatus, errorThrown);
                    }
                });

                function updateInputsAndCheckboxes(templateData) {
                    var check = $(`input[type="checkbox"]`);
                    check.prop('checked', false);

                    var numb = $(`input[type="number"]`);

                    templateData.forEach(userTemplateData => {
                        const userCheckbox = $(`input[type="checkbox"][name="c[${userTemplateData.user}]"]`);
                        if (userCheckbox.length > 0) {
                            userCheckbox.prop('checked', true);
                            const userWeight = $(`input[type="number"][name="w[${userTemplateData.user}]"]`);
                            userWeight.val(userTemplateData.weight);
                        }
                    });

                    calculateAmounts();
                }
            });

            calculateAmounts();

            // Add event listener for each checkbox
            $("input[type='checkbox']:not(#save)").change(function () {
                const userCheckbox = $(this);
                const weightInput = userCheckbox.closest('.check-input').find('input[type="number"]');

                if (userCheckbox.is(":checked")) {
                    weightInput.val(1);
                }
                calculateAmounts();
            });

            $("input[type='number'], input[type='checkbox'], input ").change(function () {
                isModified = true; console.log(isModified);
                calculateAmounts();
            });

            <?php if (isset($operation) && $operation !== null): ?>
                $(".backBtn").on("click", function (e) {
                    if (isModified) {
                        e.preventDefault();
                        return confirmLeavePage();
                    }
                });
            <?php endif; ?>
            showDeleteButton()
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
        async function deleteExpense(id) {
            try {
                await $.post("operation/delete_service/" + id);
            } catch (e) {
                console.log("Erreur : " + e);
            }
        }

            // /** <---------------------------  IT3 ---------------------------> * /
        function showDeleteButton() {

            let deleteBtn = '<button class="delete-btn" onclick="confirmDelete()" style="background-color: gold" color: white;"> delete';
            $('.deleteContainer').html(deleteBtn);
        }

        <?php if (isset($operation) && $operation !== null): ?>
                function confirmDelete() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteExpense(<?= $operation->get_id(); ?>);
                    Swal.fire(
                        'Deleted!',
                        'Your expense has been deleted.',
                        'success'
                    ).then(() => {
                        window.location.href = 'operation/expenses/' + <?= $tricount->get_id(); ?>;
                    });
                }
                    })
            console.log(id);

                }
        <?php endif; ?>

    </script>

</head>

<body>
    <?php include 'menu.php' ?>
    <div class="add-exp">
        <p>
            <?php echo $tricount->get_title(); ?> >
            <?php echo isset($operation) ? 'Edit expense' : 'Add expense'; ?>
        <div id="error-container"></div>

        </p>
        <form id="add-exp-form"
            action="<?php echo isset($operation) ? "operation/edit_expense/{$operation->get_id()}" : 'operation/add_expense'; ?>"
            method="post">

            <?php if (isset($operation)): ?>
                <input type="hidden" id="operationId" name="operationId" value="<?php echo $operation->get_id() ?>">
            <?php endif; ?>
            <div class="add-exp-title">
                <label for="title">Title</label>
                <input required class="addExp" placeholder="Title" type="text" id="title" value="<?php
                if (isset($operation))
                    echo $operation->getTitle();
                else if (isset($info))
                    echo $info[0];
                else
                    echo ''; ?>" name="title">
            </div>


            <input type="hidden" id="tricId" name="tricId" value="<?php echo $tricount->get_id() ?>">
            <br>

            <div class="add-exp-amount">
                <label for="operation_amount">Amount</label>
                <input required class="addExp" placeholder="Amount (EUR)" value="<?php
                if (isset($operation))
                    echo $operation->getAmount();
                else if (isset($info))
                    echo $info[1];
                else
                    echo ''; ?>" type="number" step="0.01" id="amount" name="amount" oninput="calculateAmounts()">
            </div>
            <br>
            <div class="add-exp-date">
                <label for="operation_date">Date</label>
                <input class="addExp" type="date" id="operation_date" value="<?php
                if (isset($operation))
                    echo $operation->getOperationDate();
                else if (isset($info))
                    echo $info[2];
                else
                    echo date('Y-m-d'); ?>" name="operation_date">
            </div>

            <br>

            <label for="paid_by">Paid By</label>
            <select id="initiator" name="initiator">
                <?php
                if (isset($operation)) {
                    echo "<option style='color: black;' selected value='{$operation->getInitiatorId()}'>{$operation->getInitiator()}</option>";
                } else if (isset($init)) {
                    echo "<option style='color: black;' selected value='{$init->getUserId()}'>{$init->getFullName()}</option>";
                }
                ?>
                <?php foreach ($users as $user): ?>
                    <?php if (!isset($operation) || $user->getUserInfo() !== $operation->getInitiator()): ?>
                        <option style="color: black;" value="<?= $user->get_user() ?>"><?= $user->getUserInfo() ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="repartition_template">Use repartition template (optional)</label>
            <button name="refreshBtn" id="refreshBtn">Refresh</button>
            <select id="repartitionTemplate" name="rti">
                <?php if (isset($template)) {
                    echo "<option style='color: black;' value='{$template->get_id()}'>{$template->get_title()}</option>";

                } else {
                    echo "<option style='color: black;' value='option-default'>No, I'll use custom repartition</option>";
                }
                ?>
                <?php if ($allTemplates !== null): ?>
                    <?php foreach ($allTemplates as $rt): ?>
                        <?php if (isset($template)): ?>
                            <?php if ($rt->title !== $template->get_title()): ?>
                                <option name="option_template" id="option_template" style="color: black;" value="<?php echo $rt->id ?>">
                                    <?php echo $rt->title ?>
                                </option>
                            <?php endif; ?>

                        <?php else: ?>
                            <option name="option_template" id="option_template" style="color: black;" value="<?php echo $rt->id ?>">
                                <?php echo $rt->title ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

            </select>

            <label for="who">For whom? (select at least one)</label>
            <?php foreach ($users as $usr) {
                $repartitions_map = [];
                if (!empty($repartitions)) {
                    foreach ($repartitions as $repartition) {
                        $repartitions_map[$repartition->user] = $repartition;
                    }
                }
                ?>
                <div class="check-input">
                    <?php
                    $isChecked = isset($repartitions_map[$usr->get_user()]);
                    if (!empty($templateId) && isset($template) && $usr->is_in_Items($templateId, $usr->user)) {
                        $isChecked = true;
                    }
                    ?>

                    <input type="checkbox" name="c[<?= $usr->get_user() ?>]" value="<?= $usr->get_user() ?>"
                        id="<?php echo $usr->getUserInfo() ?>_userCheckbox" <?php echo $isChecked ? "checked" : ""; ?>>
                    <span class="text-input" style="color: yellow; font-weight: bold;">
                        <?php echo $usr->getUserInfo() ?>
                    </span>
                    <fieldset>
                        <legend class="legend" style="color: yellow; font-weight: bold;">Weight</legend>
                        <input type="number" name="w[<?= $usr->get_user(); ?>]" onchange="calculateAmounts()"
                            id="userWeight" min="0" max="50" <?php
                            if (isset($template)) {
                                if ($usr->is_in_Items($template->get_id(), $usr->user)) {
                                    echo "value=" . $usr->get_weight_by_user($template->get_id());
                                }
                            } else {
                                echo "value=" . (isset($repartitions_map[$usr->get_user()]) ? $repartitions_map[$usr->get_user()]->weight : '0');
                            }
                            ?>>
                    </fieldset>
                    <fieldset>
                        <legend class="legend" style="color: yellow; font-weight: bold;">Amount</legend>
                        <?php $amount = $usr->get_dette(isset($operation) ? $operation->get_id() : null, $usr); ?>
                        <?php if ($amount != 0): ?>
                            <input type="number" step="0.01" id="<?= $usr->get_user() ?>_amount" value="<?php echo $amount ?>"
                                onchange="calculateAmounts()" hidden>
                        <?php else: ?>
                            <input type="number" step="0.01" id="<?= $usr->get_user() ?>_amount" value=""
                                onchange="calculateAmounts()">
                        <?php endif; ?>

                    </fieldset>
                </div>
            <?php } ?>

            <p>Add a new repartition template</p>
            <div class="save-template">
                <input type="checkbox" name="save_template" id="save"> <span
                    style="color: yellow; font-weight: bold;">Save this
                    template</span>
                <fieldset>
                    <legend>Name</legend>
                    <input type="text" name="template_name" id="savename" placeholder="Name">
                </fieldset>
            </div>
            <input type="submit" value="<?php echo 'Submit'; ?>">
        </form>
        <?php
        if ($action === 'edit' || $action === 'edit_expense') {
            echo '<div class="deleteContainer">';

            echo '<button class="delete-btn" style="background-color: blue; color: white;">';
            echo '<a href="/prwb_2223_c03/Operation/delete_confirm/' . $_GET['param1'] . '" style="text-decoration: none; color: white;">DELETE</a>';
            echo '</button>';
            echo '</div>';
        }
        ?>
    </div>
</body>

</html>