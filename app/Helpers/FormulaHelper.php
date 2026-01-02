<?php

if(! function_exists('production_value')){
    function production_value($object_value, $premi_percentage)
    {
        return $premi_percentage * $object_value;
    }
}

if(! function_exists('premi_value')){
    function premi_value($production_value, $policy_fee)
    {
        return $production_value + $policy_fee;
    }
}

if(! function_exists('commision_bruto')){
    function commision_bruto($production_value, $commision_percentage)
    {
        return $commision_percentage * $production_value;
    }
}

if(! function_exists('tax_value')){
    function tax_value($commision_bruto, $tax_percentage)
    {
        return $tax_percentage * $commision_bruto;
    }
}

if(! function_exists('commision_netto')){
    function commision_netto($commision_bruto, $tax_value, $discount)
    {
        return $commision_bruto - $tax_value - $discount;
    }
}

if(! function_exists('client_pay')){
    function client_pay($premi, $discount)
    {
        return $premi - $discount;
    }
}

if(! function_exists('insurance_pay')){
    function insurance_pay($premi, $tax_value, $discount)
    {
        return $premi + $tax_value - $discount;
    }
}

if(! function_exists('calculate_payment')){
    function calculate_payment(
        $object_value,
        $premi_percentage,
        $policy_fee,
        $commision_percentage,
        $tax_percentage,
        $discount
    ) {
        $productionValue = production_value($object_value, $premi_percentage);
        $premiValue      = premi_value($productionValue, $policy_fee);
        $commisionBruto  = commision_bruto($productionValue, $commision_percentage);
        $taxValue        = tax_value($commisionBruto, $tax_percentage);
        $commisionNetto  = commision_netto($commisionBruto, $taxValue, $discount);
        $clientPay       = client_pay($premiValue, $discount);
        $insurancePay    = insurance_pay($premiValue, $taxValue, $discount);

        return [
            'production_value' => $productionValue,
            'premi_value'      => $premiValue,
            'commision_bruto'  => $commisionBruto,
            'tax_value'        => $taxValue,
            'commision_netto'  => $commisionNetto,
            'client_pay'       => $clientPay,
            'insurance_pay'    => $insurancePay,
        ];
    }
}