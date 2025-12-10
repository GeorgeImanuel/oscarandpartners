/**
 * Form Validation
 * Handles form validation with custom error messages and field-specific validation
 */
(function () {
    'use strict';

    /**
     * Initialize form validation for margin review forms
     * Applies custom Bootstrap validation styles and error messages
     * @param {Object} options - Configuration options for validation
     * @param {Function} options.customValidator - Custom validation function that receives (form, fields) and returns {isValid: boolean, errors: Array}
     * @param {Function} options.onSubmit - Callback function to execute on successful validation (receives form element) - replaces default form submission
     * @param {Function} options.onSuccess - Callback function to execute on successful validation (receives form element) - executed before confirmation
     * @param {Function} options.onError - Callback function to execute on validation failure (receives form element and errors array)
     * @param {string} options.formSelector - Custom selector for forms (default: '.needs-validation')
     * @param {string} options.confirmTitle - Title for confirmation dialog (default: 'Anda Yakin?')
     * @param {string} options.confirmMessage - Message for confirmation dialog (default: 'Pastikan semua data sudah benar!')
     * @param {boolean} options.showConfirmDialog - Whether to show confirmation dialog (default: true)
     */
    function initializeFormValidation(options) {
        // Default options
        options = options || {};
        var formSelector = options.formSelector || '.needs-validation';
        var customValidator = options.customValidator || null;
        var onSubmit = options.onSubmit || function(form) {
            form.submit();
        };
        var onSuccess = options.onSuccess || null;
        var onError = options.onError || null;
        var confirmTitle = options.confirmTitle || 'Anda Yakin?';
        var confirmMessage = options.confirmMessage || 'Pastikan semua data sudah benar!';
        var showConfirmDialog = options.showConfirmDialog !== undefined ? options.showConfirmDialog : true;

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll(formSelector);

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                event.stopPropagation();
                var isValid = true;
                var customErrors = [];

                // Clear previous error messages
                form.querySelectorAll('.error-message').forEach(function (error) {
                    error.remove();
                });

                // Validate each field (built-in HTML5 validation)
                var fields = form.querySelectorAll('input, select, textarea');
                fields.forEach(function (field) {
                    if (!field.checkValidity()) {
                        isValid = false;

                        // Add custom error message
                        var errorMessage = document.createElement('div');
                        errorMessage.className = 'error-message';
                        errorMessage.style.color = 'red';
                        errorMessage.textContent = field.validationMessage;
                        field.insertAdjacentElement('afterend', errorMessage);
                    }

                    // Remove error message on input change
                    field.addEventListener('input', function () {
                        if (field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')) {
                            field.nextElementSibling.remove();
                        }
                    });
                });

                // Run custom validation if provided
                if (customValidator && typeof customValidator === 'function') {
                    var customValidationResult = customValidator(form, fields);
                    if (customValidationResult && customValidationResult.isValid === false) {
                        isValid = false;
                        customErrors = customValidationResult.errors || [];
                        
                        // Display custom validation errors
                        customErrors.forEach(function (error) {
                            if (error.field && error.message) {
                                var field = form.querySelector(error.field);
                                if (field) {
                                    var errorMessage = document.createElement('div');
                                    errorMessage.className = 'error-message';
                                    errorMessage.style.color = 'red';
                                    errorMessage.textContent = error.message;
                                    field.insertAdjacentElement('afterend', errorMessage);
                                }
                            }
                        });
                    }
                }

                form.classList.add('was-validated');

                // Handle validation result
                if (isValid) {
                    // Execute success callback if provided
                    if (onSuccess && typeof onSuccess === 'function') {
                        onSuccess(form);
                    }

                    // Show confirmation dialog or submit directly
                    if (showConfirmDialog && typeof alertConfirm === 'function') {
                        alertConfirm(confirmTitle, confirmMessage, function() {
                            // Execute submit callback if provided
                            if (onSubmit && typeof onSubmit === 'function') {
                                onSubmit(form);
                            }
                        });
                    } else {
                        // No confirmation dialog, submit directly
                        if (onSubmit && typeof onSubmit === 'function') {
                            onSubmit(form);
                        }
                    }
                } else {
                    // Display error messages
                    alertError('Terdapat input tidak valid!', 'Silahkan periksa kembali inputan Anda.');
                    // Execute error callback if provided
                    if (onError && typeof onError === 'function') {
                        onError(form, customErrors);
                    }
                }
            }, false);
        });
    }

    // Export function to global scope for initialization from blade template
    window.initializeFormValidation = initializeFormValidation;
})();

/**
 * USAGE EXAMPLES:
 * 
 * 1. Basic usage with custom submit:
 * initializeFormValidation({
 *     onSubmit: function(form) {
 *         submitForm(); // Your custom submit function
 *     }
 * });
 * 
 * 2. With custom validator (BoQ table validation):
 * initializeFormValidation({
 *     customValidator: function(form, fields) {
 *         var errors = [];
 *         
 *         // Example: Check if every location has at least 1 BoQ entry
 *         var designatorTables = $(form).find('.table-designator');
 *         var hasEmptyTable = false;
 *         
 *         designatorTables.each(function (index, table) {
 *             var tr = $(this).find('tbody tr').filter(function () {
 *                 return $(this).css('display') !== 'none';
 *             });
 *             if (tr.length === 0) {
 *                 hasEmptyTable = true;
 *                 return false; // Break
 *             }
 *         });
 *         
 *         if (hasEmptyTable) {
 *             // Use alertWarning for immediate user feedback
 *             alertWarning('Terdapat lokasi yang belum memiliki BoQ!', 'Silakan lengkapi data BoQ pada lokasi tersebut.');
 *             errors.push({
 *                 field: '.table-designator',
 *                 message: 'Minimal harus ada satu item BoQ per lokasi'
 *             });
 *         }
 *         
 *         return {
 *             isValid: errors.length === 0,
 *             errors: errors
 *         };
 *     },
 *     onSubmit: function(form) {
 *         submitForm();
 *     }
 * });
 * 
 * 3. With password validation:
 * initializeFormValidation({
 *     customValidator: function(form, fields) {
 *         var errors = [];
 *         
 *         // Example: Check if password and confirm password match
 *         var password = form.querySelector('#password');
 *         var confirmPassword = form.querySelector('#confirm_password');
 *         if (password && confirmPassword && password.value !== confirmPassword.value) {
 *             errors.push({
 *                 field: '#confirm_password',
 *                 message: 'Password tidak sama'
 *             });
 *         }
 *         
 *         return {
 *             isValid: errors.length === 0,
 *             errors: errors
 *         };
 *     },
 *     onSubmit: function(form) {
 *         // Submit form via AJAX or redirect
 *         window.location.href = '/success';
 *     }
 * });
 * 
 * 4. With custom callbacks:
 * initializeFormValidation({
 *     onSuccess: function(form) {
 *         console.log('Form is valid!', form);
 *         // Custom success logic here (runs before confirmation)
 *     },
 *     onError: function(form, errors) {
 *         console.log('Form has errors:', errors);
 *         // Custom error handling here
 *     },
 *     onSubmit: function(form) {
 *         submitForm();
 *     }
 * });
 * 
 * 5. Without confirmation dialog:
 * initializeFormValidation({
 *     showConfirmDialog: false,
 *     onSubmit: function(form) {
 *         // Submit form directly without confirmation
 *         submitForm();
 *     }
 * });
 * 
 * 6. Custom form selector and messages:
 * initializeFormValidation({
 *     formSelector: '.custom-validation-form',
 *     confirmTitle: 'Konfirmasi Pengiriman',
 *     confirmMessage: 'Apakah data sudah benar?',
 *     onSubmit: function(form) {
 *         submitForm();
 *     }
 * });
 * 
 * 7. Complete example with all options (like margin-analysis.blade.php):
 * initializeFormValidation({
 *     formSelector: '.needs-validation',
 *     confirmTitle: 'Anda Yakin???',
 *     confirmMessage: 'Pastikan semua data sudah benar!',
 *     showConfirmDialog: true,
 *     customValidator: function(form, fields) {
 *         var errors = [];
 *         
 *         // Custom validation: Check if every location has at least 1 BoQ entry
 *         var designatorTables = $(form).find('.table-designator');
 *         designatorTables.each(function (index, table) {
 *             var tr = $(this).find('tbody tr').filter(function () {
 *                 return $(this).css('display') !== 'none';
 *             });
 *             if (tr.length === 0) {
 *                 alertWarning('Terdapat lokasi yang belum memiliki BoQ!', 'Silakan lengkapi data BoQ pada lokasi tersebut.');
 *                 errors.push({
 *                     field: '.table-designator',
 *                     message: 'Minimal harus ada satu item BoQ'
 *                 });
 *                 return false;
 *             }
 *         });
 *         
 *         return { isValid: errors.length === 0, errors: errors };
 *     },
 *     onSuccess: function(form) {
 *         console.log('Validation passed');
 *     },
 *     onError: function(form, errors) {
 *         console.error('Validation failed:', errors);
 *     },
 *     onSubmit: function(form) {
 *         submitForm(); // Your custom submit function
 *     }
 * });
 */
