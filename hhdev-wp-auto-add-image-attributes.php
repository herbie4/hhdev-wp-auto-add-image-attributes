<?php
/*
Plugin Name: Auto Add Image Attributes
Plugin URI: https://github.com/mpellegrin/wordpress-plugin-auto-image-attributes/
Description: Auto Add Image Attributes From Image Filename For New Uploads. Changed to set only alt tag name, clean up title. Can be mu-loaded.
Author: mpellegrin
Version: 1.0.0
*/

// adapted by haha.nl - herbert hoekstra
// ----------------------------

function hhdev_autoimageattributes_auto_image_attributes( $post_ID ) {

	// Default Values For Settings
	$settings = array(
		'image_title' => '1',
		'image_alttext' => '1',
		'hyphens' => '1',
		'under_score' => '1',
	);

	$attachment = get_post( $post_ID );

	// Extract the image name from the image url
	$image_extension = pathinfo($attachment->guid);
	$image_name = basename($attachment->guid, '.'.$image_extension['extension']);

	// Process the image name and neatify it
	$attachment_title = $image_name;
	if ( isset( $settings['hyphens'] ) && boolval($settings['hyphens']) ) {
		$attachment_title 	= str_replace( '-', ' ', $attachment_title ); // Hyphen Removal
	}
	if ( isset( $settings['under_score'] ) && boolval($settings['under_score']) ) {
		$attachment_title 	= str_replace( '_', ' ', $attachment_title ); // Underscore Removal
	}
	$attachment_title 	= ucwords( $attachment_title ); // Capitalize First Word

	$uploaded_image               	= array();
	$uploaded_image['ID']         	= $post_ID;

	if ( isset( $settings['image_title'] ) && boolval($settings['image_title']) ) {
		$uploaded_image['post_title'] 	= $attachment_title; // Image Title
	}

	if ( isset( $settings['image_alttext'] ) && boolval($settings['image_alttext']) ) {
		update_post_meta( $post_ID, '_wp_attachment_image_alt', $attachment_title ); // Image Alt Text
	}

	wp_update_post( $uploaded_image );

}
add_action( 'add_attachment', 'hhdev_autoimageattributes_auto_image_attributes' );
