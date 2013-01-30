<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2>Analytics Settings</h2>


    <div class="postbox-container" style="width:65%;">
        <div class="meta-box-sortables ui-sortable">
            <div class="postbox">
                <div class="handlediv" title="Click to toggle"><br/></div>
                <h3 class="hndle"><span> Analytics Settings</span></h3>
                <div class="inside">

                    <form method="post" action="">
                        <?php wp_nonce_field('analytics_wordpress_settings'); ?>

                        <table class="form-table">
                            <tr>
                                <th valign="top">
                                    <label for="api_key">Your Segment.io API Key</label>
                                </th>
                                <td valign="top">
                                    <input type="text"
                                           name="api_key"
                                           value="<?php echo $settings['api_key']; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <small>Helpful description here.</small>
                                </td>
                            </tr>
                        </table>

                        <p class="submit">
                            <input type="submit"
                                   name="save"
                                   class="button-primary"
                                   value="Save Settings" />
                        </p>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>