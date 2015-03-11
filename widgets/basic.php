<?php
/**
 * This widget give you the full Page Builder interface inside a widget. Fully nestable.
 * Class SiteOrigin_Panels_Widgets_Builder
 */
class SiteOrigin_Panels_Widgets_Layout extends WP_Widget {
	function __construct() {
		parent::__construct(
			'siteorigin-panels-builder',
			'Layout Builder',
			array(
				'description' => 'A full Page Builder layout as a widget.',
				'panels_title' => false,
			),
			array(
			)
		);
	}

	function widget($args, $instance) {
		if( empty($instance['panels_data']) ) return;

		if( is_string( $instance['panels_data'] ) )
			$instance['panels_data'] = json_decode( $instance['panels_data'], true );
		if(empty($instance['panels_data']['widgets'])) return;

		if( empty( $instance['builder_id'] ) ) $instance['builder_id'] = uniqid();

		echo $args['before_widget'];
		echo siteorigin_panels_render( 'w'.$instance['builder_id'], true, $instance['panels_data'] );
		echo $args['after_widget'];
	}

	function update($new, $old) {
		$new['builder_id'] = uniqid();
		return $new;
	}

	function form($instance){
		$instance = wp_parse_args($instance, array(
			'panels_data' => '',
			'builder_id' => uniqid(),
		) );

		if( !is_string( $instance['panels_data'] ) ) $instance['panels_data'] = json_encode( $instance['panels_data'] );

		?>
		<div class="siteorigin-page-builder-widget siteorigin-panels-builder" id="siteorigin-page-builder-widget-<?php echo esc_attr( $instance['builder_id'] ) ?>" data-builder-id="<?php echo esc_attr( $instance['builder_id'] ) ?>">
			<p><a href="#" class="button-secondary siteorigin-panels-display-builder"><?php _e('Open Builder', 'siteorigin-panels') ?></a></p>

			<input type="hidden" data-panels-filter="json_parse" value="<?php echo esc_attr( $instance['panels_data'] ) ?>" class="panels-data" name="<?php echo $this->get_field_name('panels_data') ?>" />
			<input type="hidden" value="<?php echo esc_attr( $instance['builder_id'] ) ?>" name="<?php echo $this->get_field_name('builder_id') ?>" />
		</div>
		<script type="text/javascript">
			if(typeof jQuery.fn.soPanelsSetupBuilderWidget != 'undefined') {
				jQuery( "#siteorigin-page-builder-widget-<?php echo esc_attr( $instance['builder_id'] ) ?>").soPanelsSetupBuilderWidget();
			}
		</script>
		<?php
	}

}

/**
 * Display a loop of posts.
 *
 * Class SiteOrigin_Panels_Widgets_PostLoop
 */
class SiteOrigin_Panels_Widgets_PostLoop extends WP_Widget{
	function __construct() {
		parent::__construct(
			'siteorigin-panels-postloop',
			'Post Loop',
			array(
				'description' => 'Displays a post loop.',
			)
		);
	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {
		if( empty( $instance['template'] ) ) return;
		if( is_admin() ) return;

		$template = $instance['template'];
		$query_args = $instance;
		unset($query_args['template']);
		unset($query_args['additional']);
		unset($query_args['sticky']);
		unset($query_args['title']);

		$query_args = wp_parse_args($instance['additional'], $query_args);

		global $wp_rewrite;

		if( $wp_rewrite->using_permalinks() ) {

			if( get_query_var('paged') ) {
				// When the widget appears on a sub page.
				$query_args['paged'] = get_query_var('paged');
			}
			elseif( strpos( $_SERVER['REQUEST_URI'], '/page/' ) !== false ) {
				// When the widget appears on the home page.
				preg_match('/\/page\/([0-9]+)\//', $_SERVER['REQUEST_URI'], $matches);
				if(!empty($matches[1])) $query_args['paged'] = intval($matches[1]);
				else $query_args['paged'] = 1;
			}
			else $query_args['paged'] = 1;
		}
		else {
			// Get current page number when we're not using permalinks
			$query_args['paged'] = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
		}

		switch($instance['sticky']){
			case 'ignore' :
				$query_args['ignore_sticky_posts'] = 1;
				break;
			case 'only' :
				$query_args['post__in'] = get_option( 'sticky_posts' );
				break;
			case 'exclude' :
				$query_args['post__not_in'] = get_option( 'sticky_posts' );
				break;
		}

		// Exclude the current post to prevent possible infinite loop

		global $siteorigin_panels_current_post;

		if( !empty($siteorigin_panels_current_post) ){
			if(!empty($query_args['post__not_in'])){
				$query_args['post__not_in'][] = $siteorigin_panels_current_post;
			}
			else {
				$query_args['post__not_in'] = array( $siteorigin_panels_current_post );
			}
		}

		if( !empty($query_args['post__in']) && !is_array($query_args['post__in']) ) {
			$query_args['post__in'] = explode(',', $query_args['post__in']);
			$query_args['post__in'] = array_map('intval', $query_args['post__in']);
		}

		// Create the query
		query_posts($query_args);
		echo $args['before_widget'];

		// Filter the title
		$instance['title'] = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		if ( !empty( $instance['title'] ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}

		add_filter( 'siteorigin_panels_filter_content_enabled', array( 'SiteOrigin_Panels_Widgets_PostLoop', 'remove_content_filter' ) );

		global $more; $old_more = $more; $more = empty($instance['more']);

		if(strpos('/'.$instance['template'], '/content') !== false) {
			while( have_posts() ) {
				the_post();
				locate_template($instance['template'], true, false);
			}
		}
		else {
			locate_template($instance['template'], true, false);
		}

		$more = $old_more;
		remove_filter( 'siteorigin_panels_filter_content_enabled', array( 'SiteOrigin_Panels_Widgets_PostLoop', 'remove_content_filter' ) );

		echo $args['after_widget'];

		// Reset everything
		wp_reset_query();
	}

	/**
	 * @return bool
	 */
	static function remove_content_filter(){
		return false;
	}

	/**
	 * Update the widget
	 *
	 * @param array $new
	 * @param array $old
	 * @return array
	 */
	function update($new, $old){
		$new['more'] = !empty( $new['more'] );
		return $new;
	}

	/**
	 * Get all the existing files
	 *
	 * @return array
	 */
	function get_loop_templates(){
		$templates = array();

		$template_files = array(
			'loop*.php',
			'*/loop*.php',
			'content*.php',
			'*/content*.php',
		);

		$template_dirs = array(get_template_directory(), get_stylesheet_directory());
		$template_dirs = array_unique($template_dirs);
		foreach($template_dirs  as $dir ){
			foreach($template_files as $template_file) {
				foreach((array) glob($dir.'/'.$template_file) as $file) {
					if( file_exists( $file ) ) $templates[] = str_replace($dir.'/', '', $file);
				}
			}
		}

		$templates = array_unique($templates);
		$templates = apply_filters('siteorigin_panels_postloop_templates', $templates);
		sort($templates);

		return $templates;
	}

	/**
	 * Display the form for the post loop.
	 *
	 * @param array $instance
	 * @return string|void
	 */
	function form( $instance ) {
		$instance = wp_parse_args($instance, array(
			'title' => '',
			'template' => 'loop.php',

			// Query args
			'post_type' => 'post',
			'posts_per_page' => '',

			'order' => 'DESC',
			'orderby' => 'date',

			'sticky' => '',

			'additional' => '',
			'more' => false,
		));

		$templates = $this->get_loop_templates();
		if( empty($templates) ) {
			?><p>Your theme doesn't have any post loops.</p><?php
			return;
		}

		// Get all the loop template files
		$post_types = get_post_types(array('public' => true));
		$post_types = array_values($post_types);
		$post_types = array_diff($post_types, array('attachment', 'revision', 'nav_menu_item'));

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ) ?>">Title</label>
			<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'title' ) ?>" id="<?php echo $this->get_field_id( 'title' ) ?>" value="<?php echo esc_attr( $instance['title'] ) ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('template') ?>">Template</label>
			<select id="<?php echo $this->get_field_id( 'template' ) ?>" name="<?php echo $this->get_field_name( 'template' ) ?>">
				<?php foreach($templates as $template) : ?>
					<option value="<?php echo esc_attr($template) ?>" <?php selected($instance['template'], $template) ?>>
						<?php
						$headers = get_file_data( locate_template($template), array(
							'loop_name' => 'Loop Name',
						) );
						echo esc_html(!empty($headers['loop_name']) ? $headers['loop_name'] : $template);
						?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('post_type') ?>">Post Type</label>
			<select id="<?php echo $this->get_field_id( 'post_type' ) ?>" name="<?php echo $this->get_field_name( 'post_type' ) ?>" value="<?php echo esc_attr($instance['post_type']) ?>">
				<?php foreach($post_types as $type) : ?>
					<option value="<?php echo esc_attr($type) ?>" <?php selected($instance['post_type'], $type) ?>><?php echo esc_html($type) ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('posts_per_page') ?>">Posts Per Page</label>
			<input type="text" class="small-text" id="<?php echo $this->get_field_id( 'posts_per_page' ) ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ) ?>" value="<?php echo esc_attr($instance['posts_per_page']) ?>" />
		</p>

		<p>
			<label <?php echo $this->get_field_id('orderby') ?>><?php _e('Order By', 'siteorigin-panels') ?></label>
			<select id="<?php echo $this->get_field_id( 'orderby' ) ?>" name="<?php echo $this->get_field_name( 'orderby' ) ?>" value="<?php echo esc_attr($instance['orderby']) ?>">
				<option value="none" <?php selected($instance['orderby'], 'none') ?>>None</option>
				<option value="ID" <?php selected($instance['orderby'], 'ID') ?>>Post ID</option>
				<option value="author" <?php selected($instance['orderby'], 'author') ?>>Author</option>
				<option value="name" <?php selected($instance['orderby'], 'name') ?>>Name</option>
				<option value="name" <?php selected($instance['orderby'], 'name') ?>>Name</option>
				<option value="date" <?php selected($instance['orderby'], 'date') ?>>Date</option>
				<option value="modified" <?php selected($instance['orderby'], 'modified') ?>>Modified</option>
				<option value="parent" <?php selected($instance['orderby'], 'parent') ?>>Parent</option>
				<option value="rand" <?php selected($instance['orderby'], 'rand') ?>>Random</option>
				<option value="comment_count" <?php selected($instance['orderby'], 'comment_count') ?>>Comment Count'</option>
				<option value="menu_order" <?php selected($instance['orderby'], 'menu_order') ?>>Menu Order</option>
				<option value="menu_order" <?php selected($instance['orderby'], 'menu_order') ?>>Menu Order</option>
				<option value="post__in" <?php selected($instance['orderby'], 'post__in') ?>>Post In Order</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('order') ?>">Order</label>
			<select id="<?php echo $this->get_field_id( 'order' ) ?>" name="<?php echo $this->get_field_name( 'order' ) ?>" value="<?php echo esc_attr($instance['order']) ?>">
				<option value="DESC" <?php selected($instance['order'], 'DESC') ?>>Descending</option>
				<option value="ASC" <?php selected($instance['order'], 'ASC') ?>>Ascending</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('sticky') ?>">Sticky Posts</label>
			<select id="<?php echo $this->get_field_id( 'sticky' ) ?>" name="<?php echo $this->get_field_name( 'sticky' ) ?>" value="<?php echo esc_attr($instance['sticky']) ?>">
				<option value="" <?php selected($instance['sticky'], '') ?>>Default</option>
				<option value="ignore" <?php selected($instance['sticky'], 'ignore') ?>>Ignore Sticky</option>
				<option value="exclude" <?php selected($instance['sticky'], 'exclude') ?>>Exclude Sticky</option>
				<option value="only" <?php selected($instance['sticky'], 'only') ?>>Only Sticky</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('more') ?>">More Link </label>
			<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'more' ) ?>" name="<?php echo $this->get_field_name( 'more' ) ?>" <?php checked( $instance['more'] ) ?> /><br/>
			<small>If the template supports it, cut posts and display the more link.</small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('additional') ?>">Additional </label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'additional' ) ?>" name="<?php echo $this->get_field_name( 'additional' ) ?>" value="<?php echo esc_attr($instance['additional']) ?>" />
			<small>Additional query arguments. See <a href="http://codex.wordpress.org/Function_Reference/query_posts" target="_blank">query_posts</a>.</small>
		</p>
	<?php
	}
}

/**
 * Register the widgets.
 */
function siteorigin_panels_basic_widgets_init(){
	register_widget('SiteOrigin_Panels_Widgets_PostLoop');
	register_widget('SiteOrigin_Panels_Widgets_Layout');
}
add_action('widgets_init', 'siteorigin_panels_basic_widgets_init');