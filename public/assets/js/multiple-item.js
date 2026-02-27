/**
 * This script handles adding and removing multiple items dynamically within a container.
 * Its important to include the first item inside .item-list as a template for cloning.
 * Multiple item template can be used simultaneously by specifying data-target attribute on the .add-item-btn button.
 *
 * It assumes the following HTML structure:
 * <div class="multiple-item-container">
 *   <button class="add-item-btn" data-target="item-1"></button>
 *   <button class="add-item-btn" data-target="item-2"></button>
 *   <div class="item-list">
 *     <div class="item item-1">
 *        Title Item <span class="item-count">1</span>
 *       ...
 *       ...
 *       <button class="remove-item-btn"></button>
 *     </div>
 *     <div class="item item-1">
 *       Title Item <span class="item-count">1</span
 *       ...
 *       ...
 *       <button class="remove-item-btn"></button>
 *     </div>
 *     <div class="item item-2">
 *        Title Item 2 <span class="item-count">1</span>
 *       ...
 *       ...
 *       <button class="remove-item-btn"></button>
 *     </div>
 *     <div class="item item-2">
 *       Title Item <span class="item-count">1</span
 *       ...
 *       ...
 *       <button class="remove-item-btn"></button>
 *     </div>
 *   </div>
 * </div>
 */

/**
 * Add new item to the container
 */
$(document).on("click", ".add-item-btn", function () {
    var itemClass = ".item";
    if ($(this).data("target")) {
        itemClass = ".item." + $(this).data("target");
    }
    const container = $(this)
        .closest(".multiple-item-container")
        .find(".item-list:first");
    const firstItem = container.find(itemClass + ":first");
    const hiddenItems = container.find(".item:first").filter(function () {
        return $(this).css("display") === "none";
    });
    const hiddenItemCount = hiddenItems.length;

    if (firstItem.length) {
        const newItem = firstItem.clone();
        const itemCount =
            container.find(".item." + $(this).data("target")).length -
            hiddenItemCount +
            1;
        
        newItem.show();
        // Clear the input fields inside the cloned item
        newItem.find(".form-control, .form-select").val("");
        newItem.find("input[type='checkbox'], input[type='radio']").prop("checked", false);
        newItem.find(".required").each(function () {
            $(this).prop("required", true).prop("disabled", false);
        });
        // Update item text title
        newItem.find(".item-count").text(itemCount);
        newItem.attr("data-item-index", itemCount);
        newItem.addClass("item-" + itemCount);
        newItem.removeClass("no-print");

        // Remove select2 & Reinit the select2
        var selects = newItem.find("select");
        selects.each(function () {
            $(this).removeClass("select2-hidden-accessible");
            $(this).next("span.select2").remove();

            // Check if it has add-select2 or create-select2 class
            if ($(this).hasClass("add-select2")) {
                console.log('Initializing add-select2 for new item');
                // Get placeholder from data attribute or label
                var placeholder = $(this).data("placeholder");
                if (!placeholder) {
                    var label = $(this).siblings("label");
                    placeholder = label.length
                        ? "Pilih " + label.text().replace("*", "").trim()
                        : "Pilih Data";
                }
                $(this).select2({
                    placeholder: placeholder,
                    allowClear: true,
                });
            } else if ($(this).hasClass("create-select2")) {
                console.log('Initializing create-select2 for new item');

                initCustomSelect2($(this));
            } else {
                // Default select2 initialization
                var label = $(this).siblings("label");
                var labelText = label.length
                    ? "Pilih " + label.text().replace("*", "").trim()
                    : "Pilih Data";
                $(this).select2({
                    placeholder: labelText,
                    allowClear: true,
                });
            }
        });

        // Attach remove functionality
        newItem.find(".remove-item-btn").show();
        // Append to container
        container.append(newItem);

        // Hide empty state alert
        $(this).closest(".multiple-item-container").find(".empty-state").hide();
    }
});

/**
 * Remove item when "Remove" button is clicked
 */
$(document).on("click", ".remove-item-btn", function () {
    Swal.fire({
        title: "Anda yakin ingin menghapus?",
        text: "Data akan dihapus secara permanen.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yakin",
        confirmButtonColor: "#ef486a",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            var $itemList = $(this).closest(".item-list");
            var $multipleItemContainer = $(this).closest(
                ".multiple-item-container",
            );
            var itemClass = $(this).closest(".item").attr("class");
            
            if ($itemList.find(".item").length === 1) {
                return;
            } else {
                $(this).closest(".item").remove();

                const hiddenItems = $itemList
                    .find("." + itemClass)
                    .filter(function () {
                        return $(this).css("display") === "none";
                    });
                const hiddenItemCount = hiddenItems.length;
                const items = $itemList.find(".item");
                items.each(function (index) {
                    var itemCount = index - hiddenItemCount + 1;
                    $(this).find(".item-count").text(itemCount);
                    $(this).attr("data-item-index", itemCount);
                    $(this).removeClass(function (i, cls) {
                        return (cls.match(/(^|\s)item-\d+/g) || []).join(" ");
                    });
                    // .addClass("item-" + itemCount)
                });

                // Show empty state if no visible items
                const visibleItems = $itemList
                    .find(".item")
                    .filter(function () {
                        return $(this).css("display") !== "none";
                    });
                if (visibleItems.length === 0) {
                    $multipleItemContainer.find(".empty-state").show();
                }
            }
        }
    });
});

window.renameInputNameByIndex = function ($items) {
    $items.each(function () {
        var itemIndex = $(this).data("item-index");
        console.log("Renaming inputs for item index: ", itemIndex);
        $(this)
            .find("input, select, textarea")
            .each(function () {
                var originalName = $(this).attr("name");
                if (originalName) {
                    // Check if first [] already has an index like [0], [1], etc.
                    var hasIndex = /\[\d+\]/.test(originalName);

                    if (hasIndex) {
                        // Replace existing [number] with new index
                        var newName = originalName.replace(
                            /\[\d+\]/,
                            "[" + itemIndex + "]",
                        );
                        $(this).attr("name", newName);
                    } else {
                        // Replace first [] with [index]
                        var newName = originalName.replace(
                            /\[\]/,
                            "[" + itemIndex + "]",
                        );
                        $(this).attr("name", newName);
                    }
                }
            });
    });
};
