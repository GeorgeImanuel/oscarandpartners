
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
 *       ...
 *       ...
 *       <button class="remove-item-btn"></button>
 *     </div>
 *     <div class="item item-1">
 *       ...
 *       ...
 *       <button class="remove-item-btn"></button>
 *     </div>
 *     <div class="item item-2">
 *       ...
 *       ...
 *       <button class="remove-item-btn"></button>
 *     </div>
 *     <div class="item item-2">
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
    const container = $(this).closest(".multiple-item-container").find(".item-list");
    const firstItem = container.find(itemClass + ":first");
    const hiddenItems = container.find(".item").filter(function () {
        return $(this).css("display") === "none";
    });
    const hiddenItemCount = hiddenItems.length;

    if (firstItem.length) {
        const newItem   = firstItem.clone();
        const itemCount = container.find(".item").length - hiddenItemCount + 1;
        newItem.show();
        // Clear the input fields inside the cloned item
        newItem.find(".form-control, .form-select").val("");
        newItem.find(".form-control").prop("required", true);
        // Update item text title
        newItem.find(".item-count").text(itemCount);
        newItem.attr("data-item_index", itemCount);
        newItem.addClass("item-" + itemCount);
        newItem.removeClass("no-print");
        
        // Remove select2 & Reinit the select2
        var selects = newItem.find("select");
        selects.each(function () {
            $(this).removeClass('select2-hidden-accessible');
            $(this).next('span.select2').remove();
            
            // Check if it's an insurance select
            if ($(this).hasClass('add-select2')) {
                // Initialize with custom select2 for adding new insurances
                var $select = $(this);
                var label = $select.siblings("label");
                var labelText = label.length ? label.text().replace(/\d+/, '').trim() : "Data";
                
                $select.select2({
                    placeholder: "Pilih " + labelText,
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
                            $result.append(" <em class='text-primary'>(Klik untuk tambah data)</em>");
                        }
                        return $result;
                    }
                });
            } else {
                // Default select2 initialization
                var label = $(this).siblings("label");
                var labelText = label.length ? "Pilih " + label.text() : "Pilih Data";
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
    }).then(result => {
        if (result.isConfirmed) {
            var $itemList = $(this).closest(".item-list");
            var $multipleItemContainer = $(this).closest(".multiple-item-container");
            
            if ($itemList.find(".item").length === 1) {
                return;
            } else {
                
                const container = $multipleItemContainer.find(".item-list");
                $(this).closest(".item").remove();

                const hiddenItems = container.find(".item").filter(function () {
                    return $(this).css("display") === "none";
                });
                const hiddenItemCount = hiddenItems.length;
                const items           = $itemList.find(".item");
                items.each(function (index) {
                    var itemCount = index - hiddenItemCount + 1;
                    $(this).find(".item-count").text(itemCount);
                    $(this).attr("data-item_index", itemCount);
                    $(this)
                        .removeClass(function (i, cls) {
                            return (cls.match(/(^|\s)item-\d+/g) || []).join(" ");
                        })
                        .addClass("item-" + itemCount);
                });
                
                // Show empty state if no visible items
                const visibleItems = container.find(".item").filter(function () {
                    return $(this).css("display") !== "none";
                });
                if (visibleItems.length === 0) {
                    $multipleItemContainer.find(".empty-state").show();
                }
            }
        }
    });
});
