<?php get_header(); ?>
<section id="content" class="row"> 
<?php
	// Start the Loop.
	while ( have_posts() ) : the_post();

		// Include the page content template.
		get_template_part( 'content', 'page' );
	endwhile;
?>
</section>
<?php get_footer(); ?>