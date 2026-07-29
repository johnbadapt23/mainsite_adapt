<section class="fullWidthTextEditor print-only<?php if ( get_sub_field( 'font') ) { ?> <?php the_sub_field( 'font' );?><?php } ?><?php if ( get_sub_field( 'font_colour') ) { ?> <?php the_sub_field( 'font_colour' ); ?><?php } ?> scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <?php the_sub_field( 'embed' ); ?>
    </div>
    <button onclick="generatePDF();">Generate PDF</button>

    <script>
    function generatePDF() {
        var element = document.getElementById('#pdf');
        var report = powerbi.get(element);
        report.print();
    };

    </script>
</section>
