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
        if (!$(this).is(":disabled")) {
            var val = parseFloat($(this).val()) || 0;
            sum += val;
        }
    });

    // Handle target - can be id, class, or jQuery object
    var $target = target instanceof jQuery ? target : $(target);
    if ($target.is("input")) {
        $target.val(sum);
    } else {
        $target.text(sum);
    }
    // console.info("Sum updated:", selector, sum);
}

/**
 *
 * @param {*} selector
 * @param {*} target
 * @param {*} min
 * @param {*} max
 * @returns
 */
function validateValues(selector, target, min, max) {
    // Handle selector - can be id, class, or jQuery object
    var $inputs = selector instanceof jQuery ? selector : $(selector);
    var total = 0;

    $inputs.each(function () {
        // console.info("Validating input:", $(this).attr("name"), $(this).val());
        var value = parseFloat($(this).val()) || 0;
        var $input = $(this);
        var $inputGroup =
            $input.closest(".input-group").length > 0
                ? $input.closest(".input-group")
                : $input;
        var $errorMsg = $inputGroup.next(".error-message");

        // Validate individual input against min/max if provided
        if (
            (typeof min !== "undefined" && value < min) ||
            (typeof max !== "undefined" && value > max)
        ) {
            $input.val(value < min ? min : max);
            value = value < min ? min : max;
            $input.addClass("is-invalid border-danger");
            if ($errorMsg.length === 0) {
                $inputGroup.after(
                    '<div class="error-message" style="color: red;">Nilai harus diantara ' +
                        min +
                        "-" +
                        max +
                        "!!</div>",
                );
            }
        } else {
            $input.removeClass("is-invalid border-danger");
            $errorMsg.remove();
        }
        total += value;
    });

    // Validate total against min/max if provided
    var isValid = true;
    if (typeof min !== "undefined" && total < min) {
        isValid = false;
        // console.warn("Total value", total, "is below minimum:", min);
    }
    if (typeof max !== "undefined" && total > max) {
        isValid = false;
        // console.warn("Total value", total, "exceeds maximum:", max);
    }

    // Handle target - can be id, class, or jQuery object
    var $targets = target instanceof jQuery ? target : $(target);

    $targets.each(function () {
        var $target = $(this);
        if (isValid) {
            $target
                .closest(".alert")
                .removeClass("alert-danger")
                .addClass("alert-success");
        } else {
            $target
                .closest(".alert")
                .removeClass("alert-success")
                .addClass("alert-danger");
            console.warn("Not valid sum for ", selector, ', total: ', total);
        }
        if ($target.is("input")) {
            $target.val(total.toFixed(5)).trigger("change");
        } else {
            $target.text(total.toFixed(5));
        }
    });

    return isValid;
}

// Validate individual percentage input on blur/focusout
$(document).on("blur focusout", ".validate-percentage", function () {
    var $input = $(this);
    var $inputGroup =
        $input.closest(".input-group").length > 0
            ? $input.closest(".input-group")
            : $input;
    var $errorMsg = $inputGroup.next(".error-message");

    // Remove validation styling and error message on blur
    $input.removeClass("is-invalid border-danger");
    $errorMsg.remove();
});
