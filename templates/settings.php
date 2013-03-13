<div class="wrap">
  <div id="icon-options-general" class="icon32"></div>
  <h2>Analytics Settings</h2>

  <?php if (isset($_POST['submit']) && check_admin_referer($this->option)) { ?>
    <div class="updated"><p>Analytics settings saved!</p></div>
  <?php } ?>

  <form method="post" action="">
    <?php wp_nonce_field($this->option); ?>

    <table class="form-table">
      <tr valign="top">
        <th scope="row">
          <label for="api_key">Enter your Segment.io API key:</label>
        </th>
        <td>
          <input class="regular-text ltr"
              type="text"
              name="api_key"
              id="api_key"
              value="<?php echo $settings['api_key']; ?>" />
          <p class="description">You can find your API key in the
            WordPress section of the Setup Guide.</p>
        </td>
      </tr>
    </table>

    <p style="max-width: 49em"><strong>And you&rsquo;re done!</strong> Once
      you&rsquo;ve saved your API key, you can swap and add integrations right
      from the Segment.io interface. Any integrations you turn on will be live
      within 10 minutes. No more touching any code!</p>

    <p class="submit">
      <input class="button button-primary"
          type="submit"
          name="submit"
          id="submit"
          value="Save Changes" />
    </p>



    <h3 class="title">Advanced Settings</h3>
    <p style="max-width: 49em">These settings control which events get tracked
      for you automatically. Most of the time you shouldn&rsquo;t need to mess
      with these, but just in case you want to:</p>

    <table class="form-table">
      <tr valign="top">
        <th valign="top" scrope="row">
          <label for="ignore_user_level">Users to Ignore</label>
        </th>
        <td>
          <fieldset>
            <select class="select" name="ignore_user_level" id="ignore_user_level">
              <option value="11"<?php if ($settings['ignore_user_level'] == 11) echo ' selected="selected"'; ?>>No One</option>
              <option value="8"<?php if ($settings['ignore_user_level'] == 8) echo ' selected="selected"'; ?>>Administrators and Up</option>
              <option value="5"<?php if ($settings['ignore_user_level'] == 5) echo ' selected="selected"'; ?>>Editors and Up</option>
              <option value="2"<?php if ($settings['ignore_user_level'] == 2) echo ' selected="selected"'; ?>>Authors and Up</option>
              <option value="1"<?php if ($settings['ignore_user_level'] == 1) echo ' selected="selected"'; ?>>Contributors and Up</option>
              <option value="0"<?php if ($settings['ignore_user_level'] == 0) echo ' selected="selected"'; ?>>All Logged-in Users</option>
            </select>
            <p class="description">Users of the role you select and higher will
              be ignored.</p>
          </fieldset>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="track_posts">Track Posts</label>
        </th>
        <td>
          <fieldset>
            <label for="track_posts">
              <input name="track_posts"
                  type="checkbox"
                  id="track_posts"
                  value="1"
                  <?php if ($settings['track_posts']) echo 'checked="checked"'; ?> />
              Automatically track events when your users view Posts.
            </label>
            <p class="description">These will be "Viewed Post" events. And if
              you use any custom post types we&rsquo;ll track those too!</p>
          </fieldset>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="track_pages">Track Pages</label>
        </th>
        <td>
          <fieldset>
            <label for="track_pages">
              <input name="track_pages"
                  type="checkbox"
                  id="track_pages"
                  value="1"
                  <?php if ($settings['track_pages']) echo 'checked="checked"'; ?> />
              Automatically track events when your users view Pages.
            </label>
            <p class="description">These will be "Viewed Home Page" or "Viewed
              About Page" events for any of the pages you create.</p>
          </fieldset>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="track_archives">Track Archives</label>
        </th>
        <td>
          <fieldset>
            <label for="track_archives">
              <input name="track_archives"
                     type="checkbox"
                     id="track_archives"
                     value="1"
                     <?php if ($settings['track_archives']) echo 'checked="checked"'; ?> />
              Automatically track events when your users view archive pages.
            </label>
            <p class="description">These will be "Viewed Category Page" or
              "Viewed Author Page" events.</p>
          </fieldset>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="track_searches">Track Searches</label>
        </th>
        <td>
          <fieldset>
            <label for="track_searches">
              <input name="track_searches"
                     type="checkbox"
                     id="track_searches"
                     value="1"
                     <?php if ($settings['track_searches']) echo 'checked="checked"'; ?> />
              Automatically track events when your users view the search results page.
            </label>
            <p class="description">These will be "Viewed Search Page" events
              with a &ldquo;query&rdquo; property.</p>
          </fieldset>
        </td>
      </tr>
    </table>

    <p class="submit">
      <input class="button button-primary"
             type="submit"
             name="submit"
             id="submit"
             value="Save Changes" />
    </p>
  </form>
</div>