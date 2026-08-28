<?php
/**
 * The sidebar containing the main widget area
 *
 * @package Rev Digital Products
 */

?>

<?php

if ( is_active_sidebar( 'sidebar-1' )) { ?>
	<aside id="secondary" class="widget-area sidebar-width">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside>
<?php } else { ?>
	<aside id="secondary" class="widget-area sidebar-width">
		<div class="default-sidebar">
			<aside id="search-3" class="widget widget_search">
	            <h2 class="widget-title"><?php esc_html_e('Search Anything', 'rev-digital-products'); ?></h2>
	            <?php get_search_form(); ?>
	        </aside>
			<aside id="recent-posts-2" class="widget widget_recent_entries">
				<h2 class="widget-title"><?php esc_html_e('Latest Posts', 'rev-digital-products'); ?></h2>
				<ul>
					<?php
					$rev_digital_products_recent_posts = wp_get_recent_posts(array('numberposts' => 5));
					foreach ($rev_digital_products_recent_posts as $rev_digital_products_post) {
						echo '<li><a href="' . esc_url(get_permalink($rev_digital_products_post['ID'])) . '">' . esc_html($rev_digital_products_post['post_title']) . '</a></li>';
					}
					?>
				</ul>
			</aside>
			<aside id="recent-comments-2" class="widget widget_recent_comments">
				<h2 class="widget-title"><?php esc_html_e('Latest Comments', 'rev-digital-products'); ?></h2>
				<ul id="recentcomments">
					<?php
						$rev_digital_products_comments = get_comments(array(
							'number' => 5,
							'status' => 'approve',
						));
						foreach ($rev_digital_products_comments as $rev_digital_products_comment) {
							echo '<li class="recentcomments">' . esc_html($rev_digital_products_comment->comment_author) . ': <a href="' . esc_url(get_comment_link($rev_digital_products_comment->comment_ID)) . '">' . esc_html(get_the_title($rev_digital_products_comment->comment_post_ID)) . '</a></li>';
						}
					?>
				</ul>
			</aside>
	        <aside id="categories-2" class="widget widget_categories">
	            <h2 class="widget-title"><?php esc_html_e('Explore Categories', 'rev-digital-products'); ?></h2>
	            <ul>
	                <?php
						wp_list_categories(array(
							'title_li' => '',
						));
	                ?>
	            </ul>
	        </aside>
	        <aside id="archives-2" class="widget widget_archive">
	            <h2 class="widget-title"><?php esc_html_e('Blog Archives', 'rev-digital-products'); ?></h2>
	            <ul>
					<?php
						wp_get_archives(array(
							'type' => 'postbypost',
							'format' => 'html',
						));
					?>
	        	</ul>
	       </aside>
	        <aside id="pages-2" class="widget widget_pages">
	            <h2 class="widget-title"><?php esc_html_e('Explore Our Pages', 'rev-digital-products'); ?></h2>
	            <ul>
	                <?php
						wp_list_pages(array(
							'title_li' => '',
						));
	                ?>
	            </ul>
	        </aside>
		   <aside id="calendar-2" class="widget widget_calendar">
	            <h2 class="widget-title"><?php esc_html_e('Calender', 'rev-digital-products'); ?></h2>
	            <?php get_calendar(); ?>
	       </aside>
	   </div>
	</aside>
<?php } ?>