// Format number to Rupiah
function formatRupiah(value) {
    var number = parseFloat(value);
    if (isNaN(number)) {
        number = 0;
    }
    var currency = window.currentCurrency || "Rp";
    var decimals = currency !== "Rp" ? 2 : 0;
    var separator = currency !== "Rp" ? "," : ".";
    var formattedNumber = number
        .toFixed(decimals)
        .replace(/\B(?=(\d{3})+(?!\d))/g, separator);
    return currency + " " + formattedNumber;
}

function updateCurrencyLabel(currencyCode) {
    // Default to IDR if empty
    var currency = currencyCode || "Rp";
    // Update all input group labels
    $(".currency-label").text(currency);
    // Update rupiah format in payment summary (below form)
    // Store currency code in global variable so formatRupiah function can access it
    window.currentCurrency = currency;
}

function sumInputValues(selector, target) {
    var sum = 0;

    // Handle selector - can be id, class, or jQuery object
    var $selectors = selector instanceof jQuery ? selector : $(selector);

    $selectors.each(function () {
        var val = parseFloat($(this).val()) || 0;
        sum += val;
    });

    // Handle target - can be id, class, or jQuery object
    var $target = target instanceof jQuery ? target : $(target);
    if ($target.is("input")) {
        $target.val(sum);
    } else {
        $target.text(sum);
    }
    console.log("Sum updated:", sum);
}
