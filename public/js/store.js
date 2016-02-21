$(document).ready(function() {
    $("#create-btn").click(function() {
        $("#create-form").toggle()
    });

    $('#create-form').submit(function(e) {
        e.preventDefault();

        $.ajax({
            method: 'POST',
            url: '/create.php',
            data: {
                name: $('#createName').val(),
                cost: $('#createCost').val(),
                description: $('#createDesc').val(),
                img_url: $('#createImg').val()
            },
            success: function() {
                alert('success');
            }
        });
    });
});
