<div class="list-item" data-id="<?php echo validateText($productId); ?>">
    <div class="list-item-inner">
        <div class="item-left">
            <img class="item-img" src="<?php echo validateText($imgUrl); ?>">
        </div>
        <div class="item-right">
            <span>$</span>
            <span class="item-cost"><?php echo validateText($cost); ?></span>
        </div>
        <div class="item-center">
            <h2 class="item-name"><?php echo validateText($name); ?></h2>
            <p class="item-description"><?php echo validateText($description); ?></p>
            <button class="update-btn" type="button">Update</button>
            <button class="delete-btn" type="button">Delete</button>
        </div>
    </div>
</div>
