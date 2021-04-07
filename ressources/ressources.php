<?php

// Test application token for remote manage CRUD methods
$app_password = "0pGG SXWw 7oh7 vBOH gUve rtfD";

// create appartment
function create_appartment($request) {
	$response = new WP_REST_Response();
	// Only application/json content-type
	if($request->get_header("content-type") !== "application/json") {
		$response->set_data([
			"message" => "Unsupported Media Type. Content type should be application/json",
			"status" => 415
		]);
		
		$response->set_status(415);

		return $response;
	}

	$post_title = $request->get_param('post_title');
	$post_content = $request->get_param('post_content');
	$post_author = $request->get_param('post_author');
	$post_status = $request->get_param('post_status');

	$output = wp_insert_post([
		'post_type' => "appartment",
		'post_title' => $post_title,
		'post_content' => $post_content,
		'post_author' => $post_author,
		'post_status' => $post_status
	], true );

	// If post was not created
	if($output instanceof WP_Error) {
		$response->set_data([
			"message" => "Post was not created. " . $output->get_error_message(),
			"status" => 400
		]);
		
		$response->set_status(400);

		return $response;
	}

	$response->set_data([
		"message" => "Post was created. Post id: {$output}",
		"status" => 201
	]);

	$response->set_status(201);

	return $response;
}
// get appartment by id
function get_appartment_by_id( $request ) {
	$response = new WP_REST_Response();
	// Only application/json content-type
	if($request->get_header("content-type") !== "application/json") {
		$response->set_data([
			"message" => "Unsupported Media Type. Content type should be application/json",
			"status" => 415
		]);
		
		$response->set_status(415);

		return $response;
	}

	$id = $request->get_param("id");

	$post = get_post($id);
	if ( !isset( $post ) ) {
		$response->set_data([
			"message" => "No content",
			"status" => 204
		]);

		$response->set_status(204);

	  	return $response;
	}

	$response->set_data([
		"message" => "Ok.",
		"status" => 200,
		"data" => [
			"post_id" => $post->ID,
			"post_author" => $post->post_author,
			"post_date" => $post->post_date,
			"post_title" => $post->post_title,
			"post_excerpt" => $post->post_excerpt,
			"post_content" => $post->post_content,
			"post_status" => $post->post_status
		]
	]);

	$response->set_status(200);
		
	return $response;

  }

function get_appartment_list($request) {
	$response = new WP_REST_Response();

	// Only application/json content-type
	if($request->get_header("content-type") !== "application/json") {
		$response->set_data([
			"message" => "Unsupported Media Type. Content type should be application/json",
			"status" => 415
		]);
		
		$response->set_status(415);

		return $response;
	}
	

	$response->set_data([
		"message" => "ok",
		"status" => 200,
		"data" => get_posts([
			"post_type" => "appartment"
		])
	]);
	
	$response->set_status(200);

	return $response;

}
// update appratment
function update_appartment_by_id($request) {
	// Only application/json content-type
	if($request->get_header("content-type") !== "application/json") {
		$response = new WP_REST_Response([
			"message" => "Unsupported Media Type. Content type should be application/json",
			"status" => 415
		]);
		
		$response->set_status(415);

		return $response;
	}

	$map_data = [
		'post_title' => null,
		'post_content' => null,
		'post_author' => null,
		'post_status' => 'draft'
	];

	$mapped_data = (object)array_merge($map_data, $request->get_param("data"));
	$mapped_data->ID = $request->get_param("ID");
	// update post
	$output = wp_update_post($mapped_data, true);

	$response = new WP_REST_Response();

	if($output instanceof WP_Error) {
		$response->set_data([
			"message" => "Post was not updated. " . $output->get_error_message(),
			"status" => 400
		]);
		
		$response->set_status(400);

		return $response;
	}

	$response->set_data([
		"message" => "Post was updated.",
		"status" => 200,
		"data" => [
			"ID" => $output
		]
	]);

	$response->set_status(200);

	return $response;


}
// delete appartment by id
function delete_appartment_by_id($request) {
	$response = new WP_REST_Response();

	// Only application/json content-type
	if($request->get_header("content-type") !== "application/json") {
		$response->set_data([
			"message" => "Unsupported Media Type. Content type should be application/json",
			"status" => 415
		]);
		
		$response->set_status(415);

		return $response;
	}
	$post_id = $request->get_param("ID");
	$post = get_post($post_id);
	

	if(!isset($post)) {
		$response->set_data([
			"message" => "No content",
			"status" => 204
		]);

		$response->set_status(204);

		return $response;
	}

	$output = wp_delete_post($post_id);

	if($output === null) {
		$response->set_data([
			"message" => "Current entity is not exist.",
			"status" => 204
		]);

		$response->set_status(204);

		return $response;
	}

	if($output === false) {
		$response->set_data([
			"message" => "Entity was not deleted. Try later",
			"status" => 500
		]);

		$response->set_status(500);
	}

	$response->set_data([
		"message" => "Entity {$post_id} was deleted.",
		"status" => 200
	]);

	$response->set_status(200);

	return $response;

}
  

add_action( 'rest_api_init', function () {
	// Create appartment entitiy  [ POST /appartment/{user_id}/entity ]
	register_rest_route( 'rental/v1', '/appartment/(?P<user_id>\d+)/entity', [
		'methods' => 'POST',
		'callback' => 'create_appartment',
		'args' => [
			'user_id' => [
				'required' => true,
				'validate_callback' => function($param) {
					return is_numeric($param);
				}
			],
			'post_title' => [
				'sanitize_callback' => function($param) {
					return esc_html($param);
				},
				'validate_callback' => function($param) {
					return is_string($param);
				}
			],
			'post_content' => [
				'sanitize_callback' => function($param) {
					return esc_html($param);
				},
				'validate_callback' => function($param) {
					return is_string($param);
				}
			],
			'post_author' => [
				'sanitize_callback' => function($param) {
					return esc_html($param);
				},
				'validate_callback' => function($param) {
					return is_string($param);
				}
			],
			'post_status' => [
				'sanitize_callback' => function($param) {
					return esc_html($param);
				},
				'validate_callback' => function($param) {
					return is_string($param);
				}
			],
		],
		'permission_callback' => function () {
			return current_user_can( 'publish_posts' );
		}
	  ] );
  
	// Get appartment entity by id [ GET /appartment/{id} ]
	register_rest_route( 'rental/v1', '/appartment/(?P<id>\d+)', [
	  'methods' => 'GET',
	  'callback' => 'get_appartment_by_id',
	  'args' => [
		  'id' => [
			  'required' => true,
			  'validate_callback' => function($param) {
				return is_numeric($param);
			}
		  ]
	  ]
	] );
	// Get appartment list [ GET /appartment ]
	register_rest_route( 'rental/v1', '/appartment', [
		'methods' => 'GET',
		'callback' => 'get_appartment_list'
	] );
	// Update appartment by id [ PUT /appartment/{user_id}/entity/{ID} ]
	register_rest_route( 'rental/v1', '/appartment/(?P<user_id>\d+)/entity/(?P<ID>\d+)', [
		'methods' => 'PUT',
		'callback' => 'update_appartment_by_id',
		'args' => [
			'ID' => [
				'required' => true,
				'validate_callback' => function($param) {
					return is_numeric($param);
				}
			],
			'user_id' => [
				'required' => true,
				'validate_callback' => function($param) {
					return is_numeric($param);
				}
			],			
			'data' => [
				'required' => true,
				'validate_callback' => function($param) {
					return is_array($param);
				}
			]
		],
		'permission_callback' => function () {
			return current_user_can('edit_posts');
		}	
	] );
	// Delete appartment by id [ DELETE /appartment/{user_id}/entity/{ID} ]
	register_rest_route( 'rental/v1', '/appartment/(?P<user_id>\d+)/entity/(?P<ID>\d+)', [
		'methods' => 'DELETE',
		'callback' => 'delete_appartment_by_id',
		'args' => [
			'user_id' => [
				'required' => true,
				'validate_callback' => function($param) {
					return is_numeric($param);
				}
			],
			'ID' => [
				'required' => true,
				'validate_callback' => function($param) {
					return is_numeric($param);
				}
			],
		],
		'permission_callback' => function () {
			return current_user_can('delete_posts');
		}
	] );
  });


// helper.

function dd($data) {
	echo "<pre>";
		print_r($data);
	echo "</pre>";
}