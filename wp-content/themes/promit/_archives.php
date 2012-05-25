<?php if (!is_page() && !is_404())  : ?>
    <section id="archives">
        <?php flotheme_archives();?>
        <div class="clearfix"></div>
    </section>
    <section id="pagination">
        <?php flotheme_pagination();?>
        <div class="clearfix"></div>
    </section>
<?php endif; ?>