$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });


    let productNamePattern = /^[A-Za-z\s]{3,50}$/;

    $("#productname").on("keypress", function (e) {
        let char = String.fromCharCode(e.which);

        if (!/^[a-zA-Z\s]+$/.test(char)) {
            e.preventDefault();
        }
    });
    $("#productname").on("input blur", function () {
        this.value = this.value.replace(/[^a-zA-Z\s]/g, "").slice(0, 50);
        let productName = $(this).val().trim();
        if (productName === "") {
            $(".error-productname").text("Product Name is required");
        } else if (!productNamePattern.test(productName)) {
            $(".error-productname").text("Only letters allowed (3-50 characters)");
        } else {
            $(".error-productname").text("");
        }
    });


// DESCRIPTION VALIDATION
$("#description").on("input blur", function () {

    let description = $(this).val().trim();

    if (description === "") {
        $(".error-description").text("Description is required");
    }
    else if (description.length > 1000) {
        $(".error-description").text("Description cannot exceed 1000 characters");
    }
    else {
        $(".error-description").text("");
    }

});


   let pricePattern = /^[0-9]*\.?[0-9]*$/;

// Allow only numbers and one decimal point
$("#price").on("keypress", function (e) {
    let char = String.fromCharCode(e.which);

    if (!/[0-9.]/.test(char)) {
        e.preventDefault();
    }

    // prevent multiple dots
    if (char === "." && $(this).val().includes(".")) {
        e.preventDefault();
    }
});

$("#price").on("input blur", function () {
    let price = $(this).val().trim();

    if (price === "") {
        $(".error-price").text("Price is required");
    }
    else if (!pricePattern.test(price)) {
        $(".error-price").text("Only integer or decimal values allowed");
    }
    else {
        $(".error-price").text("");
    }
});

$("#category").on("change blur", function () {
    let category = $(this).val();

    if (category === "" || category === null) {
        $(".error-category").text("Category is required");
    } else {
        $(".error-category").text("");
    }
});


    $("#image").on("change", function () {
        let file = this.files[0];

        if (!file) {
            $(".error-image").text(" image is required");
            return;
        }

        let allowed = ["image/jpeg", "image/png", "image/jpg", "image/webp"];

        if (!allowed.includes(file.type)) {
            $(".error-image").text("Only JPG, JPEG, PNG , WEBP allowed");
            this.value = "";
        } else {
            $(".error-image").text("");
        }
    });





    $('form, input').attr('autocomplete', 'off');




















    $('form, input').attr('autocomplete', 'off');

    $("#addProductForm").submit(function (e) {
        console.log('addProduct function ');
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "/products/add",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                // console.log(response);

                if (response.status === "success") {

                    $("#successNotification")
                        .text(response.message)
                        .fadeIn()
                        .delay(1000)
                        .fadeOut();

                    setTimeout(function () {
                        window.location.href = "/products";
                    }, 1000);

                } else if (response.status === "error") {

                    $("#errorNotification")
                        .text(response.message)
                        .fadeIn()
                        .delay(1000)
                        .fadeOut();

                        $("#addProductForm")[0].reset();
                        $(".text-danger").text("");        // clear errors
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

