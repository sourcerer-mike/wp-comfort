<?php

register_deactivation_hook(
	COMFORT_FILE,
	function () {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

		if (PHP_SAPI != 'cli') {
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

			if (PHP_SAPI != 'cli') {
				exit;
			}
		}
	}
);

add_action(
	'admin_notices',
	function () {
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
);
