
# Open the WordPress site and admin.
test:
		open http://localhost:8888/wp-admin/options-general.php?page=analytics-wordpress
		open http://localhost:8888/

# Turns the testing plugin into a real folder, for more accurate testing.
test-folder:
		rm -rf test/wp-content/plugins/analytics-wordpress
		cp -r ./ ../analytics-wordpress-temp
		rm -rf ../analytics-wordpress-temp/test
		rm -rf ../analytics-wordpress-temp/.git
		mv ../analytics-wordpress-temp test/wp-content/plugins/analytics-wordpress

# Turns the testing plugin into a symlink, for faster testing.
test-symlink:
		rm -rf test/wp-content/plugins/analytics-wordpress
		ln -s ../../.. test/wp-content/plugins/analytics-wordpress

.PHONY: test