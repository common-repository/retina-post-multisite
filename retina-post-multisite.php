<?php
/*
Plugin Name: Engage user & Anti spam by RetinaPost
Plugin URI: http://www.RetinaPost.com/wordpress
Description: <strong>Make users read more like related posts</strong>. Displays extracts from blog articles or simple useful messages were the user reads them. Display a READ MORE checkbox after comment form. <strong>Protect your blog from comment spam</strong> and false registered accounts. Replaces annoying Captchas and easy to pass client site verification.
Version: 1.0
Author: Dan Negrea
*/

define('RETINA_CREATED', true);

require_once('wp-retina.php');
$wp_retina = new wp_retina('Retinapost_options');

?>