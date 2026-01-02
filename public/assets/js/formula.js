function production_value(object_value, premi_percentage) {
    return premi_percentage * object_value;
}

function premi_value(production_value, policy_fee) {
    return production_value + policy_fee;
}

function commision_bruto(production_value, commision_percentage) {
    return commision_percentage * production_value;
}

function tax_value(commision_bruto, tax_percentage) {
    return tax_percentage * commision_bruto;
}

function commision_netto(commision_bruto, tax_value, discount) {
    return commision_bruto - tax_value - discount;
}

function client_pay(premi, discount) {
    return premi - discount;
}

function insurance_pay(premi, tax_value, discount) {
    return premi + tax_value - discount;
}

function calculate_payment(
    object_value,
    premi_percentage,
    policy_fee,
    commision_percentage,
    tax_percentage,
    discount
) {
    const productionValue = production_value(object_value, premi_percentage);
    const premiValue = premi_value(productionValue, policy_fee);
    const commisionBruto = commision_bruto(productionValue, commision_percentage);
    const taxValue = tax_value(commisionBruto, tax_percentage);
    const commisionNetto = commision_netto(commisionBruto, taxValue, discount);
    const clientPay = client_pay(premiValue, discount);
    const insurancePay = insurance_pay(premiValue, taxValue, discount);

    return {
        production_value: productionValue,
        premi_value: premiValue,
        commision_bruto: commisionBruto,
        tax_value: taxValue,
        commision_netto: commisionNetto,
        client_pay: clientPay,
        insurance_pay: insurancePay
    };
}