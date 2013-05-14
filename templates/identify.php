<script type="text/javascript">
  analytics.identify(<?php echo '"' . $user_id . '"' ?><?php if (!empty($traits)) { echo ', ' . json_encode($traits); } else { echo ', {}'; } ?><?php if (!empty($options)) { echo ', ' . json_encode($options); } ?>);
</script>