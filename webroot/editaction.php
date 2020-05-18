<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'actions' );

$op = input( 'op', INPUT_STR );
$actionid = input( 'actionid', INPUT_PINT );
$include_retired = input( 'retired', INPUT_PINT );
$contacts = get_contacts();
$accounts = get_accounts( [], $include_retired );
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
    if ( !empty($action['locationid']) && ( empty($action['accountid']) || $accounts[ $action['accountid'] ]['locationid'] == $action['locationid'] ) ) {
        $accounts = get_accounts_at_location( $action['locationid'], [], $include_retired );
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

if ( $op == 'Save' || $op == 'Save & New' ) {
    $updates = array();

    $date = input( 'date', INPUT_HTML_NONE );
    $amount = input( 'amount', INPUT_NUM );
    ( $contactid = input('contactid',INPUT_PINT) ) || ( $contactid = null );
    ( $accountid = input('accountid',INPUT_PINT) ) || ( $accountid = null );
    ( $locationid = input('locationid',INPUT_PINT) ) || ( $locationid = null );
    ( $receipt = input('receipt',INPUT_HTML_NONE) ) || ( $receipt = null );
    $po = input( 'po', INPUT_HTML_NONE );
    $note = input( 'note', INPUT_HTML_NONE );
    $inkind = input( 'in_kind', INPUT_HTML_NONE );
    $inkind = !empty($inkind) ? 1 : 0;
    $transfer = input( 'is_transfer', INPUT_HTML_NONE );
    $transfer = !empty($transfer) ? 1 : 0;

    if ( empty($action['date']) || $action['date'] != $date ) {
        $updates['date'] = $date;
    }
    if ( empty($action['amount']) || $action['amount'] != $amount ) {
        $updates['amount'] = $amount;
    }
    if ( !array_key_exists('contactid',$action) || $action['contactid'] != $contactid ) {
        $updates['contactid'] = $contactid;
    }
    if ( !array_key_exists('accountid',$action) || $action['accountid'] != $accountid ) {
        $updates['accountid'] = $accountid;
    }
    if ( !array_key_exists('locationid',$action) || $action['locationid'] != $locationid ) {
        $updates['locationid'] = $locationid;
    }
    if ( !array_key_exists('receipt',$action) || $action['receipt'] != $receipt ) {
        $updates['receipt'] = $receipt;
    }
    if ( !array_key_exists('po',$action) || $action['po'] != $po ) {
        $updates['po'] = $po;
    }
    if ( !isset($action['note']) || $action['note'] != $note ) {
        $updates['note'] = $note;
    }
    if ( !isset($action['in_kind']) || $action['in_kind'] != $inkind ) {
        $updates['in_kind'] = $inkind;
    }
    if ( !isset($action['is_transfer']) || $action['is_transfer'] != $transfer ) {
        $updates['is_transfer'] = $transfer;
    }

    if ( !empty($updates) ) {
        $actionid = update_action( $actionid, $updates );
        if ( !empty($actionid) ) {
            foreach ( $updates as $field => $value ) {
                modlog_add('action',$actionid,$field,$value,array_key_exists($field,$action)?$action[$field]:'');
            }
            if ( $op == 'Save & New' ) {
                $action = array('contactid'=>0,'locationid'=>0,'accountid'=>0);
                $actionid = 0;
            } else {
                $action = get_actions( array($actionid) )[$actionid];
            }
            $op = 'SaveSuccess';
            foreach ( $contacts as &$con ) {
                if ( $action['contactid'] == $con['contactid'] ) {
                    $con['selected'] = 1;
                }
                else {
                    unset($con['selected']);
                }
            }
            if ( !empty($action['locationid']) && !empty($action['accountid']) && $accounts[ $action['accountid'] ]['locationid'] == $action['locationid'] ) {
                $accounts = get_accounts_at_location( $action['locationid'], [], $include_retired );
            }
            foreach ( $accounts as &$acc ) {
                if ( $action['accountid'] == $acc['accountid'] ) {
                    $acc['selected'] = 1;
                }
                else {
                    unset($acc['selected']);
                }
            }
            foreach ( $locations as &$loc ) {
                if ( $action['locationid'] == $loc['locationid'] ) {
                    $loc['selected'] = 1;
                }
                else {
                    unset($loc['selected']);
                }
            }
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
