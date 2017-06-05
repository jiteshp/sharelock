<div class="sharelock-container">
	<div class="sharelock-container-inner">
		<div class="sharelock">
			<p class="sharelock-message"><?php echo wp_kses_post( $atts['message'] ); ?></p>

			<button class="sharelock-button" data-share-url="<?php echo esc_attr( $atts['url'] ); ?>"><span class="dashicons dashicons-facebook"></span><?php esc_html_e( 'Share', 'sharelock' ); ?></button>

			<div class="sharelock-icon"><span class="dashicons dashicons-lock"></span></div>
		</div>

		<div class="sharelock-content">
			<?php echo do_shortcode( $content ); ?>

			<div class="sharelock-icon"><span class="dashicons dashicons-unlock"></span></div>
		</div>

	</div>
</div>
