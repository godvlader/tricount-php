$(document).ready(function() {
    // Calculate the amounts when the page is loaded
    calculateAmounts();

    // Add event listeners to the input fields
    $("input[type='number'], input[type='checkbox']").change(function() {
        calculateAmounts();
        console.log("Input ou CheckBox a changé");
    });
    console.log("LISTENERS",$("input[type='number'], input[type='checkbox']"));
});

function calculateAmounts() {
    // Récupère le montant total
    /*La fonction commence par récupérer le montant total à payer
    en utilisant la méthode val() de jQuery
    pour obtenir la valeur de l'élément HTML avec l'id amount.*/
    var totalAmount = parseFloat($("#amount").val());
    console.log("MONTANT TOTAL",totalAmount);

    // Récupère le poids de chaque user et calcule le poids total
    var weights = {};
    var totalWeight = 0;
    /*Récupère le poids de chaque utilisateur et calcule le poids total
    en parcourant les champs d'entrée numériques qui ont un id
    se terminant par _weight.
    Pour chaque champ, elle récupère l'id de l'utilisateur,
    le poids qu'il a entré,
    stocke le poids dans un objet weights avec l'id de l'utilisateur comme clé
    et ajoute le poids au poids total.*/
    $("input[type='number'][id$='_weight']").each(function() {
        var userId = $(this).attr("id").replace("_weight", "");
        var weight = parseFloat($(this).val());
        weights[userId] = weight;
        totalWeight += weight;
    });
    console.log("TOTAL WEIGHTS :",totalWeight);
    console.log("WEIGHTS",$("input[type='number'][id$='_weight']"));


    // Calculer les montants à payer pour chaque utilisateur
    $("input[type='checkbox']").each(function() {
        var user = $(this).val();
        var isChecked = $(this).is(":checked");
        console.log("EST-COCHÉ :", isChecked);
        var weight = parseFloat($("#" + user).val());
        console.log("POIDS :", weight);
        var amount = 0;
        if (isChecked && weight > 0) {
            var amount = (weight / totalWeight) * totalAmount;
        }
        $("#" + user + "_amount").val(amount.toFixed(2));
    });

    // Gérer les cases à cocher qui changent de poids
    $(".checks input[type='number'] ").change(function() {
        var weight = parseFloat($(this).val());
        var checkbox = $(this).siblings("input[type='checkbox']");
        if (weight === 0) {
            checkbox.prop("checked", false);
        } else {
            checkbox.prop("checked", true);
        }
    });

    // Calculate the total amount owed by each user
    $("input[type='number'][id$='_dette']").each(function() {
        var userId = $(this).attr("id").replace("_dette", "");
        var totalAmount = 0;
        $("input[type='number'][id$='_amount']").each(function() {
            var paidByUserId = $(this).attr("id").replace("_amount", "");
            var amount = parseFloat($(this).val());
            if (paidByUserId == userId) {
                totalAmount += amount;
            }
        });
        $(this).val(totalAmount);
        console.log("DETTE",$(this).val(totalAmount));

    });
}









