<?php
/**
 * Kindle Feed Template for displaying Future Posts in a way Kindle can consume.
 *
 * @package WordPress
 */

// The class with set options should already be loaded.
global $kf, $post, $cons_shareFollow;
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

// dump the Share and Follow junk, if present.
if ($cons_shareFollow) {
	remove_filter('the_content', array(&$cons_shareFollow, 'addContent'));
}
query_posts( $kf->query_string_for_posts(array('p'=> $post->ID )));
while( have_posts()) : the_post();
	$post = get_post(get_the_ID(), OBJECT);
	$charset = 'UTF-8'; // Force this
	header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . $charset, true);
	echo '<?xml version="1.0" encoding="'.$charset.'"?'.'>';
?>

<html>
	<head>
		<title><?php the_title(); ?></title>
		<meta name="abstract" content="<?php echo ''
		/* don't want this right now as it messes up display
		  $kf->strip_excerpt(get_the_excerpt());
		*/ ?>"/>
		<meta name="author" content="by <?php the_author(); ?>"/>
		<meta name="dc.date.issued" content="<?php the_date('Ymd'); ?>"/>
	</head>
	<body>
<?php
  $content = get_the_content_feed();
	print $kf->strip_content($content);
	// Add the author bio
	print '<br/>';
	printf('<p align="left" width="0">%s</p>', get_the_author_meta('description'));
?>
	</body>
</html>
<?php
endwhile;
?>
