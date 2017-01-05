<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'actions' );

$op = input( 'op', INPUT_STR );
$account = input( 'account', INPUT_PINT );
$contact = input( 'contact', INPUT_PINT );
$location = input( 'location', INPUT_PINT );
$amount = input( 'amount', INPUT_NUM );
$date = input( 'date', INPUT_HTML_NONE );
$search = array();

$actions = $contacts = $accounts = $locations = array();

if ( !empty($account) ) { $search['accountid'] = $account; }
if ( !empty($contact) ) { $search['contactid'] = $contact; }
if ( !empty($location) ) { $search['locationid'] = $location; }
if ( !empty($amount) ) { $search['amount'] = $amount; }
if ( !empty($date) ) { $search['date'] = $date; }

if ( $op && ! empty($search) ) {
    $actions = search_actions_cross( $search );
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
output( $output, 'actions' );
?>
