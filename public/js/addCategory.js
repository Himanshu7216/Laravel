$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });


let categoryPattern = /^[a-zA-Z0-9\s]+$/;

$("#categoryname").on("keypress", function(e){

    let char = String.fromCharCode(e.which);

    if(!/^[a-zA-Z0-9\s]+$/.test(char)){
        e.preventDefault();
    }

});

// CATEGORY NAME VALIDATION
$("#categoryname").on("input blur", function () {

    let categoryname = $(this).val().trim();

    if (categoryname === "") {
        $(".error-categoryname").text("Category name is required");
    }
    else if (categoryname.length < 5) {
        $(".error-categoryname").text("Category name must be at least 5 characters");
    }
    else if (categoryname.length > 50) {
        $(".error-categoryname").text("Category name cannot exceed 50 characters");
    }
    else if (!categoryPattern.test(categoryname)) {
        $(".error-categoryname").text("Only letters, numbers and spaces allowed");
    }
    else {
        $(".error-categoryname").text("");
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

























    $('form, input').attr('autocomplete', 'off');

    $("#addCategoryForm").submit(function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        
        $.ajax({
            url: "/categories/add",
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
                        window.location.href = "/categories";
                    }, 1000);

                } else if (response.status === "error") {

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

