<?php
/**
 * Contains Post class.
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

namespace Comfort;

/**
 * Define posts via object.
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
 *
 * @property bool     _builtin             ( default: false )
 * @property string   _edit_link           ( default:
 *           'post.php?post=%d' )
 * @property bool     can_export           ( default: true )
 * @property string[] capabilities         ( default: array() )
 * @property string   capability_type      ( default: 'post' )
 * @property bool     delete_with_user     ( default: null )
 * @property string   description          ( default: '' )
 * @property bool     exclude_from_search  ( default: null )
 * @property bool     has_archive          ( default: false )
 * @property bool     hierarchical         ( default: false )
 * @property string[] labels               ( default: array() )
 * @property string   menu_icon            ( default: null )
 * @property int      menu_position        ( default: null )
 * @property int      map_meta_cap         ( default: null )
 * @property bool     public               ( default: false )
 * @property bool     publicly_queryable   ( default: null )
 * @property bool     query_var            ( default: true )
 * @property bool     register_meta_box_cb ( default: null )
 * @property string[] rewrite              ( default: true )
 * @property bool     show_ui              ( default: null )
 * @property bool     show_in_menu         ( default: null )
 * @property bool     show_in_nav_menus    ( default: null )
 * @property bool     show_in_admin_bar    ( default: null )
 * @property string[] supports             ( default: array() )
 * @property string[] taxonomies           ( default: array() )
 *
 * @see       https://developer.wordpress.org/resource/dashicons for menu_icon values
 * @see       http://www.kevinleary.net/wordpress-dashicons-list-custom-post-type-icons/ for menu_icon values
 */
class Post_Type {
	/**
	 * @var string Identifier of the post type (e.g. "product").
	 */
	protected $_post_type;
	/**
	 * Callable for the "enter_title_here" filter.
	 *
	 * @var null|\Closure
	 */
	protected $_title_placeholder = null;

	/**
	 * Constructor
	 *
	 * @param string $post_type Identifier of the post-type.
	 */
	public function __construct( $post_type ) {
		$this->_post_type = $post_type;
	}

	/**
	 * Register the current post type.
	 *
	 * The **position in the menu** is mostly sorted alphabetically,
	 * if a label is given (e.g. via `scaffold_labels`).
	 * To determine the order take the first letter.
	 * It's order will be the position in the alphabet plus 26,
	 * which is below comments and above the first separator
	 * (see https://codex.wordpress.org/Function_Reference/register_post_type#menu_position ).
	 *
	 * @see ::scaffold_labels
	 */
	public function register_post_type() {
		$data = $this->to_array();

		if ( ! isset( $data['menu_position'] )
		     && isset( $this->labels )
		     && isset( $this->labels['menu_name'] )
		) {
			$order = substr( ( $this->labels['menu_name'] ), 0, 1 );

			$order = strtr(
				strtolower( $order ),
				array( 'ä' => 'a', 'ö' => 'o', 'ü' => 'u' )
			);

			$data['menu_position'] = ord( $order ) - 97 + 26;
		} else if ( ! isset( $data['menu_position'] ) ) {
			$data['menu_position'] = 30;
		}

		register_post_type( $this->get_post_type(), $data );
	}

	public function to_array() {
		return call_user_func( 'get_object_vars', $this );
	}

	/**
	 * @return string
	 */
	public function get_post_type() {
		return (string) $this->_post_type;
	}

	/**
	 * Place singular and plural forms for the labels.
	 *
	 * The translation to each post type will be:
	 *
	 *      array(
	 *          'name'               => $plural,
	 *          'singular_name'      => $singular,
	 *          'all_items'          => $plural,
	 *          'add_new'            => $this->__( 'Add %s', $singular ),
	 *          'add_new_item'       => $this->__( 'Add new %s', $singular ),
	 *          'edit_item'          => $this->__( 'Edit %s', $singular ),
	 *          'new_item'           => $this->__( 'New %s', $singular ),
	 *          'view_item'          => $this->__( 'View %s', $singular ),
	 *          'search_items'       => $this->__( 'Search %s', $plural ),
	 *          'not_found'          => $this->__( '0 %s found', $singular ),
	 *          'not_found_in_trash' => $this->__( 'No entry in trash' ),
	 *          'parent_item_colon'  => $this->__( 'Parent %s', $singular ),
	 *          'menu_name'          => $plural,
	 *      )
	 *
	 * @param $singular
	 * @param $plural
	 */
	public function scaffold_labels(
		$singular,
		$plural
	) {
		$this->labels = array(
			'name'               => $plural,
			'singular_name'      => $singular,
			'all_items'          => $plural,
			'add_new'            => esc_html__( 'Add %s', $singular ),
			'add_new_item'       => esc_html__( 'Add new %s', $singular ),
			'edit_item'          => esc_html__( 'Edit %s', $singular ),
			'new_item'           => esc_html__( 'New %s', $singular ),
			'view_item'          => esc_html__( 'View %s', $singular ),
			'search_items'       => esc_html__( 'Search %s', $plural ),
			'not_found'          => esc_html__( '0 %s found', $singular ),
			'not_found_in_trash' => esc_html__( 'No entry in trash' ),
			'parent_item_colon'  => esc_html__( 'Parent %s', $singular ),
			'menu_name'          => $plural,
		);

		if ( ! $this->_title_placeholder ) {
			$this->set_title_placeholder( $singular );
		}
	}

	public function set_title_placeholder( $placeholder_text ) {
		if ( $this->_title_placeholder ) {
			remove_filter( 'enter_title_here', $this->_title_placeholder );
		}

		$post_type = $this->get_post_type();

		$this->_title_placeholder = function ( $text ) use ( $placeholder_text, $post_type ) {
			$screen = get_current_screen();
			if ( $post_type != $screen->post_type ) {
				return $text;
			}

			return $placeholder_text;
		};

		add_filter( 'enter_title_here', $this->_title_placeholder );
	}
}