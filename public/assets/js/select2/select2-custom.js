"use strict";

// Example usage of initCustomSelect2
// HTML: <select id="example-select"
//              data-placeholder="Pilih atau tambah item baru"
//              data-new-form-id="#new-item-form"
//              data-new-name-attr="new_item_name"
//              data-existing-name-attr="existing_item_id"
//              data-existing-details-id="#existing-item-details">
// JS: initCustomSelect2("#example-select");
function initCustomSelect2(selector) {
    var $element = $(selector);

    // Read all config from data attributes
    var config = {
        placeholder: $element.data("placeholder") || "Pilih Item",
        newId: $element.data("new-id"),
        newName: $element.data("new-name"),
        existingId: $element.data("existing-id"),
        existingName: $element.data("existing-name"),
        allowClear: $element.data("allow-clear") !== false, // default true
        tags: $element.data("tags") !== false, // default true
        createTagText:
            $element.data("create-tag-text") || "(Klik untuk tambah data)",
    };

    console.log(config);

    $element
        .select2({
            placeholder: config.placeholder,
            allowClear: config.allowClear,
            tags: config.tags,
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
                        " <em class='text-primary'>" +
                            config.createTagText +
                            "</em>"
                    );
                }

                return $result;
            },
        })
        .on("select2:select", function (e) {
            var data = e.params.data;

            if (data.newTag) {
                // Show new form, hide existing details
                if (config.newId) {
                    $(config.newId).show();
                }
                if (config.newName) {
                    $element.attr("name", config.newName);
                }
                if (config.existingId) {
                    $(config.existingId).hide();
                }
            } else {
                // Show existing details, hide new form
                if (config.existingId) {
                    $(config.existingId).show();
                }
                if (config.existingName) {
                    $element.attr("name", config.existingName);
                }
                if (config.newId) {
                    $(config.newId).hide();
                }
            }
        })
        .on("select2:unselect", function (e) {
            console.log("Selection unselected");
            if (config.newId) {
                $(config.newId).hide();
            }
            if (config.existingName) {
                $element.attr("name", config.existingName);
            }
            if (config.existingId) {
                $(config.existingId).show();
            }
        });
}

setTimeout(function () {
    (function ($) {
        "use strict";

        $('.basic-select2').select2({
            placeholder: "Pilih Data",
            allowClear: true,
        });

        $(".select2-basic-single").each(function () {
            var $select = $(this);
            // var label =
            //     $select
            //         .closest(".col-md-12, .col-md-6")
            //         .find("label")
            //         .text()
            //         .trim() || "data";

            $select.select2({
                // placeholder: "Pilih " + label,
                placeholder: "Pilih ",
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
