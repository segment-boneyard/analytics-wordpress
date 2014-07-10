<script type="text/javascript">
	analytics.identify( <?php echo '"' . intval( $user_id ) . '"' ?><?php if ( ! empty( $traits ) ) { echo ', ' . json_encode( array_map( 'esc_js', $traits ) ); } else { echo ', {}'; } ?><?php if ( ! empty( $options ) ) { echo ', ' . json_encode( array_map( 'esc_js', $options ) ); } ?>);
</script>