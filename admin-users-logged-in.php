<?php
/*
Plugin Name: Admin Users Logged In
Plugin URI: https://wordpress.org/plugins/admin-users-logged-in/
Description: Dashboard widget that shows admin users and when they were last logged in.
Version: 1.0.6
Author: Marcel Pol
Author URI: https://timelord.nl
License: GPLv2 or later
Text Domain: admin-users-logged-in
Domain Path: /lang/


Copyright 2018 - 2024  Marcel Pol  (marcel@timelord.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Adds a dashboard widget to show the admin users.
 *
 * @since 1.0.0
 */
function auli_dashboard() {

	$role__in = array(
		'Administrator',
		'Editor',
		'Author',
		);
	/*
	* Filters the roles that are shown in the dashboard widget.
	* Users with these roles will be shown in the widget.
	* Expects the name of a role with the first character capitalized.
	*
	* @since 1.0.5
	*
	* @param array $role__in Array with roles.
	*/
	$role__in = apply_filters( 'auli_get_role__in', $role__in );

	// role__in will only work since WP 4.4.
	$users_query = new WP_User_Query( array(
		'role__in' => $role__in,
		'fields'   => 'all',
		'orderby'  => 'display_name',
		) );
	$users = $users_query->get_results();

	if ( is_array($users) && ! empty($users) ) {
		echo '<div class="auli-dashboard">';
		$rowodd = false;
		foreach ( $users as $user ) {

			if ($user === FALSE) {
				// Invalid $user_id
				continue;
			}

			$class = '';
			// rows have a different color.
			if ($rowodd) {
				$rowodd = false;
				$class = ' alternate';
			} else {
				$rowodd = true;
				$class = '';
			} ?>

			<div id="auli_<?php echo esc_html( $user->ID ); ?>" class="comment depth-1 comment-item <?php echo esc_html( $class ); ?>">
				<div class="dashboard-comment-wrap">
					<h4>
						<span class="auli-author"><cite><?php
							if ( isset( $user->display_name ) ) {
								echo esc_html( $user->display_name );
							} else {
								echo esc_html( $user->user_login );
							} ?>:
						</cite></span>

						<span class="auli-date">
							<?php
							$datetime = get_user_meta( $user->ID, 'auli_last', true );
							if ( $datetime ) {
								echo esc_html( date_i18n( get_option('date_format'), $datetime ) . ', ' . date_i18n( get_option('time_format'), $datetime ) );
							} else {
								esc_html_e( 'Never logged in', 'admin-users-logged-in' );
							} ?>
						</span>
					</h4>
				</div>
			</div><?php
		}
		echo '</div>';
	}
}


// Add the widget.
function auli_dashboard_setup() {

	$user = wp_get_current_user(); // getting & setting the current user
	$roles = (array) $user->roles;

	$role__in = array(
		'Administrator',
		'Editor',
		'Author',
		);
	/*
	* Filters if the user has access to this widget based on his/her roles.
	* Expects the name of a role with the first character capitalized.
	*
	* @since 1.0.5
	*
	* @param array $role__in Array with roles.
	*/
	$role__in = apply_filters( 'auli_show_for_role__in', $role__in );

	$allowed_admin = false;
	foreach ( $roles as $role ) {
		$role = ucfirst( $role );
		if ( in_array( $role, $role__in, true ) ) {
			$allowed_admin = true;
			break;
		}
	}

	if ( $allowed_admin ) {
		wp_add_dashboard_widget( 'auli_dashboard', esc_html__('Admin Users', 'admin-users-logged-in'), 'auli_dashboard' );
	}

}
add_action( 'wp_dashboard_setup', 'auli_dashboard_setup' );


/*
 * Save last login in user meta.
 *
 * @since 1.0.0
 */
function auli_admin_init() {

	$user_id = get_current_user_id(); // returns 0 if no current user
	$datetime = current_time( 'timestamp' );

	update_user_meta( $user_id, 'auli_last', $datetime );

}
add_action( 'admin_init', 'auli_admin_init' );


/*
 * Load Language files for dashboard only.
 *
 * @since 1.0.4
 */
function auli_load_lang() {

	load_plugin_textdomain( 'admin-users-logged-in', false, plugin_basename( __DIR__ ) . '/lang' );

}
add_action( 'admin_init', 'auli_load_lang' );
