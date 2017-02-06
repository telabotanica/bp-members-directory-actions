<?php

// register the location of the plugin templates
function bp_mda_register_templates_location() {
    return __DIR__ . '/templates/';
}

// replace index.php and members-loop.php with the plugin ones
function bp_mda_maybe_replace_templates($templates, $slug) {
	// if you want to make your theme compatible with this plugin, create
	// templates named "index.php" and "members-loop.php" in "members/"
	if('members/index' == $slug) {
		return array('members/index.php');
	}
	if('members/members-loop' == $slug) {
		return array('members/members-loop.php');
	}
	return $templates;
}

function bp_mda_overload_templates() {
	// register custom template location
	if(function_exists('bp_register_template_stack')) {
		bp_register_template_stack('bp_mda_register_templates_location');
	}
	// if trying to view the members directory, overload the templates
	if (bp_is_members_directory()) {
		add_filter('bp_get_template_part', 'bp_mda_maybe_replace_templates', 10, 2);
	}
}
add_action('bp_init', 'bp_mda_overload_templates');
