$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("form, input").attr("autocomplete", "off");

    // patterns
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/;
    let otpPattern = /^[0-9]{6}$/;

    // ================= EMAIL =================
    $("#email").on("input blur", function () {
        let email = $(this).val().trim();

        if (email === "") {
            $(".error-email").text("Email is required");
        } else if (!emailPattern.test(email)) {
            $(".error-email").text("Enter valid email");
        } else {
            $(".error-email").text("");
        }
    });

    // ================= PASSWORD =================
    $("#password").on("input blur", function () {
        this.value = this.value.slice(0, 20);

        let password = $(this).val().trim();

        if (password === "") {
            $(".error-password").text("Password is required");
        } else if (!passwordPattern.test(password)) {
            $(".error-password").text("Password must be 6-20 characters");
        } else {
            $(".error-password").text("");
        }
    });

    // ================= OTP =================
    $("#otp").on("keypress", function (e) {
        let char = String.fromCharCode(e.which);

        if (!otpPattern.test(char)) {
            e.preventDefault();
        }
    });

    $("#otp").on("input blur", function () {
        let otp = $(this).val().trim();

        if ($("#otpDiv").hasClass("d-none")) return; // only validate when visible

        if (otp === "") {
            $(".error-otp").text("OTP is required");
        } else if (!otpPattern.test(otp)) {
            $(".error-otp").text("OTP must be 6 digits");
        } else {
            $(".error-otp").text("");
        }
    });

    // ================= SUBMIT =================
    $("#loginForm").submit(function (e) {
        // e.preventDefault();

        let isValid = true;

        let email = $("#email").val().trim();
        let password = $("#password").val().trim();
        let otp = $("#otp").val().trim();

        // email check
        if (email === "") {
            $(".error-email").text("Email is required");
            isValid = false;
        } else if (!emailPattern.test(email)) {
            $(".error-email").text("Enter valid email");
            isValid = false;
        }

        // password check
        if (password === "") {
            $(".error-password").text("Password is required");
            isValid = false;
        } else if (!passwordPattern.test(password)) {
            $(".error-password").text("Password must be 6-20 characters");
            isValid = false;
        }

        // otp check (only if visible)
        if (!$("#otpDiv").hasClass("d-none")) {
            if (otp === "") {
                $(".error-otp").text("OTP is required");
                isValid = false;
            } else if (!otpPattern.test(otp)) {
                $(".error-otp").text("OTP must be 6 digits");
                isValid = false;
            }
        }
        function toggleLockFields() {
            if ($("#otpDiv").length && !$("#otpDiv").hasClass("d-none")) {
                $("#email, #password")
                    .prop("readonly", true)
                    .addClass("locked-field");
            } else {
                $("#email, #password")
                    .prop("readonly", false)
                    .removeClass("locked-field");
            }
        }
        if ($("#otpDiv").length) {
            $("#email, #password").prop("readonly", true).addClass("locked-field");
        }

// run on page load
toggleLockFields();

        if (!isValid) return;

        //let formData = new FormData(this);

        // $.ajax({
        //     url: "/login",
        //     type: "POST",
        //     data: formData,
        //     contentType: false,
        //     processData: false,
        //     dataType: "json",

        //     success: function (response) {

        //         if (response.status === "otp_required") {
        //             console.log(response.status);
        //             $("#otpDiv").removeClass("d-none");
        //             $("#otp").focus();

        //             // 🔒 lock fields
        //             $("#email, #password").prop("readonly", true);

        //             $("#successNotification")
        //                 .text(response.message)
        //                 .fadeIn()
        //                 .delay(2000)
        //                 .fadeOut();
        //         }
        //         else if (response.status === "success") {
        //             $("#successNotification").text(response.message).fadeIn();

        //             setTimeout(function () {
        //                 window.location.href = "/home";
        //             }, 1000);
        //         }
        //         else {
        //             $("#errorNotification")
        //                 .text(response.message)
        //                 .fadeIn()
        //                 .delay(2000)
        //                 .fadeOut();
        //         }
        //     },

        //     error: function (xhr) {
        //         if (xhr.status === 422) {
        //             let errors = xhr.responseJSON.errors;

        //             $(".error-email").text(errors?.email ?? "");
        //             $(".error-password").text(errors?.password ?? "");
        //             $(".error-otp").text(errors?.otp ?? "");
        //         }
        //     },
        // });
    });
});
