<div class="wrap">
    <h2><?php _e( 'Analytics Settings', 'segment' ); ?></h2>
    <form method="post" action="options.php">
      <?php
        settings_fields( self::SLUG );
        do_settings_sections( self::SLUG );
        submit_button();
      ?>
    </form>
</div>