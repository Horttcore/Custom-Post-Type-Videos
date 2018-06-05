<?php
/**
 *
 *  Custom Post Type Produts
 *
 */
final class Custom_Post_Type_Videos
{



	/**
	 * Plugin constructor
	 *
	 * @access public
	 * @return void
	 * @since 0.3
	 * @author Ralf Hortt <me@horttcore.de>
	 **/
	public function __construct()
	{

		add_action( 'custom-post-type-videos-widget-output', 'Custom_Post_Type_Videos_Widget::widget_output', 10, 3 );
		add_action( 'custom-post-type-videos-widget-loop-output', 'Custom_Post_Type_Videos_Widget::widget_loop_output', 10, 3 );
		add_action( 'init', [$this, 'register_post_type']);
		add_action( 'init', [$this, 'register_taxonomy']);
		add_action( 'plugins_loaded', [$this, 'load_plugin_textdomain']);
		add_action( 'pre_get_posts', [$this, 'pre_get_posts']);
		add_filter( 'widgets_init', [$this, 'widgets_init']);

	} // END __construct



	/**
	 * Load plugin translation
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt <me@horttcore.de>
	 * @since 0.3
	 **/
	public function load_plugin_textdomain()
	{

		load_plugin_textdomain( 'custom-post-type-videos', false, dirname( plugin_basename( __FILE__ ) ) . '/../languages/'  );

	} // END load_plugin_textdomain



	/**
	 * Alter default query
	 *
	 * @param WP_Query $query WP Query object
	 * @return void
	 */
	public function pre_get_posts( $query )
	{

		if ( !is_post_type_archive( 'video' ) )
			return;

		if ( !$query->is_main_query() )
			return;

		if ( $query->get( 'order' ) )
			return;

		$query->set( 'orderby', ['menu_order' => 'ASC', 'title' => 'ASC'] );

	}



	/**
	 *
	 * Register post type
	 *
	 * @access public
	 * @return void
	 * @since 0.3
	 * @author Ralf Hortt <me@horttcore.de>
	 */
	public function register_post_type()
	{

		register_post_type( 'video',
			[
				'labels' => [
					'name' => _x( 'Videos', 'post type general name', 'custom-post-type-videos' ),
					'singular_name' => _x( 'Video', 'post type singular name', 'custom-post-type-videos' ),
					'add_new' => _x( 'Add New', 'Video', 'custom-post-type-videos' ),
					'add_new_item' => __( 'Add New Video', 'custom-post-type-videos' ),
					'edit_item' => __( 'Edit Video', 'custom-post-type-videos' ),
					'new_item' => __( 'New Video', 'custom-post-type-videos' ),
					'view_item' => __( 'View Video', 'custom-post-type-videos' ),
					'search_items' => __( 'Search Video', 'custom-post-type-videos' ),
					'not_found' =>  __( 'No Videos found', 'custom-post-type-videos' ),
					'not_found_in_trash' => __( 'No Videos found in Trash', 'custom-post-type-videos' ),
					'parent_item_colon' => '',
					'menu_name' => __( 'Videos', 'custom-post-type-videos' )
				],
				'public' => TRUE,
				'publicly_queryable' => TRUE,
				'show_ui' => TRUE,
				'show_in_menu' => TRUE,
				'query_var' => TRUE,
				'rewrite' => [
					'slug' => _x( 'videos', 'Post Type Slug', 'custom-post-type-videos' ),
					'with_front' => FALSE,
				],
				'capability_type' => 'post',
				'has_archive' => TRUE,
				'hierarchical' => FALSE,
				'menu_position' => NULL,
				'menu_icon' => 'dashicons-format-video',
				'supports' => ['title', 'thumbnail', 'page-attributes']
			]
		);

	} // END register_post_type



	/**
	 *
	 * Register taxonomy
	 *
	 * @access public
	 * @return void
	 * @since 0.3
	 * @author Ralf Hortt <me@horttcore.de>
	 */
	public function register_taxonomy()
	{

		register_taxonomy( 'video-category', ['video'],
			[
				'hierarchical' => TRUE,
				'labels' => array(
					'name' => _x( 'Video Categories', 'taxonomy general name', 'custom-post-type-videos' ),
					'singular_name' => _x( 'Video Category', 'taxonomy singular name', 'custom-post-type-videos' ),
					'search_items' =>  __( 'Search Video Categories', 'custom-post-type-videos' ),
					'all_items' => __( 'All Video Categories', 'custom-post-type-videos' ),
					'parent_item' => __( 'Parent Video Category', 'custom-post-type-videos' ),
					'parent_item_colon' => __( 'Parent Video Category:', 'custom-post-type-videos' ),
					'edit_item' => __( 'Edit Video Category', 'custom-post-type-videos' ),
					'update_item' => __( 'Update Video Category', 'custom-post-type-videos' ),
					'add_new_item' => __( 'Add New Video Category', 'custom-post-type-videos' ),
					'new_item_name' => __( 'New Video Category Name', 'custom-post-type-videos' ),
					'menu_name' => __( 'Video Categories', 'custom-post-type-videos' ),
				),
				'show_ui' => TRUE,
				'query_var' => TRUE,
				'rewrite' => ['slug' => _x( 'video-category', 'Video Category Slug', 'custom-post-type-videos' )],
				'show_admin_column' => TRUE,
			]
		);

	} // END register_taxonomy



	/**
	 * Register widgets
	 *
	 * @access public
	 * @return void
	 * @since 0.5.0
	 * @author Ralf Hortt <me@horttcore.de>
	 **/
	public function widgets_init()
	{

		register_widget( 'Custom_Post_Type_Videos_Widget' );

	} // END widgets_init



} // END final class Custom_Post_Type_Videos

new Custom_Post_Type_Videos;
