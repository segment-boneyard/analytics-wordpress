<script type="text/javascript">
    analytics.identify(<?php echo "'" . $user_id . "'"; if ($traits) echo ', ' . json_encode($traits); ?>);
</script>