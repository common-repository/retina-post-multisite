<?php
if (defined('RETINA_CREATED') === false)
	die('no direct access');
	 
if (isset($_POST['retinapost_network_options_form_submited'])) {
	$site_options= array(
	'public_key'		=>trim($_POST['public_key']), 
	'private_key'		=>trim($_POST['private_key']),	
	'show_in_comments'	=>(bool) $_POST['show_in_comments'],
	'show_in_registration'=>(bool) $_POST['show_in_registration'],
	'xhtml_compliance'	=>(bool) $_POST['xhtml_compliance']
	);
	/* Update site options */
	$this->update_site_options($this->options_name, $site_options);
}

if (isset($_POST['retinapost_blog_options_submited'])) {
	array_walk_recursive($_POST, create_function('&$val', '$val = stripslashes($val); $val = trim($val);'));
	$options= array(
	'call_to_action'	=>$_POST['call_to_action'],
	'incorrect_response_error'=>$_POST['incorrect_response_error'], 
	'readmore_show' 	=>(bool) $_POST['readmore_show'], 
	'readmore_message'	=>$_POST['readmore_message'], 
	
	'show_in_comments'	=>(bool) $_POST['show_in_comments'],
	'bypass_for_registered_users'=>(bool) $_POST['bypass_for_registered_users'],
	'minimum_bypass_level'=> $this->validate_dropdown($this->capabilities, 'minimum_bypass_level', $_POST['minimum_bypass_level']),
	
	'type_c'			=>$_POST['type_c']=='feed'?'feed':'text',
	'custom_text_c'	=>$_POST['custom_text_c'],
	'custom_link_c'		=>$_POST['custom_link_c'],
	'tab_index_c'		=>$_POST['tab_index_c'],
	
	'show_in_registration'=>(bool) $_POST['show_in_registration'],
	'type_r'			=>$_POST['type_r']=='feed'?'feed':'text',
	'custom_text_r'	=>$_POST['custom_text_r'],
	'custom_link_r'		=>$_POST['custom_link_r'],
	'tab_index_r'		=>$_POST['tab_index_r']			
	);
	
	/* Update all blogs */
	if ($_POST['update_all_blogs'])
	{
		function get_blogs($status='') 
		{
			global $wpdb;
			$query = "SELECT blog_id FROM $wpdb->blogs";
			
			if ($status == 'only public') 
				$query .= " WHERE public='1' ";
			return $wpdb->get_results( $query, ARRAY_A );
		}
	
		$blogs = get_blogs ('only public'); 	
		/* Memmory problem can appear */
		foreach( $blogs as $blog ) 
		{
			update_blog_option( $blog['blog_id'], $this->options_name, $options, FALSE );
		}
	}
	/* Update only this blog */
	else
	{
		$this->update_options($this->options_name, $options);
	}	
}
/* Recompute options */
$this->options = $this->compute_options($this->options_name);

/* For display the current status of site/network options */
$site_options = $this->retrieve_site_options($this->options_name);
	
define("custom_text_LEN", "100");		
define("CUSTOM_LINK_LEN", "250");	
?>
<style type="text/css">
.retina_wrap{
	padding:25px;
}
h3{
	color:#77BB00;
}
.retina_caption{
	display:inline-block;
	min-width:165px;	
	padding:15px 0px 10px 10px;
}
.retina_caption_small{
	display:inline-block;
	min-width:125px;	
	padding:5px 0px 5px 10px;
}
.retina_header{
	display:inline-block;
	width:215px;	
	padding:5px 0px 25px 10px;
	font-size:1.2em
}
.retina_warning{	
	color:#ee7700;
}
.inline-block{
	display:inline-block;	
}
.right{
	margin-left:50px;
	padding-left:50px;
	border-left: 2px dotted #999999;
}
.hr-1{
	margin:10px;margin-left:200px; text-align: left; width: 25%;
}
.hr-2{
	margin:25px;margin-left:100px; text-align:left; width:25%;
}
.tweet_me{
	margin-left: 30px;
    vertical-align: middle;
	width:100px;
}
.support_buttons{
	display:inline-block;
	margin-left: 30px;
	vertical-align: middle;
}
.support_buttons a{
	display:block;
	width:100px;
	font-size: 14px;
	text-align: center;
	text-decoration:none;
	color: #050505;
	margin-top:3px;
	padding: 3px 10px;
	background: -moz-linear-gradient(top, #e7ffa6 0%,#cee342);
	background: -webkit-gradient( linear, left top, left bottom, from(#e7ffa6), to(#cee342));
	border-radius: 14px;
	border: 1px solid #804600;
	text-shadow: 0px -1px 0px rgba(000,000,000,0.2), 0px 1px 0px rgba(255,255,255,0.4);
}
.read_more_button {
	font-size: 11px;
	text-decoration:none;
	color: #000000;
	padding: 5px 6px;
	background: -moz-linear-gradient( top, #f2f2f2 0%,#e3e3e3 50%,#d1d1d1);
	background: -webkit-gradient(linear, left top, left bottom,from(#f2f2f2),color-stop(0.50, #e3e3e3),	to(#d1d1d1));
	border-radius: 7px;
	border: 1px solid #919191;
	text-shadow: 0px 1px 0px rgba(255,255,255,1),0px 1px 0px rgba(255,255,255,1);
}

.get_key_p{
	width:250px;
	display:inline-block;
	word-spaceing:2px;
}
.get_key_small{
	font-size:0.9em;
}
</style>
<script type="text/javascript">
	function set_visible(id, visible)
	{ document.getElementById( id ).style.display = visible; }
	function set_value(id, value)
	{ document.getElementById( id ).value = value; }
	function get_value(id)
	{ return document.getElementById( id ).value; }
</script>

<div class="retina_wrap">
   <h2><?php _e('Engage user & Anti spam by Retina Post', 'retina-post'); ?></h2>
   
   <h3 class="inline-block">
	<?php _e('Runs immediately after you have keys without any configuration.', 'retina-post'); ?>
	<br/>
	<?php _e('Keep it on to stop spam and invite users to read more.', 'retina-post'); ?>
   </h3>
   <a href="http://twitter.com/home?status=Great%20WordPress%20plugin%3A%20Engage%20user%20and%20Anti%20spam%20Retina%20Post%20http%3A%2F%2Fretinapost.com%20%23engage%20%23wordpress%20%23plugin" target="_blank"><img class="tweet_me" src="<?php echo WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); ?>tweet_me.png" alt="Tweet me" border="0" /></a>
   <div class="support_buttons">
	<a href="http://wordpress.org/tags/retina-post?forum_id=10" target="_blank"><?php _e('Support Forum', 'retina-post'); ?></a>
	<a href="http://retinapost.com/contact/" target="_blank"><?php _e('Contact me', 'retina-post'); ?></a>
   </div>
   

   <?php if ($this->keys_missing()) { ?>	 
<form id="get_keys_form" target="_blank" id="retina_get_key" action="http://retinapost.com/register/index.php" method="post">	
		<input type="hidden" name="domain" id="generated_public_key" value="<?php echo $this->get_domain(); ?>"/>
		<input type="hidden" name="privatekey" id="generated_private_key" value="<?php echo $this->generate_private_key(get_bloginfo('admin_email')); ?>"/>
		<input type="hidden" name="app" value="wordpress"/>
		<input type="hidden" name="submited" value="true"/>
		<input type="hidden" name="close_window" value="true"/>
		<br/><br/>
			<b> <?php _e('Get automatically the keys', 'retina-post'); ?></b> &nbsp;&nbsp;&nbsp;
			<input type="button" class="button-primary" value="<?php _e(' Get keys ', 'retina-post'); ?>" onclick="new_keys_generated()"/>
			
			<p class="get_key_small">
				<?php _e('Use ', 'retina-post'); ?>
				<input type="text" name="email" size="20" value="<?php echo get_bloginfo('admin_email'); ?>" />
				<?php _e('for registration', 'retina-post'); ?>
			</p>						
		<br/><br/>
		<span onclick="set_visible('network_options_form','block'); " style="cursor:pointer;font-size:12px"><b>&gt;&gt;</b> <?php _e('click here if you have keys', 'retina-post'); ?></span>
		<br/><br/>
	<script type="text/javascript">
		function new_keys_generated()
		{
			<?php /* If new keys generated */ ?>
			if (get_value('generated_private_key')!='') 
			{
				set_value('public_key', get_value('generated_public_key') );
				set_value('private_key', get_value('generated_private_key') );
				document.getElementById('get_keys_form').submit();
				document.getElementById('network_options_form').submit();
				
				<?php /* Just in case submit not working */ ?>
				set_visible('network_options_form','block');
				wait(1000);
				alert('<?php _e('Please Save!', 'retina-post'); ?>');
			}
			else 
			{
				alert('<?php _e('Please Get Key from retinapost.com!', 'retina-post'); ?>');
			}				
		}
   </script>
 </form>
 
<form id="network_options_form" method="post" action="" style="display:none">
   <?php } else { ?>
<form id="network_options_form" method="post" action="">
   <?php } ?>
   <input type="hidden" name="retinapost_network_options_form_submited" value="true" />	
   <h2><?php _e('Network Setup', 'retina-post'); ?></h2> 
	
	<!-- Keys Container -->
	<div id="keys_container" class="inline-block">
		<div>
			<div class="retina_caption"><?php _e('Public Key', 'retina-post'); ?></div>
			<input type="text" name="public_key" id="public_key" size="40" value="<?php echo $this->options['public_key']; ?>"/>
		</div>     
		<div>
			<div class="retina_caption"><?php _e('Private Key', 'retina-post'); ?></div>
			<input type="text" name="private_key" id="private_key" size="40" value="<?php echo $this->options['private_key']; ?>" />
		</div>
	</div>
	<div class="inline-block right">
		 <div>
			<div class="retina_caption"><?php _e('Protect all sites (force)', 'retina-post'); ?></div>
			<input type="checkbox" name="show_in_comments" value="1" <?php checked(1, $site_options['show_in_comments']); ?> id="show_in_comments2"/>
			<label for="show_in_comments2"><?php _e('comment forms ', 'retina-post'); ?></label>
			&nbsp;&nbsp;
			<input type="checkbox" name="show_in_registration" value="1" <?php checked(1, $site_options['show_in_registration']); ?> id="show_in_registration2" />
			<label for="show_in_registration2"><?php _e('registration forms ', 'retina-post'); ?></label>
		 </div>
		 <div>
			<div class="retina_caption"><?php _e('Standards Compliance', 'retina-post'); ?></div>
			<input type="checkbox" name="xhtml_compliance" value="1" <?php checked(1, $this->options['xhtml_compliance']); ?> id="xhtml_compliance2" />
			<label for="xhtml_compliance2"><?php _e('Produce XHTML 1.0 Strict Compliant Code', 'retina-post'); ?></label>
		 </div>
	</div>
	<br/>
	<p class="submit"><input type="submit" class="button-primary" title="<?php _e('Save Network Options') ?>" value="<?php _e('Save Network Options') ?> &raquo;" /></p>
</form>
   
<br/>
    <hr size="2" class="hr-2"/>
<br/>
	
<form id="blog_options_form" method="post" action="">
	<input type="hidden" name="retinapost_blog_options_submited" value="true" />
	<h2><?php _e('Basic Setup', 'retina-post'); ?></h2>
	
	<div class="inline-block">
		<div>
			<div class="retina_caption_small"><?php _e('Short description', 'retina-post'); ?></div>
			<input type="text" name="call_to_action" size="25" value="<?php echo $this->options['call_to_action']; ?>" />
		</div>		
		<div>
			<div class="retina_caption_small"><?php _e('Incorrect message', 'retina-post'); ?></div>
			<input type="text" name="incorrect_response_error" size="25" value="<?php echo $this->options['incorrect_response_error']; ?>" />
		</div>		
		<div>
			<div class="retina_caption_small"><?php _e('Read more message', 'retina-post'); ?></div>
			<input type="text"  name="readmore_message" size="25" value="<?php echo $this->options['readmore_message']; ?>" />
		</div>		
	</div>
	<br/>
	
	<h2><?php _e('Advance Setup', 'retina-post'); ?></h2>
	
	<!-- COMMENT SETTINGS -->

	<div>
		<div class="retina_header"><label for="show_in_comments"><?php _e('Enable for comments form', 'retina-post'); ?></label></div>
        <input type="checkbox" name="show_in_comments" value="1" <?php checked(1, $this->options['show_in_comments']); ?> id="show_in_comments" />    
		&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
		<label for="readmore_show"><?php _e('Show ', 'retina-post'); ?><b><?php _e('Read More', 'retina-post'); ?></b></label>
		<input type="checkbox" name="readmore_show" value="1" <?php checked(1, $this->options['readmore_show']); ?> id="readmore_show"/>
	</div>	
	
    <div class="retina_caption"><?php _e('Advanced Options', 'retina-post'); ?> </div><input type="button"id="settings_visible_c" style="display:none" value="<?php _e('Show/Hide Options', 'retina-post'); ?>"/>
	
	<div id="settings_c">	
    <div>
		<div class="retina_caption"><?php _e('Target', 'retina-post'); ?></div>
        <input type="checkbox" name="bypass_for_registered_users" value="1" <?php checked( 1, $this->options['bypass_for_registered_users'] ); ?> id="bypass_for_registered_users" />
        <label for="bypass_for_registered_users"><?php _e('Hide for Registered Users who can', 'retina-post'); ?></label>
               <?php $this->capabilities_dropdown(); ?>
	</div>
	
	<div>
		<div class="retina_caption">
			<b><?php _e('In comment form promote ', 'retina-post'); ?></b>
		</div>
		<div id="buttons_c" style="display:none">
			<input type="button" id="button_feed_c" value="<?php _e(' your Blog Posts ', 'retina-post'); ?>" />
			&nbsp;&nbsp;<?php _e('-or-', 'retina-post'); ?>&nbsp;&nbsp;
			<input type="button" id="button_text_c"  value="<?php _e('   a  Message   ', 'retina-post'); ?>" /> 				
		</div>
		<input type="text" name="type_c" id="type_c" title="Type 'feed' or 'text'" value="<?php echo $this->options['type_c']; ?>" />
	</div>
	<br/>
	
	<div id="settings_text_c">
		<div id="settings_custom_text_c">
			<?php /*show error*/ if ( $this->error_in_message_c() && empty($this->options['custom_text_c']))   { ?>
								<div class="retina_warning"><?php _e('Provide a simple text', 'retina-post'); ?></div><?php } ?>	
			<div class="retina_caption"><?php _e('Message', 'retina-post'); ?></div>
			<input type="text" name="custom_text_c" 		size="60" maxlength="<?php echo custom_text_LEN; ?>" value="<?php echo $this->options['custom_text_c']; ?>" />
		</div>		
		
		<div id="settings_text_link_c">
			<div class="retina_caption"><?php _e('Link', 'retina-post'); ?></div>
			<input type="text" name="custom_link_c" 		size="60" maxlength="<?php echo CUSTOM_LINK_LEN; ?>"  value="<?php echo $this->options['custom_link_c']; ?>" />
		</div>
	</div> <!-- end settings_text_c -->
	<div>
		<hr size="1" class="hr-1" />
	</div>
	
	<div id="comment_tab_index">	
		<div class="retina_caption"><b><?php _e('Tab index', 'retina-post'); ?></b></div>
		<input type="text" name="tab_index_c" size="2" maxlength="2" value="<?php echo $this->options['tab_index_c']; ?>" /> 
	</div>	
 
	</div>
    <hr size="2" class="hr-2"/>
	
	<!-- REGISTER SETTINGS -->

	<div>
		<div class="retina_header"><label for="show_in_registration"><?php _e('Enable for registration form', 'retina-post'); ?></label></div>
        <input type="checkbox" name="show_in_registration" value="1" <?php checked(1, $this->options['show_in_registration']); ?> id="show_in_registration" />        
	</div>		
	  
    <div class="retina_caption"><?php _e('Advanced Options', 'retina-post'); ?></div><input type="button"id="settings_visible_r" style="display:none" value="<?php _e('Show/Hide Options', 'retina-post'); ?>"/>
	<div id="settings_r">
	
	<div>
		<div class="retina_caption">
			<b><?php _e('In register form promote ', 'retina-post'); ?></b>
		</div>
		<div id="buttons_r" style="display:none">
			<input type="button" id="button_feed_r" value="<?php _e(' your Blog Posts ', 'retina-post'); ?>" />
			&nbsp;&nbsp;<?php _e('-or-', 'retina-post'); ?>&nbsp;&nbsp;
			<input type="button" id="button_text_r"  value="<?php _e('   a  Message   ', 'retina-post'); ?>" /> 		 		
		</div>
		<input type="text" name="type_r" id="type_r" title="Type 'feed' or 'text'" value="<?php echo $this->options['type_r']; ?>"/>
	</div>
	<br/>	

	<div id="settings_text_r">
		<div id="settings_custom_text_r">
			<?php /*show error*/ if ( $this->error_in_message_r() && empty($this->options['custom_text_r'])) { ?>
								<div class="retina_warning"><?php _e('Provide a simple text', 'retina-post'); ?></div><?php } ?>
			<div class="retina_caption"><?php _e('Message', 'retina-post'); ?></div>
			<input type="text" name="custom_text_r" 		size="60" maxlength="<?php echo custom_text_LEN; ?>" value="<?php echo $this->options['custom_text_r']; ?>" />	<small><?php echo custom_text_LEN; _e(' max characters','retina-post');?> </small>
		</div>		 
		
		<div id="settings_text_link_r">
			<div class="retina_caption"><?php _e('Link', 'retina-post'); ?></div>
			<input type="text" name="custom_link_r" 		size="60" maxlength="<?php echo CUSTOM_LINK_LEN; ?>"  value="<?php echo $this->options['custom_link_r']; ?>" />		<small><?php echo CUSTOM_LINK_LEN; _e(' max characters','retina-post');?></small>
		</div>
	</div> <!-- end settings_text_r -->
	<div>
		<hr size="1" class="hr-1" />
	</div>
	
	<div id="register_tab_index">	
		<div class="retina_caption"><b><?php _e('Tab index', 'retina-post'); ?></b></div>
		<input type="text" name="tab_index_r" size="2" maxlength="2" value="<?php echo $this->options['tab_index_r']; ?>" /> 
	</div>

   	</div>
    <hr size="2" class="hr-2"/>
	  
	<!-- OTHER OPTIONS --> 
	<div>
		<div class="retina_header"><?php _e('Other Options', 'retina-post'); ?></div>
	</div>

	<div>
		<div class="retina_caption"><?php _e('Standards Compliance', 'retina-post'); ?></div>
		<input type="checkbox" name="xhtml_compliance" value="1" <?php checked(1, $this->options['xhtml_compliance']); ?> id ="xhtml_compliance"/>
        <label for="xhtml_compliance"><?php _e('Produce XHTML 1.0 Strict Compliant Code', 'retina-post'); ?></label>
	</div>
    
	<p class="submit"><input type="submit" class="button-primary" title="<?php _e('Save Blog Options') ?>" value="<?php _e('Save Blog Options') ?> &raquo;" />			
		&nbsp;&nbsp;&nbsp;
		<input type="checkbox" name="update_all_blogs" value="1" id="update_all_blogs" />
		<label for="update_all_blogs"><?php _e('Update options to all blogs', 'retina-post'); ?></label>
	</p>
   </form>
   
    <script type="text/javascript">
		//multisite set_visible('keys_container', 		'none');
		
		set_visible('settings_visible_c',	'inline-block');
		set_visible('settings_visible_r',	'inline-block');

						
		<?php if (!$this->error_in_message_c() ){?>
			set_visible('settings_c',	'none');
		<?php } if (!$this->error_in_message_r() ){?>
			set_visible('settings_r',	'none');
		<?php } ?>
				
		set_visible('settings_text_c',	'none');
		set_visible('buttons_c',		'inline-block');
		set_visible('type_c',			'none');
		
		set_visible('settings_text_r',	'none');
		set_visible('buttons_r',		'inline-block');
		set_visible('type_r',			'none');

		jQuery(function($){	
			$('#settings_visible_c').click(function() {	
				$('#settings_c').toggle("slow");		
			return false;
			});
			$('#settings_visible_r').click(function() {	
				$('#settings_r').toggle("slow");		
			return false;
			});
			
			// Show comment feed
			$('#button_feed_c').click(function() {	
				$('#settings_text_c').hide();
				document.getElementById('button_feed_c').style.border='solid 2px #50C000'; 
				document.getElementById('button_text_c').style.border='';
				document.getElementById('type_c').value='feed'; 	
			return false;
			}); 
			
			// Show comment text
			$('#button_text_c').click(function() {
				$('#settings_text_c').show("slow");	
				document.getElementById('button_text_c').style.border='solid 2px #50C000'; 
				document.getElementById('button_feed_c').style.border='';
				document.getElementById('type_c').value='text';
			return false;
			}); 
			
			// Show register feed
			$('#button_feed_r').click(function() {
				$('#settings_text_r').hide();
				document.getElementById('button_feed_r').style.border='solid 2px #50C000'; 
				document.getElementById('button_text_r').style.border='';
				document.getElementById('type_r').value='feed';				
			return false;
			}); 
			
			// Show register text
			$('#button_text_r').click(function() {
				$('#settings_text_r').show("slow");	
				document.getElementById('button_text_r').style.border='solid 2px #50C000'; 
				document.getElementById('button_feed_r').style.border='';
				document.getElementById('type_r').value='text';				
			return false;
			}); 		
			
			<?php if($this->options['type_c']=='feed') { ?>			
				$('#button_feed_c').click();
			<?php } else if ($this->options['type_c']=='text') { ?>			
				$('#button_text_c').click();
			<?php } ?>
			
			
			<?php if($this->options['type_r']=='feed') { ?>
				$('#button_feed_r').click();
			<?php } else if ($this->options['type_r']=='text') { ?>
				$('#button_text_r').click();
			<?php } ?>			
		});	
   </script>
</div>