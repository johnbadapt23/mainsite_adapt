<?php
/**
 * Default output for a download via the [download] shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/** @var DLM_Download $dlm_download */
?>
<a class="download-link button" id="<?php the_sub_field( 'file_type' ); ?>" title="<?php if ( $dlm_download->get_version()->has_version_number() ) {
	printf( __( 'Version %s', 'download-monitor' ), $dlm_download->get_version()->get_version_number() );
} ?>" href="<?php $dlm_download->the_download_link(); ?>" rel="nofollow">
	<?php if(get_sub_field('file_type')){ ?>
		<?php the_sub_field( 'file_type' ); ?>
	<?php } else { ?>
		Download
	<?php } ?>

</a>
