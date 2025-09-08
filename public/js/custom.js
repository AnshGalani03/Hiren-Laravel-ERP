// Custom JavaScript for ERP System
$(document).ready(function () {
    // Global Select2 initialization with enhanced options
    function initializeSelect2() {
        $(".select2").each(function () {
            if (!$(this).hasClass("select2-hidden-accessible")) {
                $(this).select2({
                    theme: "bootstrap-5",
                    width: "100%",
                    placeholder: function () {
                        return (
                            $(this).data("placeholder") || "Select an option..."
                        );
                    },
                    allowClear: true,
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                });
            }
        });
    }

    // Initialize Select2 on page load
    initializeSelect2();

    // Re-initialize Select2 after AJAX content load
    $(document).ajaxComplete(function () {
        setTimeout(function () {
            initializeSelect2();
        }, 100);
    });

    // Handle form resets with Select2
    $("form").on("reset", function () {
        var $form = $(this);
        setTimeout(function () {
            $form.find(".select2").val(null).trigger("change");
        }, 1);
    });

    // Reset filters function for all filter forms
    $(document).on("click", "#reset_filters, .reset-filters", function (e) {
        e.preventDefault();
        var $container = $(this).closest(
            ".card, .form-container, .filter-section"
        );

        // Reset all select2 dropdowns
        $container.find(".select2").val(null).trigger("change");

        // Reset regular form inputs
        $container
            .find('input[type="text"], input[type="email"], textarea')
            .val("");

        // Reset date inputs
        $container.find('input[type="date"]').val("");

        // Reset regular select dropdowns
        $container.find("select:not(.select2)").prop("selectedIndex", 0);

        // Trigger table redraw if DataTable exists
        if (typeof table !== "undefined" && table.draw) {
            table.draw();
        }

        // Trigger any custom events
        $(document).trigger("filtersReset");
    });

    // Global DataTable configuration
    $.extend(true, $.fn.dataTable.defaults, {
        processing: true,
        serverSide: true,
        responsive: true,
        language: {
            processing: '<div class="loading">Loading...</div>',
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            zeroRecords: "No matching records found",
            emptyTable: "No data available in table",
            paginate: {
                first: "First",
                previous: "Previous",
                next: "Next",
                last: "Last",
            },
        },
        dom:
            '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        pageLength: 25,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"],
        ],
    });

    // Global AJAX setup
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Global delete confirmation handler
    $(document).on(
        "click",
        '.delete-btn, .btn-delete, [data-action="delete"]',
        function (e) {
            e.preventDefault();

            var $btn = $(this);
            var url = $btn.data("url") || $btn.attr("href");
            var message =
                $btn.data("message") ||
                "Are you sure you want to delete this item?";
            var tableId = $btn.data("table") || "#datatable, .dataTable";

            if (confirm(message)) {
                $.ajax({
                    url: url,
                    type: "DELETE",
                    dataType: "json",
                    beforeSend: function () {
                        $btn.prop("disabled", true).addClass("loading");
                    },
                    success: function (response) {
                        if (response.success) {
                            // Reload DataTable if exists
                            if (
                                $(tableId).length &&
                                $.fn.DataTable.isDataTable(tableId)
                            ) {
                                $(tableId).DataTable().ajax.reload(null, false);
                            } else {
                                // Fallback: reload page
                                location.reload();
                            }

                            // Show success message
                            showNotification(
                                "success",
                                response.message || "Item deleted successfully!"
                            );
                        } else {
                            showNotification(
                                "error",
                                response.message || "Error deleting item."
                            );
                        }
                    },
                    error: function (xhr) {
                        console.log("Delete Error:", xhr.responseText);
                        showNotification(
                            "error",
                            "Error deleting item. Please try again."
                        );
                    },
                    complete: function () {
                        $btn.prop("disabled", false).removeClass("loading");
                    },
                });
            }
        }
    );

    // Number formatting utility
    window.numberFormat = function (num, decimals = 2) {
        return parseFloat(num || 0).toLocaleString("en-IN", {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals,
        });
    };

    // Show notification utility
    window.showNotification = function (type, message, duration = 3000) {
        // Create notification element
        var notification = $(`
            <div class="alert alert-${
                type === "error" ? "danger" : type
            } alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);

        // Add to page
        $("body").append(notification);

        // Auto hide after duration
        setTimeout(function () {
            notification.alert("close");
        }, duration);
    };

    // Handle Select2 in modals
    $(document).on("shown.bs.modal", ".modal", function () {
        $(this)
            .find(".select2")
            .each(function () {
                if (!$(this).hasClass("select2-hidden-accessible")) {
                    $(this).select2({
                        theme: "bootstrap-5",
                        width: "100%",
                        dropdownParent: $(this).closest(".modal"),
                        placeholder:
                            $(this).data("placeholder") ||
                            "Select an option...",
                        allowClear: true,
                    });
                }
            });
    });

    // Back button functionality
    $(document).on("click", ".back-btn", function (e) {
        e.preventDefault();
        window.history.back();
    });

    // Print functionality
    $(document).on("click", ".print-btn", function (e) {
        e.preventDefault();
        window.print();
    });

    // Auto-hide alerts
    setTimeout(function () {
        $(".alert-success, .alert-info").fadeOut();
    }, 5000);

    // Form validation enhancement
    $("form").on("submit", function () {
        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');

        $submitBtn.prop("disabled", true).addClass("loading");

        setTimeout(function () {
            $submitBtn.prop("disabled", false).removeClass("loading");
        }, 3000);
    });

    // Console log for debugging
    console.log("ERP Custom JS loaded successfully!");
});

// Utility function to reinitialize Select2 (can be called from anywhere)
window.reinitializeSelect2 = function (container) {
    var $container = container ? $(container) : $(document);
    $container.find(".select2").each(function () {
        if ($(this).hasClass("select2-hidden-accessible")) {
            $(this).select2("destroy");
        }
        $(this).select2({
            theme: "bootstrap-5",
            width: "100%",
            placeholder: $(this).data("placeholder") || "Select an option...",
            allowClear: true,
        });
    });
};
