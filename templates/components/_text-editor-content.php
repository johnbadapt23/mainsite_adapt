<section class="text-editor-content" <?php if ( get_sub_field( 'id' )){ ?>id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
    <div class="container">
        <div class="content-inner">
            <div class="content text-black">
                <?php echo get_sub_field( 'content' ); ?>
            </div>
        </div>
    </div>
</section>
