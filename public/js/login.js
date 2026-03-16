$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#loginForm").submit(function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "/login",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",

            success: function (response) {
                console.log(response);

                if (response.status === "otp_required") {
                    $("#otpDiv").removeClass("d-none");
                    $("#otp").focus();

                    $("#successNotification")
                        .text(response.message)
                        .fadeIn()
                        .delay(2000)
                        .fadeOut();
                } else if (response.status === "success") {
                    $("#successNotification").text(response.message).fadeIn();

                    setTimeout(function () {
                        window.location.href = "/home";
                    }, 1000);
                } else if (response.status === "error") {
                    $("#errorNotification")
                        .text(response.message)
                        .fadeIn()
                        .delay(2000)
                        .fadeOut();
                }
            },

            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $(".error-email").text(errors?.email ?? "");
                    $(".error-password").text(errors?.password ?? "");
                    $(".error-otp").text(errors?.otp ?? "");
                }
            },
        });
    });
});
