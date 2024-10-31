<?php
// this is the uninstall handler
// include unregister_setting, delete_option, and other uninstall behavior here

require_once('wp-plugin-retina.php');

wp_plugin_retina::remove_options('Retinapost_options');
wp_plugin_retina::remove_site_options('Retinapost_options');
//wp_plugin_retina::remove_site_options('Retina_spam_detected');

?>