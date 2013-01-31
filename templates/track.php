<script type="text/javascript">
    analytics.track(<?php echo "'" . $event . "'"; if ($properties) echo ', ' . json_encode($properties); ?>);
</script>