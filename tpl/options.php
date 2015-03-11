<?php $settings = siteorigin_panels_setting(); ?>

<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2>Page Builder</h2>

	<form action="<?php echo admin_url( 'options-general.php?page=siteorigin_panels' ) ?>" method="POST">

		<h3>General</h3>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><strong>Post Types</strong></th>
					<td>
						<?php siteorigin_panels_options_field_post_types($settings['post-types']) ?>
					</td>
				</tr>

				<?php
				siteorigin_panels_options_field(
					'copy-content',
					$settings['copy-content'],
					'Copy Content',
					'Copy content from Page Builder into the standard content editor.'
				);
				?>

			</tbody>
		</table>

		<h3>Display</h3>
		<table class="form-table">
			<tbody>

				<?php

				siteorigin_panels_options_field(
					'responsive',
					$settings['responsive'],
					'Responsive Layout',
					'Should the layout collapse for mobile devices.'
				);

				siteorigin_panels_options_field(
					'mobile-width',
					$settings['mobile-width'],
					'Mobile Width'
				);

				siteorigin_panels_options_field(
					'margin-bottom',
					$settings['margin-bottom'],
					'Row Bottom Margin'
				);

				siteorigin_panels_options_field(
					'margin-sides',
					$settings['margin-sides'],
					'Cell Side Margins'
				);

				siteorigin_panels_options_field(
					'inline-css',
					$settings['inline-css'],
					'Inline CSS'
				);

				?>

			</tbody>
		</table>


		<?php wp_nonce_field('save_panels_settings'); ?>
		<p class="submit">
			<input type="submit" class="button-primary" value="Save Settings"/>
		</p>

	</form>
</div>