<script type="text/javascript">
	analytics.identify( <?php echo '"' . esc_js( $user_id ) . '"' ?><?php if ( ! empty( $traits ) ) { echo ', ' . json_encode( Segment_Analytics_WordPress::esc_js_deep( $traits ) ); } else { echo ', {}'; } ?><?php if ( ! empty( $options ) ) { echo ', ' . json_encode( Segment_Analytics_WordPress::esc_js_deep( $options ) ); } ?>);
</script>