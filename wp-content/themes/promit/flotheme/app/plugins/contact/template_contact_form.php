<div class="contact">
    <div class="messages"></div>
    <form method="post" action="">
        <?php foreach ($boxes as $box => $_fields) : ?>
            <div class="<?php echo $box?>">
                <?php foreach ($_fields as $field) : ?>
                    <?php echo $fields[$field]['html']; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <div class="submit">
            <input type="submit" value="Send" />
        </div>
    </form>
</div>