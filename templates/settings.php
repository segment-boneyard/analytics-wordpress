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
                        Wordpress section of the Setup Guide.</p>
                </td>
            </tr>
        </table>

        <h3 class="title">And you&rsquo;re done!</h3>
        <p style="max-width: 47em">Once you&rsquo;ve saved your API key, you can swap and add
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
    </form>
</div>