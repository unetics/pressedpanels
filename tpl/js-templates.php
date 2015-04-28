<?php
global $wp_widget_factory;
$layouts = apply_filters( 'siteorigin_panels_prebuilt_layouts', array() );
?>

<script type="text/template" id="siteorigin-panels-builder">

	<div class="so-builder-toolbar">

		<a href="#" class="so-tool-button so-widget-add">
			<span class="so-panels-icon so-panels-icon-plus"></span>
			<span class="so-button-text">Add Widget</span>
		</a>

		<a href="#" class="so-tool-button so-row-add">
			<span class="so-panels-icon so-panels-icon-columns"></span>
			<span class="so-button-text">Add Row</span>
		</a>

		<a href="#" class="so-tool-button so-prebuilt-add">
			<span class="so-panels-icon so-panels-icon-cubes"></span>
			<span class="so-button-text">Prebuilt</span>
		</a>

		<a href="#" class="so-tool-button so-history" style="display: none">
			<span class="so-panels-icon so-panels-icon-rotate-left"></span>
			<span class="so-button-text">History</span>
		</a>
		<a href="#" class="so-tool-button so-live-editor" style="display: none">
			<span class="so-panels-icon so-panels-icon-eye"></span>
			<span class="so-button-text">Live Editor</span>
		</a>

		<a href="#" class="so-switch-to-standard">Switch to Editor</a>

	</div>

	<div class="so-rows-container">

	</div>

	<div class="so-panels-welcome-message">
		<div class="so-message-wrapper">
			<?php
			printf(
				__("Add a %s %s or %s to get started. Read our %s if you need help.", 'siteorigin-panels'),
				"<a href='#' class='so-tool-button so-widget-add'><span class='so-panels-icon so-panels-icon-plus'></span> " . __('widget', 'siteorigin-panels') .  "</a>",
				"<a href='#' class='so-tool-button so-row-add'><span class='so-panels-icon so-panels-icon-columns'></span> " . __('row', 'siteorigin-panels') .  "</a>",
				"<a href='#' class='so-tool-button so-prebuilt-add'><span class='so-panels-icon so-panels-icon-cubes'></span> " . __('prebuilt layout', 'siteorigin-panels') .  "</a>",
				"<a href='https://siteorigin.com/page-builder/documentation/' target='_blank'>" . __('starting guide', 'siteorigin-panels') . "</a>"
			);
			?>
		</div>
	</div>

</script>

<script type="text/template" id="siteorigin-panels-builder-row">
	<div class="so-row-container ui-draggable">

		<div class="so-row-toolbar">
			<span class="so-row-move so-tool-button"><span class="so-panels-icon so-panels-icon-arrows-v"></span></span>

			<span class="so-dropdown-wrapper">
				<a href="#" class="so-row-settings so-tool-button"><span class="so-panels-icon so-panels-icon-wrench"></span></a>

				<div class="so-dropdown-links-wrapper">
					<ul>
						<li><a href="#" class="so-row-settings">Edit Row</a></li>
						<li><a href="#" class="so-row-duplicate">Duplicate Row</a></li>
						<li><a href="#" class="so-row-delete so-needs-confirm" data-confirm="Are you sure?">Delete Row</a></li>
						<div class="so-pointer"></div>
					</ul>
				</div>
			</span>
		</div>

		<div class="so-cells">

		</div>

	</div>
</script>

<script type="text/template" id="siteorigin-panels-builder-cell">
	<div class="cell">
		<div class="resize-handle"></div>
		<div class="cell-wrapper widgets-container">
		</div>
	</div>
</script>

<script type="text/template" id="siteorigin-panels-builder-widget">
	<div class="so-widget ui-draggable">
		<div class="so-widget-wrapper">
			<div class="title">
				<h4>{{%= title %}}</h4>
					<span class="actions">
						<a href="#" class="widget-edit">Edit</a>
						<a href="#" class="widget-duplicate">Duplicate</a>
						<a href="#" class="widget-delete">Delete</a>
					</span>
			</div>
			<small class="description">{{%= description %}}</small>
		</div>
	</div>
</script>

<script type="text/template" id="siteorigin-panels-dialog">
	<div class="so-panels-dialog {{% if(typeof left_sidebar != 'undefined') print('so-panels-dialog-has-left-sidebar '); if(typeof right_sidebar != 'undefined') print('so-panels-dialog-has-right-sidebar '); %}}">

		<div class="so-overlay"></div>

		<div class="so-title-bar">
			<h3 class="so-title">{{%= title %}}</h3>
			<a class="so-previous so-nav" href="#"><span class="so-dialog-icon"></span></a>
			<a class="so-next so-nav" href="#"><span class="so-dialog-icon"></span></a>
			<a class="so-close" href="#"><span class="so-dialog-icon"></span></a>
		</div>

		<div class="so-toolbar">
			<div class="so-status">{{% if(typeof status != 'undefined') print(status); %}}</div>
			<div class="so-buttons">
				{{%= buttons %}}
			</div>
		</div>

		<div class="so-sidebar so-left-sidebar">
			{{% if(typeof left_sidebar  != 'undefined') print(left_sidebar ); %}}
		</div>

		<div class="so-sidebar so-right-sidebar">
			{{% if(typeof right_sidebar  != 'undefined') print(right_sidebar ); %}}
		</div>

		<div class="so-content panel-dialog">
			{{%= content %}}
		</div>

	</div>
</script>

<script type="text/template" id="siteorigin-panels-dialog-builder">
	<div class="dialog-data">

		<h3 class="title">Page Builder</h3>

		<div class="content">
			<div class="siteorigin-panels-builder">

			</div>
		</div>

		<div class="buttons">
			<input type="button" class="button-primary so-close" value="Done" />
		</div>

	</div>
</script>


<script type="text/template" id="siteorigin-panels-dialog-tab">
	<li><a href="{{% if(typeof tab != 'undefined') { print ( '#' + tab ); } %}}">{{%= title %}}</a></li>
</script>

<script type="text/template" id="siteorigin-panels-dialog-widgets">
	<div class="dialog-data">

		<h3 class="title"><?php printf( __('Add New Widget %s'), '<span class="current-tab-title"></span>' ) ?></h3>

		<div class="left-sidebar">

			<input type="text" class="so-sidebar-search" placeholder="Search Widgets" />

			<ul class="so-sidebar-tabs">
			</ul>

		</div>

		<div class="content">
			<ul class="widget-type-list"></ul>
		</div>

		<div class="buttons">
			<input type="button" class="button-primary so-close" value="Close" />
		</div>

	</div>
</script>

<script type="text/template" id="siteorigin-panels-dialog-widgets-widget">
	<li class="widget-type">
		<div class="widget-type-wrapper">
			<h3>{{%= title %}}</h3>
			<small class="description">{{%= description %}}</small>
		</div>
	</li>
</script>

<script type="text/template" id="siteorigin-panels-dialog-widget">
	<div class="dialog-data">

		<h3 class="title"><span class="widget-name"></span></h3>

		<div class="right-sidebar"></div>

		<div class="content">

			<div class="widget-form">
			</div>

		</div>

		<div class="buttons">
			<div class="action-buttons">
				<a href="#" class="so-delete">Delete</a>
				<a href="#" class="so-duplicate">Duplicate</a>
			</div>

			<input type="button" class="button-primary so-close" value="Done" />
		</div>
	</div>
</script>

<script type="text/template" id="siteorigin-panels-dialog-widget-sidebar-widget">
	<div class="so-widget">
		<h3>{{%= title %}}</h3>
		<small class="description">
			{{%= description %}}
		</small>
	</div>
</script>

<script type="text/template" id="siteorigin-panels-dialog-row">
	<div class="dialog-data">

		<h3 class="title">
			{{% if( dialogType == 'create' ) { %}}
				<span class="add-row">Add New Row</span>
			{{% } else { %}}
				<span class="edit-row">Edit Row</span>
			{{% } %}}
		</h3>

		{{% if( dialogType == 'edit' ) { %}}
			<div class="right-sidebar"></div>
		{{% } %}}

		<div class="content">

			<div class="row-set-form">
				<strong>Set Row Layout</strong>
				<input type="number" min="1" max="8" name="cells"  class="so-row-field" value="2" />
				<span>Columns with Ratio</span>
				<select name="ratio" class="so-row-field">
					<option value="1">Even</option>
					<option value="0.61803398">Golden</option>
					<option value="0.5">Halves</option>
					<option value="0.33333333">Thirds</option>
					<option value="0.41421356">Diagon</option>
					<option value="0.73205080">Hecton</option>
					<option value="0.11803398">Hemidiagon</option>
					<option value="0.27201964">Penton</option>
					<option value="0.15470053">Trion</option>
					<option value="0.207">Quadriagon</option>
					<option value="0.30901699">Biauron</option>
					<option value="0.46">Bipenton</option>
				</select>
				<select name="ratio_direction" class="so-row-field">
					<option value="right">Left to Right</option>
					<option value="left">Right to Left</option>
				</select>
				<button class="button-secondary set-row">Set</button>
			</div>

			<div class="row-preview">

			</div>

		</div>

		<div class="buttons">
			{{% if( dialogType == 'edit' ) { %}}
				<div class="action-buttons">
					<a href="#" class="so-delete">Delete</a>
					<a href="#" class="so-duplicate">Duplicate</a>
				</div>
			{{% } %}}

			{{% if( dialogType == 'create' ) { %}}
				<input type="button" class="button-primary so-insert" value="Insert" />
			{{% } else { %}}
				<input type="button" class="button-primary so-save" value="Save" />
			{{% } %}}
		</div>

	</div>
</script>

<script type="text/template" id="siteorigin-panels-dialog-row-cell-preview">
	<div class="preview-cell" style="width: {{%- weight*100 %}}%">
		<div class="preview-cell-in">
			<div class="preview-cell-weight">{{% print(Math.round(weight * 1000) / 10) %}}</div>
		</div>
	</div>
</script>

<script type="text/template" id="siteorigin-panels-dialog-prebuilt">
	<div class="dialog-data">

		<h3 class="title">Prebuilt Layouts</h3>

		<div class="left-sidebar">

			<input type="text" class="so-sidebar-search" placeholder="Search" />

			<ul class="so-sidebar-tabs">
				<li><a href="#prebuilt">Theme Defined</a></li>
				<?php
				$post_types = siteorigin_panels_setting('post-types');
				foreach($post_types as $post_type) {
					$type = get_post_type_object( $post_type );
					if( empty($type) ) continue;
					?><li><a href="#<?php echo 'clone_'.$post_type ?>"><?php printf( __('Clone: %s', 'siteorigin-panels'), $type->labels->name ) ?></a></li><?php
				}
				?>
			</ul>

		</div>

		<div class="content">

		</div>

		<div class="buttons">
		</div>

	</div>
</script>

<script type="text/template" id="siteorigin-panels-dialog-prebuilt-entry">
	<div class="layout">
		<div class="layout-inside">
			<div class="dashicons dashicons-migrate"></div>
			<h4>{{%= name %}}</h4>
			<div class="description">{{%= description %}}</div>
		</div>
	</div>
</script>

<script type="text/template" id="siteorigin-panels-dialog-history">
	<div class="dialog-data">

		<h3 class="title">Page Builder Change History</h3>

		<div class="left-sidebar">
			<div class="history-entries"></div>
		</div>

		<div class="content">
			<form method="post" action="<?php echo add_query_arg( 'siteorigin_panels_live_editor', 'true', get_the_permalink() ) ?>" target="siteorigin-panels-history-iframe-{{%= cid ?>" class="history-form">
				<input type="hidden" name="siteorigin_panels_data" value="">
			</form>
			<iframe class="siteorigin-panels-history-iframe" name="siteorigin-panels-history-iframe-{{%= cid ?>" src=""></iframe>
		</div>

		<div class="buttons">
			<input type="button" class="button-primary so-restore" value="Restore Version" />
		</div>

	</div>
</script>

<script type="text/template" id="siteorigin-panels-dialog-history-entry">
	<div class="history-entry">
		<h3>{{%= title %}}{{% if( count > 1 ) { %}} <span class="count">({{%= count %}})</span>{{% } %}}</h3>
		<div class="timesince"></div>
	</div>
</script>

<script type="text/template" id="siteorigin-panels-live-editor">
	<div class="so-panels-live-editor">

		<div class="so-overlay"></div>

		<form method="post" action="<?php echo add_query_arg( 'siteorigin_panels_live_editor', 'true', get_the_permalink() ) ?>" target="siteorigin-panels-live-editor-iframe" class="live-editor-form">
			<input type="hidden" name="siteorigin_panels_data" value="">
		</form>

		<div class="so-sidebar">

			<div class="so-sidebar-tools">
				<a href="#" class="live-editor-close" title="Close Live Editor"></a>
			</div>

			<div class="page-widgets">

			</div>

		</div>

		<div class="so-preview">
			<iframe id="siteorigin-panels-live-editor-iframe" name="siteorigin-panels-live-editor-iframe" src=""></iframe>
		</div>

	</div>
</script>

<script type="text/template" id="siteorigin-panels-live-editor-sidebar-section">
	<div class="page-widgets-section">
		<div class="section-header">
			<h4>{{%= title %}}</h4>
		</div>
		<div class="section-widgets">
		</div>
	</div>
</script>