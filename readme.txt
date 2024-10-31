=== MultiSite Engage user & Anti spam by RetinaPost ===
Contributors: Dan Negrea
Donate link: http://www.RetinaPost.com
Tags: anti-spam, antispam, captcha, comments, engage, engagement, excerpt, form, links, more, mutisite, network, plugin, post, posts, registration, related post, security, spam, spam free, text
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.0

Make users Read More related posts and protect Wordpress from spam. Displays extracts from blog posts or useful messages.

== Description ==
Make users Read More related posts and protect Wordpress from spam. Displays extracts from blog articles or simple useful messages and engage users to READ MORE while securing your web forms.

Replaces annoying Captchas and non secure client site verification with something better in combating comment spam.

[Demonstration](http://blog.retinapost.com/demo/) |
[Support](http://wordpress.org/tags/retina-post?forum_id=10)|
[Help Translate](http://retinapost.com/contact)|
[Non MultiSite vers](http://wordpress.org/extend/plugins/retina-post/)

This plugin is MultiSite compatible:
- It enables the super admin to control how the plugins is used across the entire network.
- The usual admins can change some options too.

It has been tested and found compatible with: Akismet, Antispam Bee, Quick Cache, W3 Total Cache, WP Super Cache.

For more information please view the [plugin page](http://www.RetinaPost.com/wordpress "WordPress RetinaPost").


== Installation ==

1. Install automatically through the **Plugins**, **Add New**, search for **Retina Post** or Upload and unzipt the archive to the `/wp-content/plugins/` directory
1. Activate the plugin through the **Plugins** menu in WordPress
1. Go to **Engage user & Anti spam Settings** and obtain a free key or click[here](http://retinapost.com/wordpress "Retina API key")

== Requirements ==

* You need a RetinaPost (free) key. Install and get one from RetinaPost **Settings** page. Alternativelly, get one from [here](http://retinapost.com/register/?app=wordpress "Retina API key") **only email and site address needed**
* If you turn on XHTML 1.0 Compliance you and your users will need to have Javascript enabled to see and complete the RetinaPost challenge
* Your theme must have a `do_action('comment_form', $post->ID);` call right before the end of your form (*Right before the closing form tag*). Most themes do.

== Frequently Asked Questions ==

= How can this plugin make users read more? =
After entering a comment the user is encouraged to read other posts.  
If the 'Read more' checkbox is checked, after submitting the comment, the user will be redirected to another article (to the one presented in the challenge).
By presenting a snippet from a post, the user can be engaged to read that post.

Have other questions? Email me or ask me on Twitter @RetinaPost

== Screenshots ==

1. Example: showing a challenge in comment form
2. Network settings: the super admin introduces the keys and can force settings to the entire network
3. The super admin can also set other options to all blogs
4. Each normal admin can change the Basic settings
5. Each normal admin can change the Advanced settings (if they are not forced by super admin)

== Changelog ==
