 <script type="text/javascript">
  analytics.track(<?php echo '"' . esc_js( $event ) . '"' ?><?php if ( ! empty( $properties ) ) { echo ', ' . json_encode( Segment_Analytics_WordPress::esc_js_deep( $properties ) ); } else { echo ', {}'; } ?><?php if ( ! empty( $options ) ) { echo ', ' . json_encode( Segment_Analytics_WordPress::esc_js_deep( $options ) ); } ?>);
    <?php
  	if ( $http_event ) :
  		?>

		analytics.ajaxurl = "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>";

		jQuery( document ).ready( function( $ ) {
			var data = {
				action : 'segment_unset_cookie',
				key    : '<?php echo esc_js( $http_event ); ?>',

			},
			success = function( response ) {
				console.log( response );
			};

			$.post( analytics.ajaxurl, data, success );
		});

  		<?php
	endif;
  ?>

</script>
