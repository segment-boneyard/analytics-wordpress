
# Analytics for WordPress

**Analytics for WordPress** is a WordPress plugin for [Segment](https://segment.io) that lets you send data to any analytics service you want without touching any code.


## Installation

To get up and running, checkout our documentation at [segment.io/plugins/wordpress](https://segment.io/plugins/wordpress)—installation takes less than five minutes!


## Debugging

Running into notices, warnings or errors?  Enable `WP_DEBUG` for proper error reporting by adding the following code to your `wp-config.php` file:

```
define( 'WP_DEBUG', true );
```

## Contributing

We'd love to have you contribute to the Segment WordPress plugin.  We'll gladly review any pull request, but pull requests that have followed recommended practices are more likely to be merged:

1. Our recommended development environment for contributing to the Segment WordPress plugin is called [VVV](https://github.com/Varying-Vagrant-Vagrants/VVV).  It's a community-developed Vagrant environment for WordPress.  If you do any WordPress development at all, you won't find a better development environment.  We highly recommend using it.
1. After you've installed VVV, `vagrant up`, change directories to whichever WordPress install you're developing against, and `git clone https://github.com/your-user-account/analytics-wordpress.git` into the plugins directory.  This assumes you've already forked the Segment WordPress plugin.  If you haven't, be sure to do so.
1. Boom, you're ready to go!  Go to the WordPress instance you're using (consult the [VVV documentation](https://github.com/Varying-Vagrant-Vagrants/VVV/blob/master/README.md#wordpress-stable) if you're not sure which to use) and activate the Segment plugin.
1. Now you're ready to make your changes.  Fixing a bug? Awesome! Write unit tests.  Adding a feature? Sweet! Write unit tests. Check out our [current tests in the tests folder](https://github.com/segmentio/analytics-wordpress/tree/master/tests) for reference.
1. Once your changes are made and tests are written, confirm that all tests and assertions are passing by running `phpunit` from the command line in the plugin directory.
1. Commit, send your pull request, and pat yourself on the back for contributing to open source software!

## Support

If you run into issues, be sure to check out the [documentation](https://segment.io/plugins/wordpress), and you can always reach out to our [support team](https://segment.io/support) for help!


## Deploying

To deploy a new version of the WordPress plugin to the Plugins Directory, use the `make deploy` command which will ask which existing Git tag you want to deploy. You'll need to get credentials for the Segment SVN repository.

_Note: this is for internal Segment use only, and if you're just pull requesting things to the plugin you don't need to worry about this._


## License

This software is licensed under Version 2 of the [GNU General Public License](http://www.gnu.org/licenses/gpl-2.0.html), the same license used for WordPress core. Check out the [license.txt](license.txt) file for more information.