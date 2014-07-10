<div class="wrap">
  <div id="icon-options-general" class="icon32"></div>
  <h2><?php _e( 'Analytics Settings', 'segment' ); ?></h2>

  <?php if ( isset( $_POST['submit'] ) && check_admin_referer( $this->option ) ) { ?>
    <div class="updated"><p><?php _e( 'Analytics settings saved!', 'segment' ); ?></p></div>
  <?php } ?>

  <form method="post" action="">
    <?php wp_nonce_field( $this->option ); ?>

    <table class="form-table">
      <tr valign="top">
        <th scope="row">
          <label for="api_key"><?php _e( 'Enter your Segment.io API Write key:', 'segment' ); ?></label>
        </th>
        <td>
          <input class="regular-text ltr"
              type="text"
              name="api_key"
              id="api_key"
              value="<?php echo esc_attr( $settings['api_key'] ); ?>" />
          <p class="description"><?php _e( 'You can find your API Write Key in Project Settings > API Keys in your Segment.io Dashboard.', 'segment' ); ?></p>
        </td>
      </tr>
    </table>

    <p style="max-width: 49em"><?php _e( '<strong>And you&rsquo;re done!</strong> Once
      you&rsquo;ve saved your API key, you can swap and add integrations right
      from the Segment.io interface. Any integrations you turn on will be live
      within 10 minutes. No more touching any code!', 'segment' ); ?></p>

    <p class="submit">
      <input class="button button-primary"
          type="submit"
          name="submit"
          id="submit"
          value="<?php _e( 'Save Changes', 'segment' ); ?>" />
    </p>



    <h3 class="title"><?php _e( 'Advanced Settings', 'segment' ); ?></h3>
    <p style="max-width: 49em"><?php _e( 'These settings control which events get tracked
      for you automatically. Most of the time you shouldn&rsquo;t need to mess
      with these, but just in case you want to:', 'segment' ); ?></p>

    <table class="form-table">
      <tr valign="top">
        <th valign="top" scrope="row">
          <label for="ignore_user_level"><?php _e( 'Users to Ignore', 'segment' ); ?></label>
        </th>
        <td>
          <fieldset>
            <select class="select" name="ignore_user_level" id="ignore_user_level">
              <option value="11"<?php if ($settings['ignore_user_level'] == 11) echo ' selected="selected"'; ?>><?php _e( 'No One', 'segment' ); ?></option>
              <option value="8"<?php if ($settings['ignore_user_level'] == 8) echo ' selected="selected"'; ?>><?php _e( 'Administrators and Up', 'segment' ); ?></option>
              <option value="5"<?php if ($settings['ignore_user_level'] == 5) echo ' selected="selected"'; ?>><?php _e( 'Editors and Up', 'segment' ); ?></option>
              <option value="2"<?php if ($settings['ignore_user_level'] == 2) echo ' selected="selected"'; ?>><?php _e( 'Authors and Up', 'segment' ); ?></option>
              <option value="1"<?php if ($settings['ignore_user_level'] == 1) echo ' selected="selected"'; ?>><?php _e( 'Contributors and Up', 'segment' ); ?></option>
              <option value="0"<?php if ($settings['ignore_user_level'] == 0) echo ' selected="selected"'; ?>><?php _e( 'Everyone!', 'segment' ); ?></option>
            </select>
            <p class="description"><?php _e( 'Users of the role you select and higher will
              be ignored.', 'segment' ); ?></p>
          </fieldset>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="track_posts"><?php _e( 'Track Posts', 'segment' ); ?></label>
        </th>
        <td>
          <fieldset>
            <label for="track_posts">
              <input name="track_posts"
                  type="checkbox"
                  id="track_posts"
                  value="1"
                  <?php if ($settings['track_posts']) echo 'checked="checked"'; ?> />
              <?php _e( 'Automatically track events when your users view Posts.', 'segment' ); ?>
            </label>
            <p class="description"><?php _e( 'These will be "Viewed Post" events. And if
              you use any custom post types we&rsquo;ll track those too!', 'segment' ); ?></p>
          </fieldset>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="track_pages"><?php _e( 'Track Pages', 'segment' ); ?></label>
        </th>
        <td>
          <fieldset>
            <label for="track_pages">
              <input name="track_pages"
                  type="checkbox"
                  id="track_pages"
                  value="1"
                  <?php if ($settings['track_pages']) echo 'checked="checked"'; ?> />
              <?php _e( 'Automatically track events when your users view Pages.', 'segment' ); ?>
            </label>
            <p class="description"><?php _e( 'These will be "Viewed Home Page" or "Viewed
              About Page" events for any of the pages you create.', 'segment' ); ?></p>
          </fieldset>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="track_archives"><?php _e( 'Track Archives', 'segment' ); ?></label>
        </th>
        <td>
          <fieldset>
            <label for="track_archives">
              <input name="track_archives"
                     type="checkbox"
                     id="track_archives"
                     value="1"
                     <?php if ($settings['track_archives']) echo 'checked="checked"'; ?> />
              <?php _e( 'Automatically track events when your users view archive pages.', 'segment' ); ?>
            </label>
            <p class="description"><?php _e( 'These will be "Viewed Category Page" or
              "Viewed Author Page" events.', 'segment' ); ?></p>
          </fieldset>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="track_comments"><?php _e( 'Track Comments', 'segment' ); ?></label>
        </th>
        <td>
          <fieldset>
            <label for="track_comments">
              <input name="track_comments"
                     type="checkbox"
                     id="track_comments"
                     value="1"
                     <?php if ($settings['track_comments']) echo 'checked="checked"'; ?> />
              <?php _e( 'Automatically track events when your comments are made.', 'segment' ); ?>
            </label>
          </fieldset>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="track_logins"><?php _e( 'Track Logins', 'segment' ); ?></label>
        </th>
        <td>
          <fieldset>
            <label for="track_logins">
              <input name="track_logins"
                     type="checkbox"
                     id="track_logins"
                     value="1"
                     <?php if ($settings['track_logins']) echo 'checked="checked"'; ?> />
              <?php _e( 'Automatically track events when your users log in.', 'segment' ); ?>
            </label>
          </fieldset>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="track_login_page"><?php _e( 'Track Login Pageviews', 'segment' ); ?></label>
        </th>
        <td>
          <fieldset>
            <label for="track_login_page">
              <input name="track_login_page"
                     type="checkbox"
                     id="track_login_page"
                     value="1"
                     <?php if ($settings['track_login_page']) echo 'checked="checked"'; ?> />
              <?php _e( 'Automatically track events when your login page is viewed.', 'segment' ); ?>
            </label>
          </fieldset>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="track_searches"><?php _e( 'Track Searches', 'segment' ); ?></label>
        </th>
        <td>
          <fieldset>
            <label for="track_searches">
              <input name="track_searches"
                     type="checkbox"
                     id="track_searches"
                     value="1"
                     <?php if ($settings['track_searches']) echo 'checked="checked"'; ?> />
              <?php _e( 'Automatically track events when your users view the search results page.', 'segment' ); ?>
            </label>
            <p class="description"><?php _e( 'These will be "Viewed Search Page" events
              with a &ldquo;query&rdquo; property.', 'segment' ); ?></p>
          </fieldset>
        </td>
      </tr>
    </table>

    <p class="submit">
      <input class="button button-primary"
             type="submit"
             name="submit"
             id="submit"
             value="<?php _e( 'Save Changes', 'segment' ); ?>" />
    </p>
  </form>
</div>