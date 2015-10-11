<?php

require_once __DIR__ . '/bootstrap.php';

require_once ABSPATH . '/wp-admin/includes/plugin.php';

$data = get_plugin_data( COMFORT_FILE );

$versions = \Sami\Version\GitVersionCollection::create( dirname( __DIR__ ) )
                                              ->addFromTags( 'v1.*.*' )
                                              ->add( 'master' )
;

$iterator = new \Symfony\Component\Finder\Finder();
$iterator->files()
         ->in( COMFORT_DIR . '/includes' )
         ->name( '*.php' )
         ->append(
	         \Symfony\Component\Finder\Finder::create()
	                                         ->in( COMFORT_DIR )
	                                         ->name( '*.php' )
	                                         ->depth( 0 )
         )
;

$config = new Sami\Sami(
	$iterator,
	[
		'versions'  => $versions,
		'title'     => 'Comfort',
		'build_dir' => COMFORT_DIR . '/var/sami/%version%',
		'cache_dir' => COMFORT_DIR . '/var/sami/cache/%version%',
	]
);

return $config;