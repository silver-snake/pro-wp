<style type="text/css">
    <?php foreach ($colors['selectors'] as $selector => $fields) : ?>
        <?php echo $selector; ?> {
        <?php foreach ($fields as $field) : ?>
            <?php if ($values[$field] || $colors['fields'][$field]['default']) : ?>
                <?php echo $colors['fields'][$field]['style']?>: #<?php echo $values[$field] ? $values[$field] : $colors['fields'][$field]['default']; ?>;
            <?php endif; ?>
        <?php endforeach; ?>
        }
    <?php endforeach; ?>
</style>