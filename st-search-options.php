<?php
/**
 * Plugin Name: Typeahead.JS Search Options
 * Plugin URI: http://stboston.com
 * Description: Set custom autocomplete options for typeahead.js jQuery plugin.
 * Version: 0.1
 * Author: Brian Hanna
 * Author URI: http://github.com/brianjohnhanna
 * License: GPL2
 */


//Add Admin Scripts and Styles
add_action('admin_enqueue_scripts','st_typeahead_admin_scripts_styles');

function st_typeahead_admin_scripts_styles() {
	//wp_enqueue_style('st-typeahead', plugins_url('css/st-typeahead.css',__FILE__));
	//wp_enqueue_script('st-typeahead-admin-js', plugins_url('js/st-typeahead-admin.js',__FILE__), array(), false, true);
}

//Add Front-end Scripts and Styles
add_action('wp_enqueue_scripts', 'st_typeahead_scripts_styles');

function st_typeahead_scripts_styles() {
	wp_enqueue_script('typeahead', plugins_url('js/typeahead.min.js', __FILE__), array('jquery'), false, true);
	wp_register_script('st-typeahead', plugins_url('js/st-typeahead.js',__FILE__), array('jquery', 'typeahead'), false, true);
	wp_enqueue_script('st-typeahead');
	wp_localize_script('st-typeahead', 'pages', get_option('st_typeahead'));
	wp_enqueue_style('st-typeahead', plugins_url('css/st-typeahead.css',__FILE__));
}

//Add Options Page
add_action('admin_menu', 'st_typeahead_options_page');

function st_typeahead_options_page() {
	add_options_page('Search Options', 'Search Options', 'manage_options', 'search-options', 'st_typeahead_settings_form');
}

//Settings Form
function st_typeahead_settings_form() {
	$selected_pages = get_option('st_typeahead');
?>
<!-- The Admin Settings Section -->
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<p>Select which pages you would like to appear in the quick search.</p>
	<form method="post">
		<div class="all-pages">
			<h3>All Pages</h3>
			<?php 
				$all_pages = get_pages(); 
				$i=0;
				foreach ($all_pages as $key => $page) {
					echo '<label>';
					echo '<input type="checkbox" name="name[]" value="'. $page->post_title .'" '. (in_array($page->post_title, $selected_pages) ? 'checked' : '') .'>';
					echo $page->post_title.'</label> <br />';
					echo '<input type="hidden" name="url[]" value="'. $page->guid .'">';
					$i++;
				}
			?>
			<br /><br />
		</div>
		<input type="submit" name="st_typeahead_update" class="button-primary" value="Update Settings">
	</form>
</div>
<?php 
//var_dump(get_option('st_typeahead'));
} 

if(isset($_POST['st_typeahead_update'])) {
	update_option('st_typeahead', st_update_options());
}

function st_update_options() {
	$i=0;
	foreach ($_POST['name'] as $key => $value) {
		$_val[$i]['value'] = $_POST['name'][$key];
		$_val[$i]['url'] = $_POST['url'][$key];
		$i++;
	}
	return $_val;
}

//Add Shortcode
function typeahead_shortcode() {
	?>
	<div id="bloodhound">
	  <input class="typeahead" type="text" placeholder="Enter your search...">
	</div>
	<?php
}

add_shortcode('typeahead', 'typeahead_shortcode');

?>