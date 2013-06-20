# wp-heartbeat-notify

#### WordPress realtime new post notifications based on hearbeat API

Based on **WordPress 3.6 heartbeat API**, Wp Hearbeat Notify, display a realtime custom message to your visitor each time a new post is published with a link redirecting to it. Still in beta version, this plugin has been **full tested only on WordPress 3.6-beta3**.

## Install

Just upload `wp-heartbeat-notify` to your WordPress `wp-content/plugin/` folder. Go to *Plugins->Installed plugins* WordPress menu and activate it. That's it... You just have to write a new article to see it in action (Clearly, the notification will not be displayed to the user who created the event. So, for the purposes of testing, I suggest you open another browser where you're logged in).

## Purpose

This is probably the first, or at least one of the first WordPress plugin that makes use of the Hearbeat API. Its purpose is almost purely didactic and I hope that the solution that I have implemented it can be useful for developing new ideas.

The plugin, however, is fully functional and can increase the functionality with a few lines of code, as described below.

## Under the hood

The functioning of the plugin is quite simple.

* All scripts and styles necessary for the functioning of hearbeat are queued
* To generate a notification you hook onto an action or a filter of WordPress and you create a transient that match the length of the heartbeat rate
* wp-heartbeat-notify, every few seconds, check the presence of transient and output the content as a notification

Easy... Isn't it?

## Add new notice

To add a new notice, as stated before, you just have to hook into an action or filter and run `Wp_Hearbeat_Notify::notify( $args )` as in the following example:

### Add a notice on registered users comments 

```php

// Let's hook into Comment publication
add_filter ( 'comment_post', 'notify_new_comment' );
function notify_new_comment( $comment_id ) {
  
  // Retrieve the comment
  $comment = get_comment( $comment_id );

  // Check if the user is registered
  if ( ! $comment->user_id > 0 )
		return;
  
  // Get the comment link
  $comment_link = get_comment_link( $comment_id ); 

  // Here's the magic
	Wp_Hearbeat_Notify::notify( array(
		'title'		=>		'New Comment by ' . $comment->comment_author,
		'content'	=>	 	'There\'s a new comment, why don\'t you <a href="' . $comment_link . '">give it</a> a look?',
		'type'		=>		'info'
	) );
	
}
```

## Support and contacts

If you need support you can find me on [twitter](https://twitter.com/Micc1983) or comment on the dedicated page on my [website](http://codeb.it/).
