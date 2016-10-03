<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'actions' );

$op = input( 'op', INPUT_STR );
$actionid = input( 'actionid', INPUT_PINT );
$contacts = get_contacts();
$accounts = get_accounts();
$locations = all_locations();

$action = array();
if ( !empty($actionid) ) {
    $action = get_actions( array($actionid) )[$actionid];
    foreach ( $contacts as &$con ) {
        if ( $action['contactid'] == $con['contactid'] ) {
            $con['selected'] = 1;
            break;
        }
    }
    if ( !empty($action['locationid']) && !empty($action['accountid']) && $accounts[ $action['accountid'] ]['locationid'] == $action['locationid'] ) {
        $accounts = get_accounts_at_location( $action['locationid'] );
    }
    foreach ( $accounts as &$acc ) {
        if ( $action['accountid'] == $acc['accountid'] ) {
            $acc['selected'] = 1;
            break;
        }
    }
    foreach ( $locations as &$loc ) {
        if ( $action['locationid'] == $loc['locationid'] ) {
            $loc['selected'] = 1;
            break;
        }
    }
}

uasort( $contacts, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );
uasort( $locations, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );
uasort( $accounts, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );

if ( $op == 'Save' ) {
    $updates = array();

    $date = input( 'date', INPUT_HTML_NONE );
    $amount = input( 'amount', INPUT_NUM );
    $contactid = input( 'contactid', INPUT_PINT );
    $accountid = input( 'accountid', INPUT_PINT );
    $locationid = input( 'locationid', INPUT_PINT );
    $receipt = input( 'receipt', INPUT_HTML_NONE );
    $po = input( 'po', INPUT_HTML_NONE );
    $note = input( 'note', INPUT_HTML_NONE );

    if ( empty($action['date']) || $action['date'] != $date ) {
        $updates['date'] = $date;
    }
    if ( empty($action['amount']) || $action['amount'] != $amount ) {
        $updates['amount'] = $amount;
    }
    if ( empty($action['contactid']) || $action['contactid'] != $contactid ) {
        $updates['contactid'] = $contactid;
    }
    if ( empty($action['accountid']) || $action['accountid'] != $accountid ) {
        $updates['accountid'] = $accountid;
    }
    if ( empty($action['locationid']) || $action['locationid'] != $locationid ) {
        $updates['locationid'] = $locationid;
    }
    if ( empty($action['receipt']) || $action['receipt'] != $receipt ) {
        $updates['receipt'] = $receipt;
    }
    if ( empty($action['po']) || $action['po'] != $po ) {
        $updates['po'] = $po;
    }
    if ( empty($action['note']) || $action['note'] != $note ) {
        $updates['note'] = $note;
    }

    if ( !empty($updates) ) {
        $actionid = update_action( $actionid, $updates );
        if ( !empty($actionid) ) {
            $op = 'SaveSuccess';
            $action = get_actions( array($actionid) )[$actionid];
        }
    }
}

$output = array(
    'contacts' => $contacts,
    'accounts' => $accounts,
    'locations' => $locations,
    'actionid' => $actionid,
    'action' => $action,
    'op' => $op,
);
output( $output, 'editaction' );
?>
