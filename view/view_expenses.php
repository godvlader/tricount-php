<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>Expenses </title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/expenses.css" rel="stylesheet" type="text/css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>

</head>

<body>
<script>
/**
 * (par montant, par date, par initiateur et par titre, de façon croissante et décroissante).
 * 
 * arrow up : <i class="fa-solid fa-sort-up"></i>
 * 
 * arrow down : <i class="fa-solid fa-sort-down"></i>
 */
const expenses = <?= $expenses_json ?>;
let sortColumn = 'date';
let sortAscending = true;

function displayExpenses(){
    let data_items ="";
    for (let exp of expenses){
        data_items += "<a href='Operation/detail_expense/" + exp.id + "'>";
        data_items += "<div class='data-card'>";
        data_items += "<h2 class='title'>"+exp.title + "</h2>";
        data_items += "<input type='hidden' name ='operationId' value='"+exp.id+"'>";
        data_items += "<p class='amount'>"+ exp.amount + "€</p>";
        data_items += "<p class='initiator'>Paid by "+ exp.initiator + "</p>";
        data_items += "<p class='date'>"+ exp.operation_date + "</p>";
        data_items += "</div>";
        data_items += "</a>";
    }
    $('.data-item').html(data_items);
}

function sortExpenses(){
    expenses.sort(function (a,b) {
        if (sortColumn === 'date') {
            let dateA = new Date(a.operation_date);
            let dateB = new Date(b.operation_date);
            if (dateA < dateB) {
                return sortAscending ? -1 : 1;
            } else if (dateA > dateB) {
                return sortAscending ? 1 : -1;
            } else {
                return 0;
            }
        } else {
            if (a[sortColumn] < b[sortColumn])
                return sortAscending ? -1 : 1;
            if (a[sortColumn] > b[sortColumn])
                return sortAscending ? 1 : -1;
            return 0;
        }
    });
}


function sort(value){
    let [column, order] = value.split('-');
    console.log([column, order] + " trsr");
    if(order === "desc"){
        sortAscending = false;
    }else{
        sortAscending = true;
    }
    sortColumn = column;
    sortExpenses();
    displayExpenses();
}



function showSelectSection() {
    let html = "<select id='sort-select'>" +
        "<option value='title-asc'> by title &#xf0de;</option>" +
        "<option value='title-desc'> by title &#xf0dd;</option>" +
        "<option value='date-asc'> by date &#xf0de;</option>" +
        "<option value='date-desc'> by date &#xf0dd;</option>" +
        "<option value='initiator-asc'> by creator &#xf0de;</option>" +
        "<option value='initiator-desc'> by creator &#xf0dd;</option>" +
        "<option value='amount-asc'> by amount &#xf0de;</option>" +
        "<option value='amount-desc'> by amount &#xf0dd;</option>" +
        "</select>";
    $('#js_Select').html(html);


    // selection du choix par défaut
    $('#sort-select').val('date-desc');

}

$(function(){
    showSelectSection();
    sort(sortColumn);
    displayExpenses();
    $('#sort-select').on('change', function() {
        let value = $(this).val();
        sort(value);
    });
});
</script>
    <?php include 'menu.php' ?>
    
    <div id="js_Select" >
    </div>
    <div class="cont">
        <div class="view_expenses">
            <button class="edit-btn">
                <a href="tricount/edit/<?= $tricount->get_id() ?>" style="text-decoration: none; color: black;">Edit</a>
            </button>
            <p>
                <?php echo $tricount->get_title(); ?> > Expenses
            </p>
            <?php
            $total_usr = 0;
            if (!empty($amounts)) {
                foreach ($amounts as $operations) {
                    if (!is_null($operations)) {
                        foreach ($operations as $operation) {
                            if ($user->is_in_operation($operation->id) || $user === $operation->initiator) {
                                $total_usr += $operation->total_by_user($user->getUserId(), $operation->get_id());
                            }
                        }
                    }

                }
            } else {
                $total_usr = 0;
            }
            ?>
            <ul>
                <div class="container">
                    <ul class="data-list">
                        <?php
                        if (!empty($amounts)) {
                            foreach ($amounts as $amount):
                                if (!empty($amount)) {
                                    echo '<a href="/prwb_2223_c03/Operation/balance/' . $tricount->get_id() . '">';
                                    echo '<button class="view-balance-button">';
                                    echo '<i class="fa fa-exchange"></i>View Balance';
                                    echo '</button>';
                                    echo '</a>';
                                    echo '<div class="expenses_json"> </div>';
                                    echo '<li class="data-item">';
                                    foreach ($amount as $am):
                                        //$id = $am->initiator;
                                        $id_expense = $am->id;
                                        echo '<a href="Operation/detail_expense/' . $id_expense . '">
                                            <div class="data-card">
                                                    <h2 class="title">' . $am->title . '</h2>
                                                    <input type="hidden" name="operationId" value="$id_expense">
                                                    <p class="amount">' . $am->amount . ' €</p>
                                                    <p class="initiator">Paid by ' . $am->getInitiator() . '</p>
                                                    <p class="date">' . $am->operation_date . '</p>
                                            </div>
                                        </a>';
                                    endforeach;
                                    echo '</li>';
                                    echo '<div class="totals">
                                        <div class="mytot">
                                            <p>MY TOTAL <br> ' . number_format($total_usr, 2) . ' €</p>
                                            </div>
                                            <div class="add-btn">
                                                <a href="/prwb_2223_c03/Operation/add/' . $tricount->get_id() . '">
                                                    <button id="button" class="add-button">+
                                                    </button>
                                                </a>
                                            </div>
                                            <div class="exp">
                                                <p>TOTAL EXPENSES <br> ' . number_format($totalExp["0"], 2) . ' €</p>
                                            </div>
                                        </div>';
                                }
                                if (empty($amount) && ($participants > 0) && $totalExp["0"] === null) {
                                    $totalExp["0"] = 0;
                                    echo "<h1 style='text-align:center;'>this is empty :(</h1>";
                                    echo '<a href="/prwb_2223_c03/Operation/add/' . $tricount->get_id() . '">';
                                    echo '<button class="view-balance-button">';
                                    echo 'ADD AN EXPENSE';
                                    echo '</button>';
                                    echo '</a>';
                                    echo '<div class="totals">
                                            <div class="mytot">
                                            <p>MY TOTAL <br> ' . number_format($total_usr, 2) . '  €</p>
                                        </div>
                                            <div class="exp">';
                                    echo '<p>TOTAL EXPENSES <br> ' . number_format($totalExp["0"], 2) . '  €</p>';
                                    echo '</div>
                                        </div>';
                                }
                                if (empty($amount) && ($participants == 0) && $totalExp["0"] === null) {
                                    $totalExp["0"] = 0;
                                    echo "<h1 style='text-align:center;'>you are alone :(</h1>";
                                    echo '<a href="tricount/edit/' . $tricount->get_id() . '">';
                                    echo '<button class="view-balance-button">';
                                    echo 'ADD FRIENDS';
                                    echo '</button>';
                                    echo '</a>';
                                    echo '<div class="totals">
                                            <div class="mytot">
                                                <p>MY TOTAL <br>' . number_format($total_usr, 2) . ' €</p>
                                            </div>
                                            <div class="add-btn">
                                                <a href="/prwb_2223_c03/Operation/add/' . $tricount->get_id() . '">
                                                    <button class="add-button">+
                                                    </button>
                                                </a>
                                            </div>
                                            <div class="exp">
                                            echo <p>TOTAL EXPENSES <br> ' . number_format($totalExp["0"], 2) . ' €</p>;
                                        </div>
                                        </div>';
                                }
                            endforeach;
                        }
                        ?>
                    </ul>

                </div>
            </ul>
        </div>
    </div>
    </div>


</body>

</html>