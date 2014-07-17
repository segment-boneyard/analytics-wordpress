
# Analytics for WordPress

**Analytics for WordPress** is a WordPress plugin for [Segment](https://segment.io) that lets you send data to any analytics service you want without touching any code.


## Installation

To get up and running, checkout our documentation at [segment.io/plugins/wordpress](https://segment.io/plugins/wordpress)—installation takes less than five minutes!


## Debugging

Running into notices, warnings or errors?  Enable `WP_DEBUG` for proper error reporting by adding the following code to your `wp-config.php` file:

```
define( 'WP_DEBUG', true );
```


## Support

If you run into issues, be sure to check out the [documentation](https://segment.io/plugins/wordpress), and you can always reach out to our [support team](https://segment.io/support) for help!


## Deploying

To deploy a new version of the WordPress plugin to the Plugins Directory, use the `make deploy` command which will ask which existing Git tag you want to deploy. You'll need to get credentials for the Segment SVN repository.

_Note: this is for internal Segment use only, and if you're just pull requesting things to the plugin you don't need to worry about this._


## License

This software is licensed under Version 2 of the [GNU General Public License](http://www.gnu.org/licenses/gpl-2.0.html), the same license used for WordPress core. Check out the [license.txt](license.txt) file for more information.
