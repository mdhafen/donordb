<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'accounts' );

$op = input( 'op', INPUT_STR );
$action = input( 'action', INPUT_STR );
$accountid = input( 'accountid', INPUT_PINT );
$account = array();

$contacts = get_contacts();
$accounts = get_accounts();
$locations = all_locations();
uasort( $contacts, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );
uasort( $locations, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );
uasort( $accounts, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );


$page;
switch ($op) {
default:
case 'move': $page = 'account_move'; break;
case 'transfer': $page = 'account_transfer'; break;
}

if ( empty($accountid) ) {
    error(array('ACCOUNT_ACTION_NO_ACCOUNT'=>'No account specified.'));
}
$account = get_accounts_with_total( array($accountid) )[$accountid];
$balance = $account['total'];
unset($account['total']);

if ( !empty($action) ) {
    $locationid = input( 'locationid', INPUT_PINT );
    $to_accountid = input( 'to_accountid', INPUT_PINT );
    $contactid = input( 'contactid', INPUT_PINT );
    $note = input( 'note', INPUT_HTML_NONE );
    $amount = input( 'amount', INPUT_NUM );
    $errors = array();
    $successes = 0;

    if ( empty($contactid) ) {
        $errors['ACCOUNT_ACTION_NO_CONTACT'] = 'A contact must be specified for the transfer action entries.';
    }
    if ( empty($to_accountid) ) {
        $errors['ACCOUNT_ACTION_NO_DEST_ACCOUNT'] = 'A destination account must be specified for the transfer action entries.';
    }
    if ( empty($locationid) ) {
        $errors['ACCOUNT_ACTION_NO_LOCATION'] = 'A location must be specified for the transfer action entries.';
    }
    if ( ($op == 'move' && !isset($amount)) || ( $op == 'transfer' && empty($amount) ) ) {
        $errors['ACCOUNT_ACTION_NO_AMOUNT'] = 'An amount must be specified for the transfer action entries.';
    }
    if ( !empty($errors) ) {
        error($errors);
    }

    $updates = array(
        'date' => date('Y-m-d'),
        'is_transfer' => 1,
        'contactid' => $contactid,
        'note' => $note,
    );

    if ( $amount ) {
        $from_update = $updates;
        $from_update['amount'] = $amount * -1;
        $from_update['accountid'] = $accountid;
        $from_update['locationid'] = $account['locationid'];
        $actionid = update_action( 0, $from_update );

        $to_update = $updates;
        $to_update['amount'] = $amount;
        $to_update['accountid'] = $to_accountid;
        $to_update['locationid'] = $locationid;
        $actionid = update_action( 0, $to_update );

        $successes++;
    }

    if ( $op == 'move' ) {
        $updates = [ 'locationid' => $locationid ];
        update_account( $accountid, $updates );
        modlog_add('account',$accountid,'locationid',$locationid,array_key_exists('locationid',$account)?$account['locationid']:'');
        $successes++;
    }

    if ( $successes ) {
        redirect( 'editaccount.php?accountid='.$accountid );
    }
}

$output = array(
    'contacts' => $contacts,
    'accounts' => $accounts,
    'locations' => $locations,
    'accountid' => $accountid,
    'account' => $account,
    'balance' => $balance,
    'op' => $op,
);
output( $output, $page );
?>
