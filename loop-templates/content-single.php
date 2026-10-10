<?php
/**
 * Single post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<div class="category">
			<h5 class="h6"><strong><?php echo get_the_category()[0]->name; ?></strong></h5>
		</div>

		<?php the_title( '<h1 class="h2 entry-title">', '</h1>' ); ?>

		<!-- <div class="entry-meta"> -->

			<?php //understrap_posted_on(); ?>

		<!-- </div>.entry-meta -->

	</header><!-- .entry-header -->

	<div class="entry-content">

		<?php the_content(); ?>

		<div class="share-post-wrap pt-3 pb-4">
			<div class="share-post-title">
				<h6>Share on socials:</h6>
			</div>
			<div class="icon-wrap">
				<div class="social-icons">
					<!-- Facebook -->
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink(get_the_ID())); ?>" target="_blank">
						<img src="https://langley.co.uk/wp-content/themes/langley/inc/images/socials_dkblue.svg#facebook" alt="Facebook">
					</a>&nbsp;
					<!-- Twitter -->
					<a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_permalink(get_the_ID())); ?>" target="_blank">
						<img src="https://langley.co.uk/wp-content/themes/langley/inc/images/socials_dkblue.svg#twitter" alt="Twitter">
					</a>&nbsp;
					<!-- LinkedIn -->
					<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode(get_permalink(get_the_ID())); ?>" target="_blank">
						<img src="https://langley.co.uk/wp-content/themes/langley/inc/images/socials_dkblue.svg#linkedin" alt="LinkedIn">
					</a>
				</div>
			</div>
		</div>

		<div class="back-btn-wrap">
			<a href="<?php echo get_post_type_archive_link('post'); ?>" role="button" class="btn-mulberry">&larr; Back to Journal Articles</a>
		</div>

	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
