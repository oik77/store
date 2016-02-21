$(document).ready(function() {
    $("#create-btn").click(function() {
        $("#create-form").toggle()
    });

    $('#create-form').submit(function(e) {
        e.preventDefault();

        $.ajax({
            method: 'GET',
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

    $('.delete-btn').click(function() {
        var $listItem = $(this).parent().parent();

        $.ajax({
            method: 'GET',
            url: '/delete.php',
            data: { id: $listItem.data('id') },
            success: function() {
                $listItem.remove();
            }
        });
    })
});
