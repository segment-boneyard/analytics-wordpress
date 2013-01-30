<script type="text/javascript">
    analytics.identify('<?php echo $user->ID; ?>', {
        'email'     : '<?php echo $user->user_email; ?>',
        'name'      : '<?php echo $user->display_name; ?>',
        'firstName' : '<?php echo $user->user_firstname; ?>',
        'lastName'  : '<?php echo $user->user_lastname; ?>',
        'username'  : '<?php echo $user->user_login; ?>',
    });
</script>