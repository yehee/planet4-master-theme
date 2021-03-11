<?php

/**
 * Campaign Exporter Functionality
 *
 * @package P4MT
 */

global $wpdb, $post;

require_once 'exporter-helper.php';

define('CMX_VERSION', '1.0');
$sitename = sanitize_key(get_bloginfo('name'));
if (! empty($sitename)) {
	$sitename .= '.';
}

// Sanitize input.
$post_ids = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_STRING);

$post_ids = explode(',', $post_ids);

if (1 === count($post_ids)) {
	$slug = get_post_field('post_name', $post_ids[0]);
	if (! $slug) {
		$slug = 'P4-Campaign';
	}
	// Add campaign slug in XML filename.
	$filename = $sitename . $slug . '.' . gmdate('Y-m-d') . '.xml';
} else {
	// Add "P4-Campaign" word in XML filename to distinguish between normal WP export and P4 campaign export.
	$filename = $sitename . 'P4-Campaign.' . gmdate('Y-m-d') . '.xml';
}

header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename=' . $filename);
header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);

// Get campaign attachments ids.
$post_ids = get_campaign_attachments($post_ids);

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . "\" ?>\n";

?>
<!-- This is a WordPress eXtended RSS file generated by Planet4 as an export of campaign/s. -->
<!-- It contains information about campaign/s content. -->
<!-- You may use this file to transfer that content from one Planet4 site to another. -->
<!-- This file is not intended to serve as a complete backup of your site. -->

<!-- To import this information into a WordPress site follow these steps: -->
<!-- 1. Log in to that site as an administrator. -->
<!-- 2. Go to Tools: Import in the WordPress admin panel. -->
<!-- 3. Install the "WordPress" importer from the list. -->
<!-- 4. Activate & Run Importer. -->
<!-- 5. Upload this file using the form provided on that page. -->
<!-- 6. You will first be asked to map the authors in this export file to users -->
<!--    on the site. For each author, you may choose to map to an -->
<!--    existing user on the site or to create a new user. -->
<!-- 7. WordPress will then import each of the campaign/s, etc. -->
<!--    contained in this file into your site. -->

<?php the_generator('export'); ?>
<rss version="2.0"
	xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wp="http://wordpress.org/export/1.0/"
>
	<channel>
		<title><?php bloginfo_rss('name'); ?></title>
		<link><?php bloginfo_rss('url'); ?></link>
		<description><?php bloginfo_rss('description'); ?></description>
		<pubDate><?php echo gmdate('D, d M Y H:i:s +0000'); ?></pubDate>
		<language><?php bloginfo_rss('language'); ?></language>
		<wp:wxr_version><?php echo CMX_VERSION; ?></wp:wxr_version>
		<wp:base_site_url><?php echo p4_px_single_post_site_url(); ?></wp:base_site_url>
		<wp:base_blog_url><?php bloginfo_rss('url'); ?></wp:base_blog_url>

	<?php p4_px_single_post_authors_list($post_ids); ?>

		<?php
			do_action('rss2_head');
		?>

		<?php
		if ($post_ids) {
			/**
			 * Global WP Query Object.
			 *
			 * @global WP_Query $wp_query WordPress Query object.
			 */
			global $wp_query;

			// Fake being in the loop.
			$wp_query->in_the_loop = true;

			// Fetch 10 posts at a time rather than loading the entire table into memory.
			while ($next_posts = array_splice($post_ids, 0, 10)) { // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
				$sql = 'SELECT * FROM %1$s WHERE ID IN (' . generate_list_placeholders($next_posts, 2) . ')';

				$prepared_sql = $wpdb->prepare(
					$sql, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
					array_merge([ $wpdb->posts ], $next_posts)
				);
				$posts        = $wpdb->get_results($prepared_sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.WP.GlobalVariablesOverride.Prohibited

				// Begin Loop.
				foreach ($posts as $post) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					setup_postdata($post);

					/** This filter is documented in wp-includes/feed.php */
					$campaign_title = apply_filters('the_title_rss', $post->post_title);

					/**
					 * Filters the post content used for exports.
					 *
					 * @param string $post_content Content of the current campaign.
					 */
					$content = p4_px_single_post_cdata(apply_filters('the_content_export', $post->post_content));

					/**
					 * Filters the post excerpt used for exports.
					 *
					 * @param string $post_excerpt Excerpt for the current campaign.
					 */
					$excerpt = p4_px_single_post_cdata(apply_filters('the_excerpt_export', $post->post_excerpt));

					$is_sticky = is_sticky($post->ID) ? 1 : 0;
					?>
		<item>
			<title><?php echo $campaign_title; ?></title>
			<link><?php the_permalink_rss(); ?></link>
			<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
			<dc:creator><?php echo p4_px_single_post_cdata(get_the_author_meta('login')); ?></dc:creator>
			<guid isPermaLink="false"><?php the_guid(); ?></guid>
			<description></description>
			<content:encoded><?php echo $content; ?></content:encoded>
			<excerpt:encoded><?php echo $excerpt; ?></excerpt:encoded>
			<wp:post_id><?php echo $post->ID; ?></wp:post_id>
			<wp:post_date><?php echo $post->post_date; ?></wp:post_date>
			<wp:post_date_gmt><?php echo $post->post_date_gmt; ?></wp:post_date_gmt>
			<wp:comment_status><?php echo $post->comment_status; ?></wp:comment_status>
			<wp:ping_status><?php echo $post->ping_status; ?></wp:ping_status>
			<wp:post_name><?php echo $post->post_name; ?></wp:post_name>
			<wp:status><?php echo $post->post_status; ?></wp:status>
			<wp:post_parent><?php echo $post->post_parent; ?></wp:post_parent>
			<wp:menu_order><?php echo $post->menu_order; ?></wp:menu_order>
			<wp:post_type><?php echo $post->post_type; ?></wp:post_type>
			<wp:post_password><?php echo $post->post_password; ?></wp:post_password>
			<wp:is_sticky><?php echo $is_sticky; ?></wp:is_sticky>
					<?php if ('attachment' === $post->post_type) : ?>
			<wp:attachment_url><?php echo wp_get_attachment_url($post->ID); ?></wp:attachment_url>
					<?php endif; ?>

					<?php
					$postmeta = get_post_meta($post->ID);
					foreach ($postmeta as $meta_key => $meta_value) :
						if ('_edit_lock' === $meta_key) {
							continue;
						}
						?>
				<wp:postmeta>
					<wp:meta_key><?php echo $meta_key; ?></wp:meta_key>
					<wp:meta_value><?php echo p4_px_single_post_cdata(maybe_serialize($meta_value[0])); ?></wp:meta_value>
				</wp:postmeta>
					<?php endforeach; ?>
		</item>
					<?php
				}
			}
		}
		?>
	</channel>
</rss>
