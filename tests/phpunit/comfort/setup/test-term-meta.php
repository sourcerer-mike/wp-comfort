<?php

namespace Comfort\Setup;

use Comfort\TestCase;

/**
 * Class TermMetaTest
 *
 * @package Comfort\Setup
 *
 */
class TermMetaTest extends TestCase {
	public static function setUpBeforeClass() {
		require_once COMFORT_DIR . '/includes/setup/1.1.0-term-meta.php';

		parent::setUpBeforeClass();
	}

	public function test_it_registeres_a_new_meta_table() {
		comfort_setup_term_meta();

		/** @var \wpdb $wpdb */
		global $wpdb;

		$this->assertNotEmpty($wpdb->termmeta);
		$this->assertNotEmpty(_get_meta_table('term'));
	}

	public function test_it_creates_usual_meta_table() {
		/** @var \wpdb $wpdb */
		global $wpdb;

		$wpdb->query('SELECT * FROM ' . $wpdb->termmeta . ' LIMIT 1');

		$col_names = $wpdb->get_col_info();

		$all_expected = [
			'term_id',
			'meta_id',
			'meta_key',
			'meta_value'
		];

		foreach ( $all_expected as $expected ) {
			$this->assertContains($expected, $col_names);
		}
	}
}