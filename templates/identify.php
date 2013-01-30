<script type="text/javascript">
    analytics.identify('<?php echo $user->ID; ?>', {
        <?php if ($user->user_email) { ?>
        'email'     : '<?php echo $user->user_email; ?>',
        <?php } if ($user->display_name) { ?>
        'name'      : '<?php echo $user->display_name; ?>',
        <?php } if ($user->user_firstname) { ?>
        'firstName' : '<?php echo $user->user_firstname; ?>',
        <?php } if ($user->user_lastname) { ?>
        'lastName'  : '<?php echo $user->user_lastname; ?>',
        <?php } if ($user->user_url) { ?>
        'website'   : '<?php echo $user->user_url; ?>',
        <?php } ?>
        'username'  : '<?php echo $user->user_login; ?>'
    });
</script>