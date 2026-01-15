(function ($) {
    "use strict";
    var fomr_wizard_two = {
        init: function () {
            var navListItems = $("div.setup-panel div a"),
                allWells = $(".setup-content"),
                allNextBtn = $(".nextBtn");
            allWells.hide();
            navListItems.click(function (e) {
                e.preventDefault();
                var $target = $($(this).attr("href")),
                    $item = $(this);
                if (!$item.hasClass("disabled")) {
                    navListItems
                        .removeClass("btn btn-primary")
                        .addClass("btn btn-light");
                    $item.removeClass("btn btn-light");
                    $item.addClass("btn btn-primary");
                    allWells.hide();
                    $target.show();
                    $target.find("input:eq(0)").focus();
                }
            }),
                allNextBtn.click(function () {
                    var curStep = $(this).closest(".setup-content"),
                        curStepBtn = curStep.attr("id"),
                        nextStepWizard = $(
                            'div.setup-panel div a[href="#' + curStepBtn + '"]'
                        )
                            .parent()
                            .next()
                            .children("a"),
                        curInputs = curStep.find(
                            "input[type='text'],input[type='url']"
                        ),
                        isValid = true;
                    $(".form-group").removeClass("has-error");
                    for (var i = 0; i < curInputs.length; i++) {
                        if (!curInputs[i].validity.valid) {
                            isValid = false;
                            $(curInputs[i])
                                .closest(".form-group")
                                .addClass("has-error");
                        }
                    }
                    if (isValid)
                        nextStepWizard.removeAttr("disabled").trigger("click");
                }),
                $("div.setup-panel div a.btn-primary").trigger("click");
        },
    };
    (function ($) {
        "use strict";
        fomr_wizard_two.init();
    })(jQuery);
})(jQuery);

//TODO: Update this so the next button no need to define the next destination
//TODO: add the validation on the next button to
function changeStep(id) {
    const targetButton = document.getElementById(id);
    if (targetButton) {
        targetButton.click(); // Programmatically trigger the target button's click event
    } else {
        console.error(`Button with selector "${targetSelector}" not found.`);
    }
}

/**
 * Navigates to the previous step in a form wizard.
 * Finds the current step button with the class 'btn-primary' and triggers a click event on the previous step button.
 * Logs an error if the current or previous step button is not found.
 */
function prevStep() {
    // console.log("previousStep");
    const currentStep = document.querySelector(".stepwizard-row .btn-primary");
    if (currentStep) {
        const prevStep =
            currentStep.parentElement.previousElementSibling.querySelector("a");
        if (prevStep) {
            prevStep.click(); // Programmatically trigger the previous step's click event
        } else {
            console.error("Previous step button not found.");
        }
    } else {
        console.error("Current step button not found.");
    }
}

/**
 * Navigates to the next step in a form wizard.
 * Finds the current step button with the class 'btn-primary' and triggers a click event on the next step button.
 * Logs an error if the current or previous step button is not found.
 */
function nextStep() {
    const currentStep = document.querySelector(".stepwizard-row .btn-primary");
    if (currentStep) {
        const nextStep =
            currentStep.parentElement.nextElementSibling.querySelector("a");
        if (nextStep) {
            nextStep.click(); // Programmatically trigger the next step's click event
        } else {
            console.error("Next step button not found.");
        }
    } else {
        console.error("Current step button not found.");
    }

    // Call the function initially and also on step change
    hidePrevButtonIfFirstStep();
    hideNextButtonIfLastStep();
}

document.querySelectorAll(".stepwizard-next").forEach(function (button) {
    button.addEventListener("click", function () {
        nextStep();
    });
});

document.querySelectorAll(".stepwizard-prev").forEach(function (button) {
    button.addEventListener("click", function () {
        prevStep();
    });
});

/**
 * Hides the previous step button if the current step is the first element.
 */
function hidePrevButtonIfFirstStep() {
    const currentStep = document.querySelector(".stepwizard-row .btn-primary");
    const prevButton = document.querySelector(".stepwizard-prev");
    if (currentStep && prevButton) {
        const isFirstStep = !currentStep.parentElement.previousElementSibling;
        if (isFirstStep) {
            prevButton.style.display = "none";
        } else {
            prevButton.style.display = "inline-block";
        }
    }
}

/**
 * Hides the next button and shows the submit button if the current step is the last element.
 */
function hideNextButtonIfLastStep() {
    const currentStep = document.querySelector(".stepwizard-row .btn-primary");
    const nextButton = document.querySelector(".stepwizard-next");
    const submitButton = document.querySelector(".stepwizard-submit");
    if (currentStep && nextButton && submitButton) {
        const isLastStep = !currentStep.parentElement.nextElementSibling;
        if (isLastStep) {
            nextButton.style.display = "none";
            submitButton.style.display = "inline-block";
        } else {
            nextButton.style.display = "inline-block";
            submitButton.style.display = "none";
        }
    }
}

/**
 * Change title every tab change
 */
// document.querySelectorAll(".stepwizard-step a").forEach(function (link) {
//     link.addEventListener("click", hidePrevButtonIfFirstStep);
//     link.addEventListener("click", hideNextButtonIfLastStep);

//     link.addEventListener("click", function (event) {
//         event.preventDefault(); // Prevent default link behavior
//         const titleElement = document.getElementById("tab-title");
//         const linkText = link.textContent.trim(); // Get the text inside the link
//         const paragraphText = link.nextElementSibling.textContent.trim(); // Get the text of the paragraph after the link

//         // Update the title
//         titleElement.textContent = `${linkText}. ${paragraphText}`;
//     });
// });
