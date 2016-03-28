<?php

namespace Comfort\Admin;

use PHPUnit\Comfort\TestCase;

class SetupTest extends TestCase {
	public static $redirect_to = null;

	protected function setUp() {
		static::$redirect_to = null;

		$admins = get_super_admins();
		$user   = get_user_by( 'login', $admins[0] );

		if ( is_wp_error( $user ) || ! $user ) {
			throw new \Exception( 'Could not login.' );
		}

		wp_set_current_user( $user->ID );
	}


	public function test_activation_hook_exists() {
		$key = 'activate_' . plugin_basename( COMFORT_FILE );

		global $wp_filter;

		$this->assertArrayHasKey( $key, $wp_filter );
	}

	public function test_deactivation_hook_exists() {
		$key = 'deactivate_' . plugin_basename( COMFORT_FILE );

		global $wp_filter;

		$this->assertArrayHasKey( $key, $wp_filter );
	}

	public function test_can_not_be_deactivated_with_dependencies() {
		// Given I am logged in as super-admin

		// And fetch all redirects
		$this->assertNull( static::$redirect_to );
		add_filter(
			'wp_redirect',
			function ( $location ) {
				SetupTest::$redirect_to = $location;

				return false;
			}
		);

		// And plugin "comfort" depends on something
		add_filter(
			'children_' . plugin_basename( COMFORT_FILE ),
			function ( $dependencies ) {
				$dependencies[] = COMFORT_FILE;

				return $dependencies;
			}
		);

		// When I deactivate the plugin
		do_action( 'deactivate_' . plugin_basename( COMFORT_FILE ) );

		// Then it should redirect me
		$this->assertNotNull( static::$redirect_to );
		$this->assertContains( '/plugins.php?', static::$redirect_to );
	}

	/**
	 * @backupGlobals
	 */
	public function test_admin_notices_show_dependencies() {
		global $pagenow;

		$backup_pagenow = $pagenow;

		// Given I am on the backend "plugins.php"
		$pagenow              = 'plugins.php';

		// And collide with dependencies
		$_GET['plugin']       = $this->getPluginBasename();
		$_GET['dependencies'] = [
			$this->getPluginBasename(),
			md5( $this->getPluginBasename() )
		];

		// When I visit the page
		ob_start();
		comfort_deactivate_errors();
		$content = ob_get_clean();

		// Then I should see the errors
		$this->assertNotEmpty( $content );
		$this->assertContains( $this->getPluginBasename(), $content );
		$this->assertContains( md5( $this->getPluginBasename() ), $content );

		$pagenow = $backup_pagenow;
	}
}