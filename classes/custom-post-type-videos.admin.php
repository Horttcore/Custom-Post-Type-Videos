<?php
/**
 *
 *  Custom Post Type Produts
 *
 */
class Custom_Post_Type_Videos_Admin
{



	/**
	 * Plugin constructor
	 *
	 * @access public
	 * @return void
	 * @since v0.3
	 * @author Ralf Hortt <me@horttcore.de>
	 **/
	public function __construct()
	{

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

	} // END __construct


	/**
	 * Register Metaboxes
	 *
	 * @access public
	 * @author Ralf Hortt
	 **/
	public function add_meta_boxes()
	{

		add_meta_box( 'video-information', __( 'Video Information', 'custom-post-type-videos' ), array( $this, 'meta_box_information' ), 'video', 'normal' );

	} // END add_meta_boxes


	/**
	 * Metabox
	 *
	 * @access public
	 * @param obj $post Post object
	 * @author Ralf Hortt
	 **/
	public function meta_box_information( $post )
	{
		do_action( 'custom-post-type-videos-meta-box-table-before', $post );
		?>

		<table class="form-table">

			<?php do_action( 'custom-post-type-videos-meta-box-table-first-row-before', $post ); ?>

			<tr>
				<th><label for="video-link"><?php _e( 'Link:', 'custom-post-type-videos' ); ?></label></th>
				<td>
					<input size="50" type="text" value="<?php echo esc_attr( get_post_meta( $post->ID, '_video-link', TRUE ) ) ?>" name="video-link" id="video-link"><br>
				</td>
			</tr>

			<?php do_action( 'custom-post-type-videos-meta-box-table-last-row-after', $post ); ?>

		</table>

		<?php
		do_action( 'custom-post-type-videos-meta-box-table-after', $post );
		wp_nonce_field( 'save-video-meta', 'video-meta-nonce' );
	} // end video_meta




	/**
	 * Update messages
	 *
	 * @access public
	 * @param array $messages Messages
	 * @return array Messages
	 * @since v0.3
	 * @author Ralf Hortt <me@horttcore.de>
	 **/
	public function post_updated_messages( $messages )
	{

		$post             = get_post();
		$post_type        = 'video';
		$post_type_object = get_post_type_object( $post_type );

		$messages[$post_type] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Video updated.', 'custom-post-type-videos' ),
			2  => __( 'Custom field updated.' ),
			3  => __( 'Custom field deleted.' ),
			4  => __( 'Video updated.', 'custom-post-type-videos' ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Video restored to revision from %s', 'custom-post-type-videos' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Video published.', 'custom-post-type-videos' ),
			7  => __( 'Video saved.', 'custom-post-type-videos' ),
			8  => __( 'Video submitted.', 'custom-post-type-videos' ),
			9  => sprintf( __( 'Video scheduled for: <strong>%1$s</strong>.', 'custom-post-type-videos' ), date_i18n( __( 'M j, Y @ G:i', 'custom-post-type-videos' ), strtotime( $post->post_date ) ) ),
			10 => __( 'Video draft updated.', 'custom-post-type-videos' )
		);

		if ( $post_type_object->publicly_queryable ) :

			$permalink = get_permalink( $post->ID );

			$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View video', 'custom-post-type-videos' ) );
			$messages[ $post_type ][1] .= $view_link;
			$messages[ $post_type ][6] .= $view_link;
			$messages[ $post_type ][9] .= $view_link;

			$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
			$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview video', 'custom-post-type-videos' ) );
			$messages[ $post_type ][8]  .= $preview_link;
			$messages[ $post_type ][10] .= $preview_link;

		endif;

		return $messages;

	} // END post_updated_messages



	/**
	 * Save post meta
	 *
	 * @access public
	 * @param int $post_id Post ID
	 * @author Ralf Hortt
	 **/
	public function save_post( $post_id )
	{

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( !isset( $_POST['video-meta-nonce'] ) || !wp_verify_nonce( $_POST['video-meta-nonce'], 'save-video-meta' ) )
			return;

		update_post_meta( $post_id, '_video-link', sanitize_url( $_POST['video-link'] ) );

	} // end save_post



} // END class Custom_Post_Type_Videos_Admin

new Custom_Post_Type_Videos_Admin;
