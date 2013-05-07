<script type="text/javascript">
  analytics.identify(<?php echo '"' . $user_id . '"' ?>, <?php echo json_encode($traits, JSON_FORCE_OBJECT); ?>, <?php echo json_encode($options, JSON_FORCE_OBJECT); ?>);
</script>