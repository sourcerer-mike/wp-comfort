<?php

require_once __DIR__ . '/bootstrap.php';

require_once ABSPATH . '/wp-admin/includes/plugin.php';

$data = get_plugin_data( COMFORT_FILE );

$versions = \Sami\Version\GitVersionCollection::create(dirname(__DIR__))
	->addFromTags('v1.*.*')
	->add('master');

$config = new Sami\Sami(
	__DIR__ . '/../includes',
	[
		'versions' => $versions,
		'title' => 'Comfort',
		'build_dir' => COMFORT_DIR . '/var/sami/%version%',
		'cache_dir' => COMFORT_DIR . '/var/sami/cache/%version%',
	]
);

return $config;