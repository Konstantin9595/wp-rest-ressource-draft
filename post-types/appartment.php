<?php

/**
 * Registers the `appartment` post type.
 */
function appartment_init() {
	register_post_type( 'appartment', array(
		'labels'                => array(
			'name'                  => __( 'Appartments', 'twentynineteen' ),
			'singular_name'         => __( 'Appartment', 'twentynineteen' ),
			'all_items'             => __( 'All Appartments', 'twentynineteen' ),
			'archives'              => __( 'Appartment Archives', 'twentynineteen' ),
			'attributes'            => __( 'Appartment Attributes', 'twentynineteen' ),
			'insert_into_item'      => __( 'Insert into Appartment', 'twentynineteen' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Appartment', 'twentynineteen' ),
			'featured_image'        => _x( 'Featured Image', 'appartment', 'twentynineteen' ),
			'set_featured_image'    => _x( 'Set featured image', 'appartment', 'twentynineteen' ),
			'remove_featured_image' => _x( 'Remove featured image', 'appartment', 'twentynineteen' ),
			'use_featured_image'    => _x( 'Use as featured image', 'appartment', 'twentynineteen' ),
			'filter_items_list'     => __( 'Filter Appartments list', 'twentynineteen' ),
			'items_list_navigation' => __( 'Appartments list navigation', 'twentynineteen' ),
			'items_list'            => __( 'Appartments list', 'twentynineteen' ),
			'new_item'              => __( 'New Appartment', 'twentynineteen' ),
			'add_new'               => __( 'Add New', 'twentynineteen' ),
			'add_new_item'          => __( 'Add New Appartment', 'twentynineteen' ),
			'edit_item'             => __( 'Edit Appartment', 'twentynineteen' ),
			'view_item'             => __( 'View Appartment', 'twentynineteen' ),
			'view_items'            => __( 'View Appartments', 'twentynineteen' ),
			'search_items'          => __( 'Search Appartments', 'twentynineteen' ),
			'not_found'             => __( 'No Appartments found', 'twentynineteen' ),
			'not_found_in_trash'    => __( 'No Appartments found in trash', 'twentynineteen' ),
			'parent_item_colon'     => __( 'Parent Appartment:', 'twentynineteen' ),
			'menu_name'             => __( 'Appartments', 'twentynineteen' ),
		),
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_nav_menus'     => true,
		'supports'              => array( 'title', 'editor' ),
		'has_archive'           => true,
		'rewrite'               => true,
		'query_var'             => true,
		'menu_position'         => null,
		'menu_icon'             => 'dashicons-admin-post',
		'show_in_rest'          => true,
		'rest_base'             => 'appartment',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'appartment_init' );

/**
 * Sets the post updated messages for the `appartment` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `appartment` post type.
 */
function appartment_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['appartment'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Appartment updated. <a target="_blank" href="%s">View Appartment</a>', 'twentynineteen' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'twentynineteen' ),
		3  => __( 'Custom field deleted.', 'twentynineteen' ),
		4  => __( 'Appartment updated.', 'twentynineteen' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Appartment restored to revision from %s', 'twentynineteen' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Appartment published. <a href="%s">View Appartment</a>', 'twentynineteen' ), esc_url( $permalink ) ),
		7  => __( 'Appartment saved.', 'twentynineteen' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Appartment submitted. <a target="_blank" href="%s">Preview Appartment</a>', 'twentynineteen' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Appartment scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Appartment</a>', 'twentynineteen' ),
		date_i18n( __( 'M j, Y @ G:i', 'twentynineteen' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Appartment draft updated. <a target="_blank" href="%s">Preview Appartment</a>', 'twentynineteen' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'appartment_updated_messages' );
