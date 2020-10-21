<?php

namespace Example_Minimal;

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
	<header class="entry-header">
		<?php
		if ( is_singular() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		}
		else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}

		if ( 'post' === get_post_type() ) {
			?>
			<div class="entry-meta">
				<?php
				posted_on();
				posted_by();
				?>
			</div>
			<?php
		}
		?>
	</header>

	<?php
	post_thumbnail();

	if ( is_search() ) {
		?>
		<div class="entry-summary">
			<?php
			the_excerpt();
			?>
		</div>
		<?php
	}
	else {
		?>
		<div class="entry-content">
			<?php
			the_content();
			wp_link_pages();
			?>
		</div>
		<?php
	}
	?>

	<footer class="entry-footer">
		<?php
		entry_footer();
		?>
	</footer>
</article>
