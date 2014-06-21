 <script type="text/javascript">
  analytics.page(<?php echo '"' . $page . '"' ?><?php if (!empty($properties)) { echo ', ' . json_encode($properties); } else { echo ', {}'; } ?><?php if (!empty($options)) { echo ', ' . json_encode($options); } ?>);
</script>