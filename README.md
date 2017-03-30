# bp-members-directory-actions
A BuddyPress plugin that adds capacity to perform bulk actions on members from the members directory

Currently shipped with one default action `send-message` : send a private message to multiple recipients

## usage
Install, activate

## important
At the moment (2017-02) this plugin guarantees compatibility with BP Profile
Search only if the member loop is initiated with default parameters (`type="active"`).

Feel free to change members loop initiation mode when overloading the members directory
template in your theme, but the "Select all search results" checkbox will not work
as expected with `type` values other than `active` - see related issue #1

## how to extend (add custom actions)
1. Ad a filter to `bp_mda_bulk_actions` (see below) to add your action to the bulk actions menu
2. Hook a function to `bp_mda_action_specific_form` to execute code corresponding to the treatment of your new action

## vocabulary
**action** designates an action provided by this plugin or an extension, i.e. an action the user can perform from the members directory. Wordpress hookable actions are referred to as **WP-action**

## new WP actions

### bp_directory_before_members_item
Fires before an item (i.e. a member) is displayed in the members list.

Default hook `bp_mda_add_checkbox_before_member_item` allows ticking members one by one.

### bp_mda_action_specific_form `(string $action, array $recipientsIds)`
Sub-WP-action hooked to `bp_after_directory_members`. Feeds function hooked to it with chosen action slug (value of the action selector in the bulk actions form) and recipients IDs (all members the action is targeted at; i.e. the members whose checkboxes were ticked).

Default hook `bp_mda_add_default_action_specific_form` manages the actions included in the plugin (only `send-message` at the moment).

Hooking new functions here allows to perform any action; to add actions to the bulk actions selector, see `bp_mda_bulk_actions` filter below.

## new WP filters

### bp_mda_bulk_actions `(array $values)`
Used to feed the action selector in the bulk actions form. Elements of `$values` array must be `'action-slug' => 'Translated action display name'`. For example : `'send-message' => __('Write a message', 'bp-members-directory-actions')`.

Default filter `bp_mda_add_default_bulk_actions` adds actions included in the plugin (currently `send-message`).

Applying new filters here allows to add custom actions. To execute custom actions added here, see `bp_mda_action_specific_form` WP-action above.
