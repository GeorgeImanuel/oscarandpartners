// Robust parsing helper that accepts formatted numbers like "1.234,56" or "1,234.56"
function parseNumber(value) {
    if (value === null || value === undefined || value === '') return 0;
    if (typeof value === 'number') return value;

    var s = String(value).trim();
    // Remove currency symbols, spaces and non numeric except .,,-
    s = s.replace(/[^0-9.,-]/g, '');

    var lastDot = s.lastIndexOf('.');
    var lastComma = s.lastIndexOf(',');

    if (lastDot > -1 && lastComma > -1) {
        // Both present: the rightmost separator is the decimal separator
        if (lastComma > lastDot) {
            // Indonesian style: dots are thousands, comma is decimal
            s = s.replace(/\./g, '').replace(/,/g, '.');
        } else {
            // US style: commas are thousands, dot is decimal
            s = s.replace(/,/g, '');
        }
    } else if (lastComma > -1) {
        // Only comma present: treat comma as decimal separator
        s = s.replace(/,/g, '.');
    } else {
        // Only dot or no separator present: keep as-is (dots are decimal)
    }

    var n = parseFloat(s);
    return isNaN(n) ? 0 : n;
}

// Format number to Rupiah
function formatRupiah(value) {
    var number = parseNumber(value);
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
            var val = parseNumber($(this).val()) || 0;
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
 * @param {*} decimals - number of decimal places (default: 5)
 * @returns
 */
function validateValues(selector, target, min, max, decimals) {
    // Default decimals to 5 if not provided
    if (typeof decimals === "undefined") {
        decimals = 5;
    }

    // Handle selector - can be id, class, or jQuery object
    var $inputs = selector instanceof jQuery ? selector : $(selector);
    var total = 0;

    $inputs.each(function () {
        // console.info("Validating input:", $(this).attr("name"), $(this).val());
        var value = parseNumber($(this).val()) || 0;
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
            console.warn("Not valid sum for ", selector, ", total: ", total);
        }
        if ($target.is("input")) {
            $target.val(total.toFixed(decimals)).trigger("change");
        } else {
            $target.text(total.toFixed(decimals));
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

window.toggleNegativeWarning = function (element) {
    var val = parseNumber($(element).val()) || 0;

    // --- LOGIC PENCARIAN ELEMEN WARNING YANG LEBIH KUAT ---
    // Coba cari warning tepat di sebelah input (jika tidak ada input-group)
    var $warningMsg = $(element).next(".negative-warning");

    // Jika tidak ketemu, coba cari di sebelah parentnya (jika ada input-group)
    if ($warningMsg.length === 0) {
        $warningMsg = $(element).parent().next(".negative-warning");
    }

    // Jika masih tidak ketemu, cari di dalam container kolom (col-md-...)
    if ($warningMsg.length === 0) {
        $warningMsg = $(element)
            .closest('div[class^="col-"]')
            .find(".negative-warning");
    }
    // -------------------------------------------------------

    if (val < 0) {
        // Jika Negatif: Tampilkan & beri warna
        $warningMsg.stop().slideDown(200);
        $(element).addClass("border-warning text-warning fw-bold");
    } else {
        // Jika Aman: Sembunyikan
        $warningMsg.stop().slideUp(200);
        $(element).removeClass("border-warning text-warning fw-bold");
    }
};
