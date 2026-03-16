$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $('form, input').attr('autocomplete', 'off');
    // Name validation
        let namePattern = /^[A-Za-z\s]{3,50}$/;
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let phonePattern = /^[0-9]{10}$/;
        let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/;


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

    //email
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

    //password
    $("#password").on("input blur", function () {
        this.value = this.value.slice(0, 20);

        let password = $(this).val().trim();

        if (password === "") {
            $(".error-password").text("Password is required");
        } else if (password.length < 8) {
            $(".error-password").text("Password must be at least 8 characters");
        }else if (!passwordPattern.test(password)) {
            $('.error-password').text("Password must contain uppercase, lowercase, number and special character");
        }  else {
            $(".error-password").text("");
        }
    });

    //profile
    $("#profile").on("change", function () {
        let file = this.files[0];

        if (!file) {
            $(".error-profile").text("Profile image is required");
            return;
        }

        let allowed = ["image/jpeg", "image/png", "image/jpg", "image/webp"];

        if (!allowed.includes(file.type)) {
            $(".error-profile").text("Only JPG, JPEG, PNG , WEBP allowed");
            this.value = "";
        } else {
            $(".error-profile").text("");
        }
    });

    $("#signupForm").submit(function (e) {
        e.preventDefault();


        let formData = new FormData(this);

        $.ajax({
            url: "/signup",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                console.log(response);
                 if (response.status === "success") {
                    $("#successNotification").fadeIn();

                    setTimeout(function () {
                        window.location.href = "/login";
                    }, 1000); // 1 second
                }else if(response.status==="error"){
                     $("#errorNotification")
                    .text(response.message)
                    .fadeIn()
                    .delay(1000)
                    .fadeOut();
                }
            },


            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $(".text-danger").text(""); // clear old errors
                    $.each(errors, function (key, value) {
                        $(".error-" + key).text(value[0]);
                    });
                }
            },
        });
    });


});


function resetValidation() {
    console.log('reset btn clicked');

    // reset form fields
    $('#signupForm')[0].reset();

    // remove all error messages
    $('.text-danger').text('');
}
