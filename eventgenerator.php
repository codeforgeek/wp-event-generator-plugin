<?php
/*
Plugin Name: Event generator
Description: This plugin acts as hook to notify when there is new article is added on WordPress or article is being updated.
Author: Shahid Shaikh
Author URI: https://codeforgeek.com/about
Version: 1.1.2
*/

add_action( 'post_updated', 'post_update_function', 10, 3 );

function post_update_function( $post_ID, $post_after, $post_before ) {
	// on each change, call this api to notify the content engine
	$event = null;
	// if article is auto draft
	if($post_before -> post_status === 'auto-draft' && $post_after -> post_status === 'publish') {
		$event = 'new_article';
	// if article is draft then published
	} else if($post_before -> post_status === 'draft' && $post_after -> post_status === 'publish') {
		$event = 'new_article';
	// if its updated article
	} else if($post_before -> post_status === 'publish' && $post_after -> post_status === 'publish') {
		$event = 'updated_article';
	} else {
		return;
	}
	if($event === null) {
		return;
	}
	$response = wp_remote_post('http://localhost:3000/api/event', array(
		'method' => 'POST',
		'headers' => array('token' => 12345),
		'body' => array('eventType' => $event, 'postId' => $post_ID )
	)); 
}
?>
