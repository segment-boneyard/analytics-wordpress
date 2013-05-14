<script type="text/javascript">
  analytics.track(<?php echo '"' . $event . '"' ?><?php if (!empty($properties)) { echo ', ' . json_encode($properties); } else { echo ', {}'; } ?><?php if (!empty($options)) { echo ', ' . json_encode($options); } ?>);
</script>