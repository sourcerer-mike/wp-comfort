<?php // Silence is golden.

namespace Comfort;

$loader_class = __NAMESPACE__ . '\\Loader';
if ( ! class_exists( __NAMESPACE__ . '\\Loader' ) ) {
	return;
}

$loader_class::register_directory( __DIR__ . '/includes' );

/**
 * Load files of classes via SPL.
 */
class Loader {
	/**
	 * List of directories to load from.
	 *
	 * @var string[]
	 */
	protected static $_directories = [ ];

	/**
	 * Directories where classes will be searched.
	 *
	 * @return \string[]
	 */
	public static function get_directories() {
		return static::$_directories;
	}

	/**
	 * Search class in include path and load it.
	 *
	 * @param string $class_name The class name.
	 */
	public static function load_class( $class_name ) {

		$filename = static::class_to_file( $class_name );

		foreach ( static::$_directories as $namespace => $base_path ) {
			$file_path = $base_path . DIRECTORY_SEPARATOR . $filename;

			if ( ! is_readable( $file_path ) ) {
				continue;
			}

			require_once $file_path;
		}
	}

	/**
	 * Turn class name into a filename.
	 *
	 * Giving a class name will turn it into a filename fulfilling
	 * the WordPress Coding Standards.
	 *
	 * @since 0.0.0
	 * @link  https://make.wordpress.org/core/handbook/coding-standards/php/#naming-conventions
	 *
	 * @param string $class_name The name of a class.
	 *
	 * @return string
	 */
	public static function class_to_file( $class_name ) {
		$filename = '';

		// Sanitize class name.
		$class_name = ltrim( $class_name, '\\' );

		// WP coding standards: Files should be [...] lowercase letters.
		$class_name = strtolower( $class_name );

		if ( $last_ns_pos = strrpos( $class_name, '\\' ) ) {
			$namespace  = substr( $class_name, 0, $last_ns_pos );
			$class_name = substr( $class_name, $last_ns_pos + 1 );
			$filename   = str_replace(
				'\\',
				DIRECTORY_SEPARATOR,
				$namespace
			);
		}

		// WP coding standards:
		// - Hyphens should separate words.
		// - Class file names should be [...] with class- prepended.
		$filename = (string) str_replace(
			'_',
			'-',
			$filename
			. DIRECTORY_SEPARATOR . 'class-' . $class_name . '.php'
		);

		return $filename;
	}

	/**
	 * Register this loader.
	 */
	public static function register() {
		\spl_autoload_register(
			array( __CLASS__, 'load_class' )
		);

	}

	/**
	 * Add a directory to look up classes in WordPress-Style
	 *
	 * @param string $path Directory to search in.
	 */
	public static function register_directory( $path ) {
		static::$_directories[] = $path;
	}
}

Loader::register();
