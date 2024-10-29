=== Admin Users Logged In ===
Contributors: mpol
Tags: dashboard widget, admin users, last login
Requires at least: 4.4
Tested up to: 6.6
Stable tag: 1.0.6
License: GPLv2 or later
Requires PHP: 7.0

Dashboard widget that shows admin users and when they were last logged in.


== Description ==

Dashboard widget that shows admin users and when they were last logged in.

= Compatibility =

This plugin is compatible with [ClassicPress](https://www.classicpress.net).

= Contributions =

This plugin is also available in
[Codeberg](https://codeberg.org/cyclotouriste/admin-users-logged-in).


== Installation ==

= Installation =

* Install the plugin through the admin page "Plugins".
* Alternatively, unpack and upload the contents of the zipfile to your '/wp-content/plugins/' directory.
* Activate the plugin through the 'Plugins' menu in WordPress.
* Visit the Dashboard. That's it.


= PHP filters for Custom Roles =

This first filter is for showing an additional role in the widget.

	<?php
		function my_auli_get_role__in( $role__in ) {
			$role__in[] = 'Subscriber';
			return $role__in;
		}
		add_filter( 'auli_get_role__in', 'my_auli_get_role__in' );
	?>

This second filter is for who gets to see the widget.

	<?php
		function my_auli_show_for_role__in( $role__in ) {
			$role__in[] = 'Customrole';
			return $role__in;
		}
		add_filter( 'auli_show_for_role__in', 'my_auli_show_for_role__in' );
	?>


== Screenshots ==

1. Dashboard widget with admin users and their last login.


== Changelog ==

= 1.0.6 =
* 2024-10-02
* Loading plugin translations should be delayed until init action (in this case admin_init).
* Use __DIR__ for loading translations instead of dirname(__FILE__).
* Better check for direct access of files.

= 1.0.5 =
* 2022-05-11
* Add filter 'auli_get_role__in' for showing users with these roles.
* Remove check for shown users based on capability.
* Add filter 'auli_show_for_role__in' for allowing users to see this widget.
* No need to check if function 'current_user_can()' exists.

= 1.0.4 =
* 2021-06-01
* Support translations.

= 1.0.3 =
* 2021-05-31
* Small updates from wpcs.

= 1.0.2 =
* 2018-02-06
* Fix the location of closing </div> (outside the loop).

= 1.0.1 =
* 2018-02-03
* Only show the widget on capability 'edit_posts'.

= 1.0.0 =
* 2018-01-18
* First release.
