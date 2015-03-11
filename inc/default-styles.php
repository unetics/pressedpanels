<?php

/**
 * Register the custom styles scripts
 */
function siteorigin_panels_default_styles_register_scripts(){
	$js_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	wp_register_script('siteorigin-panels-front-styles', plugin_dir_url(SITEORIGIN_PANELS_BASE_FILE) . 'js/styling' . $js_suffix . '.js', array('jquery'), SITEORIGIN_PANELS_VERSION );
}
add_action('wp_enqueue_scripts', 'siteorigin_panels_default_styles_register_scripts', 5);

/**
 * Class for handling all the default styling.
 *
 * Class SiteOrigin_Panels_Default_Styling
 */
class SiteOrigin_Panels_Default_Styling {

	static function init() {
		// Adding all the fields
		add_filter('siteorigin_panels_row_style_fields', array('SiteOrigin_Panels_Default_Styling', 'row_style_fields' ) );
		add_filter('siteorigin_panels_widget_style_fields', array('SiteOrigin_Panels_Default_Styling', 'widget_style_fields' ) );

		// Filter the row style
		add_filter('siteorigin_panels_row_style_attributes', array('SiteOrigin_Panels_Default_Styling', 'row_style_attributes' ), 10, 2);
		add_filter('siteorigin_panels_cell_style_attributes', array('SiteOrigin_Panels_Default_Styling', 'cell_style_attributes' ), 10, 2);
		add_filter('siteorigin_panels_widget_style_attributes', array('SiteOrigin_Panels_Default_Styling', 'widget_style_attributes' ), 10, 2);

		// Main filter to add any custom CSS.
		add_filter('siteorigin_panels_css_object', array('SiteOrigin_Panels_Default_Styling', 'filter_css_object' ), 10, 3);

		// Filtering specific attributes
		add_filter('siteorigin_panels_css_row_margin_bottom', array('SiteOrigin_Panels_Default_Styling', 'filter_row_bottom_margin' ), 10, 2);
		add_filter('siteorigin_panels_css_row_gutter', array('SiteOrigin_Panels_Default_Styling', 'filter_row_gutter' ), 10, 2);
	}

	static function row_style_fields($fields) {
		// Add the attribute fields

		$fields['class'] = array(
			'name' => 'Row Class',
			'type' => 'text',
			'group' => 'attributes',
			'description' => 'A CSS class',
			'priority' => 5,
		);

		$fields['cell_class'] = array(
			'name' => 'Cell Class',
			'type' => 'text',
			'group' => 'attributes',
			'description' => 'Class added to all cells in this row.',
			'priority' => 6,
		);

		$fields['row_css'] = array(
			'name' => 'CSS Styles',
			'type' => 'code',
			'group' => 'attributes',
			'description' => 'CSS Styles, given as one per row.',
			'priority' => 10,
		);

		// Add the layout fields

		$fields['bottom_margin'] = array(
			'name' => 'Bottom Margin',
			'type' => 'measurement',
			'group' => 'layout',
			'description' => 'Space below the row.',
			'priority' => 5,
		);

		$fields['gutter'] = array(
			'name' => 'Gutter',
			'type' => 'measurement',
			'group' => 'layout',
			'description' => 'Amount of space between columns.',
			'priority' => 6,
		);

		$fields['padding'] = array(
			'name' => 'Padding',
			'type' => 'measurement',
			'group' => 'layout',
			'description' => 'Padding around the entire row.',
			'priority' => 7,
		);

		$fields['row_stretch'] = array(
			'name' => 'Row Layout',
			'type' => 'select',
			'group' => 'layout',
			'options' => array(
				'standard' => 'Standard',
				'full' => 'Full Width',
				'full-stretched' => 'Full Width Stretched',
			),
			'priority' => 10,
		);

		// How lets add the design fields

		$fields['background'] = array(
			'name' => 'Background Color',
			'type' => 'color',
			'group' => 'design',
			'description' => 'Background color of the row.',
			'priority' => 5,
		);

		$fields['background_image_attachment'] = array(
			'name' => 'Background Image',
			'type' => 'image',
			'group' => 'design',
			'description' => 'Background image of the row.',
			'priority' => 6,
		);

		$fields['background_display'] = array(
			'name' => 'Background Image Display',
			'type' => 'select',
			'group' => 'design',
			'options' => array(
				'tile' => 'Tiled Image',
				'cover' => 'Cover',
				'center' => 'Centered, with original size',
			),
			'description' => 'How the background image is displayed.',
			'priority' => 7,
		);

		$fields['border_color'] = array(
			'name' => 'Border Color',
			'type' => 'color',
			'group' => 'design',
			'description' => 'Border color of the row.',
			'priority' => 10,
		);

		return $fields;
	}

	static function widget_style_fields($fields) {
		$fields['class'] = array(
			'name' => 'Widget Class',
			'type' => 'text',
			'group' => 'attributes',
			'description' => 'A CSS class',
			'priority' => 5,
		);

		$fields['widget_css'] = array(
			'name' => 'CSS Styles',
			'type' => 'code',
			'group' => 'attributes',
			'description' => 'CSS Styles, given as one per row.',
			'priority' => 10,
		);

		$fields['padding'] = array(
			'name' => 'Padding',
			'type' => 'measurement',
			'group' => 'layout',
			'description' => 'Padding around the entire widget.',
			'priority' => 7,
		);

		// How lets add the design fields

		$fields['background'] = array(
			'name' => 'Background Color',
			'type' => 'color',
			'group' => 'design',
			'description' => 'Background color of the widget.',
			'priority' => 5,
		);

		$fields['background_image_attachment'] = array(
			'name' => 'Background Image',
			'type' => 'image',
			'group' => 'design',
			'description' => 'Background image of the widget.',
			'priority' => 6,
		);

		$fields['background_display'] = array(
			'name' => 'Background Image Display',
			'type' => 'select',
			'group' => 'design',
			'options' => array(
				'tile' => 'Tiled Image',
				'cover' => 'Cover',
				'center' => 'Centered, with original size',
			),
			'description' => 'How the background image is displayed.',
			'priority' => 7,
		);

		$fields['border_color'] = array(
			'name' => 'Border Color',
			'type' => 'color',
			'group' => 'design',
			'description' =>'Border color of the widget.',
			'priority' => 10,
		);

		$fields['font_color'] = array(
			'name' => 'Font Color',
			'type' => 'color',
			'group' => 'design',
			'description' => 'Color of text inside this widget.',
			'priority' => 15,
		);

		return $fields;
	}

	static function row_style_attributes( $attributes, $args ) {
		$attributes['class'][] ='';
		if( !empty( $args['row_stretch'] ) ) {
			$attributes['class'][] = 'siteorigin-panels-stretch';
			$attributes['class'][] = $args['row_stretch'].'-row';
// 			$attributes['data-stretch-type'] = $args['row_stretch'];
// 			wp_enqueue_script('siteorigin-panels-front-styles');
		}

		if( !empty( $args['class'] ) ) {
			$attributes['class'] = array_merge( $attributes['class'], explode(' ', $args['class']) );
		}

		if( !empty($args['row_css']) ){
			preg_match_all('/(.+?):(.+?);?$/', $args['row_css'], $matches);

			if(!empty($matches[0])){
				for($i = 0; $i < count($matches[0]); $i++) {
					$attributes['style'] .= $matches[1][$i] . ':' . $matches[2][$i] . ';';
				}
			}
		}

		if( !empty( $args['padding'] ) ) {
			$attributes['style'] .= 'padding: ' . esc_attr($args['padding']) . ';';
		}

		if( !empty( $args['background'] ) ) {
			$attributes['style'] .= 'background-color:' . $args['background']. ';';
		}

		if( !empty( $args['background_image_attachment'] ) ) {
			$url = wp_get_attachment_image_src( $args['background_image_attachment'], 'full' );

			if( !empty($url) ) {
				$attributes['style'] .= 'background-image: url(' . $url[0] . ');';
			}

			switch( $args['background_display'] ) {
				case 'tile':
					$attributes['style'] .= 'background-repeat: repeat;';
					break;
				case 'cover':
					$attributes['style'] .= 'background-size: cover;';
					break;
				case 'center':
					$attributes['style'] .= 'background-position: center center; background-repeat: no-repeat;';
					break;
			}
		}

		if( !empty( $args['border_color'] ) ) {
			$attributes['style'] .= 'border: 1px solid ' . $args['border_color']. ';';
		}

		return $attributes;
	}

	static function cell_style_attributes( $attributes, $row_args ) {
		if( !empty( $row_args['cell_class'] ) ) {
			if( empty($attributes['class']) ) $attributes['class'] = array();
			$attributes['class'] = array_merge( $attributes['class'], explode(' ', $row_args['cell_class']) );
		}

		return $attributes;
	}

	static function widget_style_attributes( $attributes, $args ) {
		if( !empty( $args['class'] ) ) {
			if( empty($attributes['class']) ) $attributes['class'] = array();
			$attributes['class'] = array_merge( $attributes['class'], explode(' ', $args['class']) );
		}

		if( !empty($args['widget_css']) ){
			preg_match_all('/(.+?):(.+?);?$/', $args['widget_css'], $matches);

			if(!empty($matches[0])){
				for($i = 0; $i < count($matches[0]); $i++) {
					$attributes['style'] .= $matches[1][$i] . ':' . $matches[2][$i] . ';';
				}
			}
		}

		if( !empty( $args['padding'] ) ) {
			$attributes['style'] .= 'padding: ' . esc_attr($args['padding']) . ';';
		}

		if( !empty( $args['background'] ) ) {
			$attributes['style'] .= 'background-color:' . $args['background']. ';';
		}

		if( !empty( $args['background_image_attachment'] ) ) {
			$url = wp_get_attachment_image_src( $args['background_image_attachment'], 'full' );

			if( !empty($url) ) {
				$attributes['style'] .= 'background-image: url(' . $url[0] . ');';
			}

			switch( $args['background_display'] ) {
				case 'tile':
					$attributes['style'] .= 'background-repeat: repeat;';
					break;
				case 'cover':
					$attributes['style'] .= 'background-size: cover;';
					break;
				case 'center':
					$attributes['style'] .= 'background-position: center center; background-repeat: no-repeat;';
					break;
			}
		}

		if( !empty( $args['border_color'] ) ) {
			$attributes['style'] .= 'border: 1px solid ' . $args['border_color']. ';';
		}

		if( !empty( $args['font_color'] ) ) {
			$attributes['style'] .= 'color: ' . $args['font_color']. ';';
		}

		return $attributes;
	}

	static function filter_css_object( $css, $panels_data, $post_id ) {
		return $css;
	}

	static function filter_row_bottom_margin( $margin, $grid ){
		if( !empty($grid['style']['bottom_margin']) ) {
			$margin = $grid['style']['bottom_margin'];
		}
		return $margin;
	}

	static function filter_row_gutter( $gutter, $grid ) {
		if( !empty($grid['style']['gutter']) ) {
			$gutter = $grid['style']['gutter'];
		}

		return $gutter;
	}

}

SiteOrigin_Panels_Default_Styling::init();