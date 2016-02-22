$(document).ready(function() {
    $('#create-btn').click(function() {
        $('#create-form').show()
    });
    $('#cancel-create').click(function() {
        $('#create-form').hide();
    });
    $('#create-form').submit(function(e) {
        e.preventDefault();

        $.ajax({
            method: 'POST',
            url: '/create.php',
            data: {
                name: $('#create-name').val(),
                cost: $('#create-cost').val(),
                description: $('#create-description').val(),
                imgUrl: $('#create-img').val()
            },
            success: function() {
                alert('success');
            }
        });
    });

    $('.delete-btn').click(function() {
        if (!confirm('Are you sure?')) return;

        var $listItem = $(this).parent().parent();

        $.ajax({
            method: 'GET',
            url: '/delete.php',
            data: { productId: $listItem.data('id') },
            success: function() {
                $listItem.remove();
            }
        });
    });

    $('.update-btn').click(function() {
        var $listItem = $(this).parent().parent();
        var imgUrl = $listItem.find('.item-img').attr('src');
        var cost = $listItem.find('.item-cost').text();
        var name = $listItem.find('.item-name').text();
        var description = $listItem.find('.item-description').text();
        var productId = $listItem.attr('data-id');
        var $updateForm = $('#update-form');

        $updateForm.attr('data-id', productId);
        $updateForm.find('#update-name').val(name);
        $updateForm.find('#update-cost').val(cost);
        $updateForm.find('#update-description').val(description);
        $updateForm.find('#update-img').val(imgUrl);

        $updateForm.appendTo($listItem);
        $updateForm.show();
    });
    $('#cancel-update').click(function() {
        $('#update-form').hide();
    });
    $('#update-form').submit(function(e) {
        e.preventDefault();

        var $updateForm = $(this);

        var productId = $updateForm.attr('data-id');
        var name = $updateForm.find('#update-name').val();
        var cost = $updateForm.find('#update-cost').val();
        var description = $updateForm.find('#update-description').val();
        var imgUrl = $updateForm.find('#update-img').val();

        $.ajax({
            method: 'POST',
            url: '/update.php',
            data: {
                productId: productId,
                name: name,
                cost: cost,
                description: description,
                imgUrl: imgUrl
            },
            success: function() {
                var $listItem = $updateForm.parent();
                $listItem.find('.item-img').attr('src', imgUrl);
                $listItem.find('.item-cost').text(cost);
                $listItem.find('.item-name').text(name);
                $listItem.find('.item-description').text(description);
            }
        });
    });
});
