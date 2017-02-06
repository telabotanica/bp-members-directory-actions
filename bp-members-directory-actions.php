<?php
/*
 * @wordpress-plugin
 * Plugin Name:       BP Members Directory Actions
 * Plugin URI:        https://github.com/telabotanica/bp-members-directory-actions
 * GitHub Plugin URI: https://github.com/telabotanica/bp-members-directory-actions
 * Description:       A BuddyPress plugin that adds capacity to perform bulk actions on members from the members directory
 * Version:           0.1
 * Author:            Tela Botanica
 * Author URI:        https://github.com/telabotanica
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bp-members-directory-actions
 * Domain Path:       /languages
 */

add_action('bp_include', 'bp_mda_init');

function bp_mda_init() {
	// Custom rendering of members directory: checkboxes, actions menu...
	require_once __DIR__ . '/overload-members-directory-template.php';

	// Add "select all page items" checkbox before members list
	add_action('bp_before_directory_members_list', 'bp_mda_select_all_page_items_checkbox', 10);
	if (class_exists('bps_widget')) { // BP Profile Search compatibility
		// Add "select all search results" checkbox before members list
		add_action('bp_before_directory_members_list', 'bp_mda_add_select_all_search_results_checkbox', 10);
	}
	// Add filterable actions menu before members list
	add_action('bp_before_directory_members_list', 'bp_mda_add_filterable_actions_menu', 10);
	// Populate actions menu with default values
	add_filter('bp_mda_bulk_actions', 'bp_mda_add_default_bulk_actions');
	// Add a checkbox before every member
	add_action('bp_directory_before_members_item', 'bp_mda_add_checkbox_before_member_item', 10);

	// Add JS on members directory page
	add_action( 'bp_after_directory_members_page', 'bp_mda_load_javascript' );
}

/**
 * 
 */
function bp_mda_select_all_page_items_checkbox() {
	?>
	<div class="bp_mda_select_all_page_items">
		<input type="checkbox" class="bp_mda_all_page_items_checkbox">
	</div>
	<?php
}

/**
 * 
 */
function bp_mda_add_filterable_actions_menu() {
	?>
	<div class="bp_mda_group_actions_container">
		<form action="" method="POST" class="bp_mda_group_actions_form">
			<label class="bp_mda_group_actions_label">
				<?php _e('Bulk actions', 'bp-members-directory-actions'); ?>
			</label>
			<select class="bp_mda_group_actions_options">
			<?php
				$options = apply_filters('bp_mda_bulk_actions', array());
				foreach ($options as $k => $opt) { ?>
					<option value="<?php echo $k ?>"><?php echo $opt ?></option>
				<?php }
			?>
			</select>
			<input class="bp_mda_group_actions_submit" type="submit" value="Go !">
		</form>
	</div>
	<?php
}

/**
 * 
 */
function bp_mda_add_select_all_search_results_checkbox() {
	if (! empty($_REQUEST['bp_profile_search'])) {
		global $members_template;
		//var_dump($_POST);
	?>
		<div class="bp_mda_select_all_search_results">
			<label>
				<input type="checkbox" class="bp_mda_all_search_results_checkbox bp_mda_checkbox" name="bp_mda_select_all_search_results">
				<?php _e('Select all search results', 'bp-members-directory-actions') ?>
				(<?php echo bp_core_number_format($members_template->total_member_count) ?>)
			</label>
		</div>
	<?php
	}
}

function bp_mda_add_default_bulk_actions($values) {
	$values = array(
		'send-message' => __('Send a message', 'bp-members-directory-actions'),
		'buy-cookie' => __('Buy a cookie', 'bp-members-directory-actions')
	);
	return $values;
}

function bp_mda_add_checkbox_before_member_item() {
	?>
	<div class="bp_mda_before_member_item">
		<input type="checkbox" class="bp_mda_member_item_checkbox bp_mda_checkbox">
	</div>
	<?php
}

function bp_mda_load_javascript() {
	wp_enqueue_script('bp-members-directory-actions', WP_PLUGIN_URL . '/bp-members-directory-actions/bp-members-directory-actions.js', array('jquery'));
}
