<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Template </title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body>
    <?php include 'menu.php' ?>
    <div class="delete_template">
        <div class="delete_template_container">
            <h1>Are you sure ?</h1>
            <p>Do you really want to delete template <b> "<?= $template->title?>" </b> and all of it's dependencies ? </p>

            <p>this process cannot be undone.</p>
            <form action="templates/delete_template/<?= $template->id ?>" class="delete_confirm" method="POST">
                <input type="submit" value="Cancel" name="submitted">
                <input id="<?= $template->id?>" type="submit" value="Delete" name="submitted">
            </form>
        </div> 
    </div>
</body>
</html>