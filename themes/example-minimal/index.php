<?php
get_header();
?>

	<main id="content">

		<?php
		if ( have_posts() ) {
			if ( is_archive() ) {
				?>
				<header class="page-header">
					<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
					?>
				</header>
				<?php
			}
			elseif ( is_search() ) {
				?>
				<header class="page-header">
					<h1 class="page-title">
						<?php
						/* translators: %s: search query. */
						printf( esc_html__( 'Search Results for: %s', 'example-minimal' ), '<span>' . get_search_query() . '</span>' );
						?>
					</h1>
				</header>
				<?php
			}

			while ( have_posts() ) {
				the_post();

				if ( is_search() ) {
					get_template_part( 'template-parts/content', 'search' );
				}
				else {
					get_template_part( 'template-parts/content', get_post_type() );
				}

				if ( is_single() ) {
					the_post_navigation();
				}

				if ( is_single() || is_page() ) {
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				}
			}

			/*
			The implementation of the_posts_navigation() does not output anything if there is only one page,
			so it is safe to call this even for single posts.
			*/
			the_posts_navigation();
		}
		elseif ( is_404() ) {
			get_template_part( 'template-parts/content', '404' );
		}
		else {
			get_template_part( 'template-parts/content', 'none' );
		}
		?>

	</main>

<?php
get_sidebar();
get_footer();
