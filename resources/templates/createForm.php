<form id="create-form" style="display: none">
    Product Name: <input id="createName" type="text" name="name"><br>
    Cost: <input id="createCost" type="number" name="cost"><br>
    Description: <textarea id="createDesc" name="description" rows="5" cols="40"></textarea><br>
    Image: <input id="createImg" type="url" name="img_url"><br>
    <input type="submit" name="submit" value="Submit"><br>
</form>

<script>
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
    })
</script>