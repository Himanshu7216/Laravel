$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Name validation
    let namePattern = /^[A-Za-z\s]{3,50}$/;
    let phonePattern = /^[0-9]{10}$/;

    //name
    $("#name").on("keypress", function (e) {
        let char = String.fromCharCode(e.which);

        if (!/^[a-zA-Z\s]+$/.test(char)) {
            e.preventDefault();
        }
    });
    $("#name").on("input blur", function () {
        this.value = this.value.replace(/[^a-zA-Z\s]/g, "").slice(0, 50);
        let name = $(this).val().trim();
        if (name === "") {
            $(".error-name").text("Name is required");
        } else if (!namePattern.test(name)) {
            $(".error-name").text("Only letters allowed (3-50 characters)");
        } else {
            $(".error-name").text("");
        }
    });
    //phone
    $("#phone").on("keypress", function (e) {
        let char = String.fromCharCode(e.which);
        let charPattern = /^[0-9]+$/;
        if (!charPattern.test(char)) {
            e.preventDefault();
        }
    });
    $("#phone").on("input blur", function () {
        let phone = $(this).val().trim();

        if (phone === "") {
            $(".error-phone").text("Phone is required");
        } else if (!phonePattern.test(phone)) {
            $(".error-phone").text("Phone must be 10 digits");
        } else {
            $(".error-phone").text("");
        }
    });

    $("#twoFactorToggle").on("change", function () {
        let toggle = $(this);
        let status = toggle.is(":checked") ? 1 : 0;

        // 👉 SHOW CONFIRM ONLY WHEN ENABLING
        if (status === 1) {
            let confirmEnable = confirm("Are you sure you want to enable 2FA?");

            if (!confirmEnable) {
                toggle.prop("checked", false);
                return;
            }
        }

        toggle.prop("disabled", true); // prevent spam clicks

        $.ajax({
            url: "/toggle-2fa",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                enable_two_factors: status,
            },
            success: function (response) {
                console.log(response);
                if (response.status === "logout") {
                    $("#successNotification").text(response.message).fadeIn();
                    setTimeout(function () {
                        window.location.href = "/login";
                    }, 1000); // 1 second
                }
                if (response.status === "success") {
                    $("#successNotification").text(response.message).fadeIn();
                } else if (response.status === "error") {
                    $("#errorNotification")
                        .text(response.message)
                        .fadeIn()
                        .delay(1000)
                        .fadeOut();
                    toggle.prop("checked", !status); // revert UI
                }
            },
            complete: function () {
                toggle.prop("disabled", false);
            },
        });
    });
});
