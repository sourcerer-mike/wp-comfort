<?php
/**
 * Contains hooks.
 *
 * LICENSE: This source file is subject to the MIT license
 * that is available through the world-wide-web at the following URI:
 * https://opensource.org/licenses/MIT. If you did not receive a copy
 * of the PHP License and are unable to obtain it through the web, please send
 * a note to pretzlaw@gmail.com so we can mail you a copy immediately.
 *
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2016 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/wp-comfort/LICENSE.md MIT License
 * @link      http://github.com/sourcerer-mike/wp-comfort
 */

register_deactivation_hook(
	COMFORT_FILE,
	function () {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

		if ( PHP_SAPI != 'cli' ) {
			check_admin_referer( "deactivate-plugin_{$plugin}" );
		}

		$dependencies = apply_filters( 'children_' . plugin_basename( COMFORT_FILE ), array() );

		sort( $dependencies );

		if ( $dependencies ) {
			wp_redirect(
				admin_url(
					'plugins.php?' . http_build_query(
						array(
							'plugin'       => $plugin,
							'dependencies' => $dependencies,
						)
					)
				)
			);

			if ( PHP_SAPI != 'cli' ) {
				exit;
			}
		}
	}
);

add_action( 'admin_notices', 'comfort_deactivate_errors' );

function comfort_deactivate_errors() {
	global $pagenow;

	if ( 'plugins.php' != $pagenow ) {
		// Not the plugins page: ignore.
		return;
	}

	if ( ! isset( $_GET['dependencies'] ) || ! $_GET['dependencies'] ) {
		// Not an message about dependencies: ignore.
		return;
	}

	$all_plugins = get_plugins();
	$target      = '';

	if ( isset( $_GET['plugin'] ) ) {
		$target = $_GET['plugin'];
	}

	if ( isset( $all_plugins[ $target ] )
	     && isset( $all_plugins[ $target ]['Name'] )
	) {
		$target = $all_plugins[ $target ]['Name'];
	}

	$target = str_replace( '"', "'", $target );

	require_once __DIR__
	             . DIRECTORY_SEPARATOR . 'deactivate-error-notice.phtml';
}
