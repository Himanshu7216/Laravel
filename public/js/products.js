$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $(document).on("submit", ".deleteProductForm", function (e) {

        e.preventDefault();

        let id = $(this).data("id");

        if (!confirm("Are you sure you want to delete this product?")) {
            return;
        }

        $.ajax({
            url: "/products/delete/" + id,
            type: "DELETE",

            success: function (response) {
            if (response.status === "success") {
                $("#productRow" + id).fadeOut(400, function () {
                    $(this).remove();
                });

                $("#successNotification")
                        .text(response.message)
                        .fadeIn()
                        .delay(1000)
                        .fadeOut();

            }  else if (response.status === "error") {

                    $("#errorNotification")
                        .text(response.message)
                        .fadeIn()
                        .delay(1000)
                        .fadeOut();

                }
        },

            error: function () {
                alert("Something went wrong");
            }

        });

    });

});
