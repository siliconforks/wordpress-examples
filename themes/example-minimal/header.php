<!doctype html>
<html <?php language_attributes(); ?> >
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
	wp_head();
	?>
</head>

<body <?php body_class(); ?> >
<?php
wp_body_open();
?>
<div>
	<a class="skip-link screen-reader-text" href="#content">
		<?php
		esc_html_e( 'Skip to content', 'example-minimal' );
		?>
	</a>

	<header class="site-header">
		<div class="site-branding">
			<?php
			if ( is_front_page() || is_home() ) {
				?>
				<h1 class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php
						bloginfo( 'name' );
						?>
					</a>
				</h1>
				<?php
			}
			else {
				?>
				<p class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php
						bloginfo( 'name' );
						?>
					</a>
				</p>
				<?php
			}
			?>
		</div>

		<nav class="site-header-navigation">
			<button class="menu-toggle" aria-controls="site-header-navigation-menu" aria-expanded="false">
				<?php
				esc_html_e( 'Menu', 'example-minimal' );
				?>
			</button>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'menu_id' => 'site-header-navigation-menu',
					'container' => FALSE,

					/*
					The default callback is wp_page_menu();
					unfortunately the structure of this does not match the structure of the non-fallback menu.
					(It is wrapped in a <div> element even though we set 'container' to FALSE above.)
					It is best to just disable the fallback entirely.
					*/
					'fallback_cb' => FALSE,
				)
			);
			?>
		</nav>
	</header>
