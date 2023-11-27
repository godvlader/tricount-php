<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>Templates </title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>
    <?php include 'menu.php' ?>
    <div class="view_templates">
        <?php echo $tricount->get_title() . " > templates"; ?>
        <a href="templates/edit_template/<?php echo $tricount->get_id(); ?>" id="addNewTemplate"> New template</a>

        <div class="templates_container_1">
            <table class="tab_templates">
                <?php if ($templates !== null): ?>
                    <?php foreach ($templates as $rt): ?>
                        <tr>
                            <th> <a href="templates/edit_template/<?php echo $tricount->get_id(); ?>/<?php echo $rt->get_id() ?>">
                                    <?= $rt->get_title(); ?></th></a>
                        </tr>
                        <tr>
                            <th class="info_templates">
                                <ul>
                                    <?php foreach ($items as $participe): ?>

                                        <!-- <?php echo '<pre>';
                                        print_r($templates);
                                        echo '</pre>'; ?>  -->
                                        <?php if ($participe !== null): ?>
                                            <?php foreach ($participe as $row): ?>
                                                <?php if ($row->get_rt() === $rt->get_id()): ?>

                                                    <li>
                                                        <?php echo $row->get_user_info(); ?>
                                                        <?php echo "(";
                                                        echo $row->get_weight_by_user($row->get_user(), $row->repartition_template);
                                                        echo "/";
                                                        echo $row->get_Sum_Weight();
                                                        echo ")"; ?>
                                                    </li>

                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </th>
                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>
                    <p> <strong>no template for now.</strong></p>
                <?php endif; ?>
            </table>
        </div>
    </div>

</body>

</html>