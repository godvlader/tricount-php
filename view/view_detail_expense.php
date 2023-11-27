<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>Expenses </title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/view-detail.css" rel="stylesheet" type="text/css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

</head>

<body>
    <?php include 'menu.php' ?>
    <div class="cont">
        <input type="hidden" name="tricount_id" value="<?php echo $tricount->get_id(); ?>" hidden>
        <input type="hidden" name="operation" value="<?php echo $operation_data->get_id(); ?>" hidden>
        <p>
            <?php echo $tricount->get_title(); ?> >
            <?php echo $operation_data->getTitle() ?> <button id="edit" class="edit-btn">
                <a href="Operation/edit/<?php echo $operation_data->get_id() ?>"
                    style="text-decoration: none; color: black;">Edit</a>
            </button>
        <div class="view_expenses">
            <h2>
                <?php echo number_format($operation_data->getAmount(), 2) ?>
            </h2>
            <?php
            if ($participants["0"] === 0) {
                echo "<p>For me</p>";
            } else {
                echo "<p>For " . $participants["0"] . " participants, including me</p>";
            }
            ?>
            <p>Paid by
                <?php echo $usr ?>
            </p>
            <p>
                <?php echo $operation_data->getOperationDate() ?>
            </p>
            <?php echo $operation_data->getTitle() ?>
            <?php if (($participants["0"] === 0)) {
                echo ' ';
            } else
                echo '<table>
                <thead>
                    <tr>
                    <th>User</th>
                    <th>Debt</th>
                    </tr>
                </thead>
                <tbody>';
            if (!empty($operationUsers)) {
                foreach ($operationUsers as $user) {
                    echo '<tr>';
                    if ($participants["0"] === 0) {
                        echo 'solo';
                    } else if ($user->getUserId() == $operation_data->getInitiatorId()) {
                        echo '<td style="color:yellow"><b>' . $user->getFullName() . '</b></td>';
                        echo '<td style="color:yellow"><b>' . number_format($user->get_dette($operation_data->get_id()), 2) . '</b></td>';
                    } else {
                        echo '<td>' . $user->getFullName() . '</td>';
                        echo '<td>' . number_format($user->get_dette($operation_data->get_id()), 2) . '</td>';
                    }
                    echo '</tr>';
                }
            } else {
                echo "solo";
            }

            ?>
            </tbody>
            </table>
        </div>
        <?php
        if (!$operation_data->isFirstOperation($tricount->get_id())): ?>

            <form action="operation/next_expense" method="post">
                <input type="hidden" name="tricount_id" value="<?php echo $tricount->get_id(); ?>" hidden>
                <input type="hidden" name="operation" value="<?php echo $operation_data->get_id(); ?>" hidden>
                <input class="btn" type="submit" name="submit" value="Previous">
            </form>
        <?php endif; ?>
        <?php if (!$operation_data->isLastOperation($tricount->get_id())): ?>
            <form action="operation/next_expense" method="post">
                <input type="hidden" name="tricount_id" value="<?php echo $tricount->get_id(); ?>" hidden>
                <input type="hidden" name="operation" value="<?php echo $operation_data->get_id(); ?>" hidden>
                <input class="btn" type="submit" name="submit" value="Next">
            </form>
        <?php endif; ?>


    </div>


</body>

</html>