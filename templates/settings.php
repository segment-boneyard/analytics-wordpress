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


        <p style="max-width: 49em"><strong>And you&rsquo;re done!</strong> Once you&rsquo;ve saved your API key, you can swap and add
            integrations right from the Segment.io interface. Any integrations
            you turn on will be live within 10 minutes. No more touching any
            code!</p>

        <p class="submit">
            <input class="button button-primary"
                   type="submit"
                   name="submit"
                   id="submit"
                   value="Save Changes" />
        </p>

        <h3 class="title">Advanced Settings</h3>
        <p style="max-width: 49em">These settings control which events get tracked for you automatically. Most of the time you shouldn&rsquo;t need to mess with these, but just in case you want to:</p>

        <table class="form-table">
            <tr valign="top">
                <th valign="top" scrope="row">
                  <label for="ignore_userlevel">Ignore users:</label>
                </th>
                <td>
                  <fieldset>
                    <select class="select" name="ignore_userlevel" id="ignore_userlevel">
                      <option value="11"<?php if ($settings['ignore_userlevel']==11) echo ' selected="selected"'; ?>>Ignore no-one</option>
                      <option value="8"<?php if ($settings['ignore_userlevel']==8) echo ' selected="selected"'; ?>>Administrator</option>
                      <option value="5"<?php if ($settings['ignore_userlevel']==5) echo ' selected="selected"'; ?>>Editor</option>
                      <option value="2"<?php if ($settings['ignore_userlevel']==2) echo ' selected="selected"'; ?>>Author</option>
                      <option value="1"<?php if ($settings['ignore_userlevel']==1) echo ' selected="selected"'; ?>>Contributor</option>
                      <option value="0"<?php if ($settings['ignore_userlevel']==0) echo ' selected="selected"'; ?>>Subscriber (ignores all logged in users)</option>
                    </select>
                    <p class="description">Users of the role you select and higher will be ignored, so if you select Editor, all Editors and Administrators will be ignored.</p>
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
                        <p class="description">These will be "Viewed Post" events. And if you use any custom post types we&rsquo;ll track those too!</p>
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
                        <p class="description">These will be "Viewed Home Page" or "Viewed About Page" events for any of the pages you create.
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
                        <p class="description">These will be "Viewed Category Page" or "Viewed Author Page" events.
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
                        <p class="description">These will be a "Viewed Search Page" event with their query.
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