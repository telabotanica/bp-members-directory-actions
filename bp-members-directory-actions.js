/**
 * Manage checkboxes combinations behaviour
 * 
 * Plugin Name:       BP Members Directory Actions
 */
jQuery(document).ready(function() {
	var jq = jQuery,
		itemCheckboxes = jq('.bp_mda_member_item_checkbox'),
		allSearchResultsCheckbox = jq('.bp_mda_all_search_results_checkbox'),
		allPageItemsCheckbox = jq('.bp_mda_all_page_items_checkbox'),
		actionButton = jq('.bp_mda_bulk_actions_submit');

	// click listeners
	itemCheckboxes.click(function() {
		updateActionButtonState();
	});

	allSearchResultsCheckbox.click(function() {
		reflectAllSearchResultsState(true);
	});

	allPageItemsCheckbox.click(function() {
		chekAllPageCheckboxes();
	});

	/**
	 * Action button is enabled only if at least one checkbox is checked
	 */
	function updateActionButtonState() {
		var checkedItemCheckboxes = jq('.bp_mda_member_item_checkbox:checked');
		var newActionButtonState = ((checkedItemCheckboxes.length > 0) || allSearchResultsCheckbox.prop("checked")) ? false : 'disabled';
		actionButton.attr('disabled', newActionButtonState);
	}

	/**
	 * When "all search results" is checked, all members checkboxes are checked
	 * too, and disabled to prevent thinking you can unckeck some (won't work)
	 */
	function reflectAllSearchResultsState(reflectUncheckedState) {
		var currentState = allSearchResultsCheckbox.prop('checked');
		if (currentState || reflectUncheckedState) {
			itemCheckboxes.prop('checked', currentState);
		}
		itemCheckboxes.prop('disabled', currentState);
		updateActionButtonState();
	}

	/**
	 * Propagates "check all page" checkbox state to all members checkboxes
	 */
	function chekAllPageCheckboxes() {
		var currentState = allPageItemsCheckbox.prop('checked');
		itemCheckboxes.prop('checked', currentState);
		updateActionButtonState();
	}

	// first run
	reflectAllSearchResultsState(false); // includes updateActionButtonState()
});
