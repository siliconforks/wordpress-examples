<?php

if ( post_password_required() ) {
	return;
}
?>

<div id="comments">

	<?php
	if ( have_comments() ) {
		?>
		<h2>
			<?php
			esc_html_e( 'Comments', 'example-minimal' );
			?>
		</h2>

		<?php
		the_comments_navigation();
		?>

		<ol>
			<?php
			wp_list_comments(
				array(
					'style' => 'ol',
					'short_ping' => TRUE,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation();

		if ( ! comments_open() ) {
			?>
			<p class="no-comments">
				<?php
				esc_html_e( 'Comments are closed.', 'example-minimal' );
				?>
			</p>
			<?php
		}
	}

	comment_form();
	?>

</div>
