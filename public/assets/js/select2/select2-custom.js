"use strict";

// Example usage of initCustomSelect2
// initCustomSelect2("#example-select", {
//     placeholder: "Pilih atau tambah item baru",
//     newFormId: "#new-item-form",
//     newNameAttr: "new_item_name",
//     existingNameAttr: "existing_item_id",
//     existingDetailsId: "#existing-item-details",
// });
function initCustomSelect2(selector, config) {
    var label =
        $(selector)
            .closest(".col-md-12, .col-md-6")
            .find("label")
            .text()
            .trim() || "data";

    $(selector)
        .select2({
            placeholder: config.placeholder || "Pilih " + label,
            allowClear: true,
            tags: true,
            createTag: function (params) {
                var term = $.trim(params.term);

                if (term === "") {
                    return null;
                }

                return {
                    id: term,
                    text: term,
                    newTag: true,
                };
            },
            templateResult: function (data) {
                var $result = $("<span></span>");
                $result.text(data.text);

                if (data.newTag) {
                    $result.append(
                        " <em class='text-primary'>(Klik untuk tambah data)</em>"
                    );
                }

                return $result;
            },
        })
        .on("select2:select", function (e) {
            var data = e.params.data;

            if (data.newTag) {
                // Show new form, hide existing details
                $(config.newFormId).show();
                $(selector).attr("name", config.newNameAttr);
                $(config.existingDetailsId).hide();
            } else {
                // Show existing details, hide new form
                $(config.existingDetailsId).show();
                $(selector).attr("name", config.existingNameAttr);
                $(config.newFormId).hide();
            }
        })
        .on("select2:unselect", function (e) {
            console.log("Selection unselected");
            $(config.newFormId).hide();
            $(selector).attr("name", config.existingNameAttr);
            $(config.existingDetailsId).show();
        });
}

setTimeout(function () {
    (function ($) {
        "use strict";
        $(".select2-basic-single").each(function () {
            var $select = $(this);
            var label =
                $select
                    .closest(".col-md-12, .col-md-6")
                    .find("label")
                    .text()
                    .trim() || "data";

            $select.select2({
                placeholder: "Pilih " + label,
                allowClear: true,
            });
        });

        // Single Search Select
        $(".js-example-basic-single").select2();
        $(".js-example-disabled-results").select2();

        // Multi Select
        // $(".js-example-basic-multiple").select2();

        // // With Placeholder
        // $(".js-example-placeholder-multiple").select2({
        //     placeholder: "Pilih Beberapa Opsi",
        // });

        // //Limited Numbers
        // $(".js-example-basic-multiple-limit").select2({
        //     maximumSelectionLength: 2,
        // });

        // //RTL Suppoort
        // $(".js-example-rtl").select2({
        //     dir: "rtl",
        // });
        // // Responsive width Search Select
        // $(".js-example-basic-hide-search").select2({
        //     minimumResultsForSearch: Infinity,
        // });
        // $(".js-example-disabled").select2({
        //     disabled: true,
        // });
        // $(".js-programmatic-enable").on("click", function () {
        //     $(".js-example-disabled").prop("disabled", false);
        // });
        // $(".js-programmatic-disable").on("click", function () {
        //     $(".js-example-disabled").prop("disabled", true);
        // });
    })(jQuery);
}, 350);
