<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'actions' );

$op = input( 'op', INPUT_STR );
$old_account = input( 'old_account', INPUT_PINT );
$old_contact = input( 'old_contact', INPUT_PINT );
$old_location = input( 'old_location', INPUT_PINT );
$old_amount = input( 'old_amount', INPUT_NUM );
$old_receipt = input( 'old_receipt', INPUT_HTML_NONE );
$old_po = input( 'old_po', INPUT_HTML_NONE );
$old_date = input( 'old_date', INPUT_HTML_NONE );
$new_account = input( 'new_account', INPUT_PINT );
$new_contact = input( 'new_contact', INPUT_PINT );
$new_location = input( 'new_location', INPUT_PINT );
$new_amount = input( 'new_amount', INPUT_NUM );
$new_receipt = input( 'new_receipt', INPUT_HTML_NONE );
$new_po = input( 'new_po', INPUT_HTML_NONE );
$new_date = input( 'new_date', INPUT_HTML_NONE );
$mod_date = input( 'mod_date', INPUT_HTML_NONE );
$search = array();

$actions = $contacts = $accounts = $locations = array();

if ( !empty($old_account) ) { $search['old_accountid'] = $old_account; }
if ( !empty($old_contact) ) { $search['old_contactid'] = $old_contact; }
if ( !empty($old_location) ) { $search['old_locationid'] = $old_location; }
if ( !empty($old_amount) ) { $search['old_amount'] = $old_amount; }
if ( !empty($old_receipt) ) { $search['old_receipt'] = $old_receipt; }
if ( !empty($old_po) ) { $search['old_po'] = $old_po; }
if ( !empty($old_date) ) { $search['old_date'] = $old_date; }

if ( !empty($new_account) ) { $search['new_accountid'] = $new_account; }
if ( !empty($new_contact) ) { $search['new_contactid'] = $new_contact; }
if ( !empty($new_location) ) { $search['new_locationid'] = $new_location; }
if ( !empty($new_amount) ) { $search['new_amount'] = $new_amount; }
if ( !empty($new_receipt) ) { $search['new_receipt'] = $new_receipt; }
if ( !empty($new_po) ) { $search['new_po'] = $new_po; }
if ( !empty($new_date) ) { $search['new_date'] = $new_date; }

if ( !empty($mod_date) ) { $search['new_mod_date'] = $mod_date; }

if ( $op && ! empty($search) ) {
    $actions = modlog_search( 'action', $search );
    foreach ( $actions as &$act ) {
        foreach ( array_keys($act) as $key ) {
            $value = $act[$key];
            $act[$key] = [$value];
        }
        $changes = modlog_get( 'action', $act['actionid'][0] );
        foreach ( $changes as $change ) {
            $act[$change['field']][] = $change['old_value'];
        }
    }
}
else {
    $contacts = get_contacts();
    uasort( $contacts, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );
    $accounts = get_accounts();
    uasort( $accounts, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );
    $locations = all_locations();
    uasort( $locations, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );
}

$output = array(
    'op' => $op,
    'contacts' => $contacts,
    'accounts' => $accounts,
    'locations' => $locations,
    'actions_list' => $actions,
);
output( $output, 'modlog_actions' );
?>
