<div class="list-item" data-id="<?php echo validateText($productId); ?>">
    <img class="item-img" src="<?php echo validateText($imgUrl); ?>">
    <h3 class="item-cost"><?php echo "$" . validateText($cost); ?></h3>
    <div class="item-content">
        <h2 class="item-name"><?php echo validateText($name); ?></h2>
        <p class="item-description"><?php echo validateText($description); ?></p>
        <button class="update-btn" type="button">Update</button>
        <button class="delete-btn" type="button">Delete</button>
    </div>
</div>
