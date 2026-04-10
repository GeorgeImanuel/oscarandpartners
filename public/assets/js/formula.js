function production_value(object_value, premi_percentage, TJH, discount) {
    return premi_percentage * object_value + TJH - discount;
}

// Formula khusus untuk Endors: prorate berdasarkan jumlah hari
function production_value_endors(object_value, premi_percentage, TJH, discount, days) {
    var daysFactor = days / 365;
    return (premi_percentage * object_value * daysFactor) + TJH - discount;
}

function premi_value(production_value, policy_fee) {
    return production_value + policy_fee;
}

function commision_bruto(production_value, commision_percentage, discount) {
    return commision_percentage * (production_value + discount);
}

function tax_value(commision_bruto, tax_percentage) {
    return tax_percentage * commision_bruto;
}

function commision_netto(commision_bruto, tax_value, discount_agent) {
    return commision_bruto - tax_value - discount_agent;
}

function client_pay(premi_value, discount_agent) {
    return premi_value - discount_agent;
}

function insurance_pay(client_pay, commision_netto) {
    return client_pay - commision_netto;
}

function calculate_payment(
    object_value,
    premi_percentage,
    TJH,
    discount,
    policy_fee,
    commision_percentage,
    tax_percentage,
    discount_agent,
) {
    const productionValue = production_value(
        object_value,
        premi_percentage,
        TJH,
        discount,
    );
    const premiValue = premi_value(productionValue, policy_fee);
    const commisionBruto = commision_bruto(
        productionValue,
        commision_percentage,
        discount,
    );
    const taxValue = tax_value(commisionBruto, tax_percentage);
    const commisionNetto = commision_netto(
        commisionBruto,
        taxValue,
        discount_agent
    );
    const clientPay = client_pay(premiValue, discount_agent);
    const insurancePay = insurance_pay(clientPay, commisionNetto);

    return {
        production_value: productionValue,
        premi_value: premiValue,
        commision_bruto: commisionBruto,
        tax_value: taxValue,
        commision_netto: commisionNetto,
        client_pay: clientPay,
        insurance_pay: insurancePay,
    };
}

// Update total percentage display in real-time
function updatePercentageTotal(percentageClass, totalId, infoId) {
    var totalPercentage = 0;
    $(percentageClass).each(function () {
        if ($(this).closest(".item").is(":visible")) {
            var value = parseNumber($(this).val()) || 0;
            totalPercentage += value;
        }
    });
    $(totalId).text(totalPercentage.toFixed(2));

    if (infoId) {
        var infoDiv = $(infoId);
        if (totalPercentage > 100 || totalPercentage < 0) {
            infoDiv.attr("class", "alert alert-danger");
        } else {
            infoDiv.attr("class", "alert alert-success");
        }
    }

    return totalPercentage;
}
