<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'actions' );

$op = input( 'op', INPUT_STR );
$field = input( 'field', INPUT_STR );

$actions = $contacts = $accounts = $locations = array();

switch ( $field ) {
case 'account' :
    $field = 'accountid';
    $search = input( 'term', INPUT_PINT );
    break;
case 'contact' :
    $field = 'contactid';
    $search = input( 'term', INPUT_PINT );
    break;
case 'location' :
    $field = 'locationid';
    $search = input( 'term', INPUT_PINT );
    break;
case 'date' :
default :
    $field = 'date';
    ( $search = input( 'term', INPUT_HTML_NONE ) ) || ( $search = '-1 year' );
}

if ( $op && $field && $search ) {
    $actions = search_actions_cross( $field, $search );
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
