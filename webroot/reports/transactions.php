<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );
include_once( '../../inc/donordb.phpm' );

authorize( 'reports' );

$op = input( 'op', INPUT_STR );

$params = array();
$locations = all_locations();
uasort( $locations, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );
$all_accounts = get_accounts();
uasort( $all_accounts, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );

$rows = array();
$title = 'Transactions';

$header = array(
    array('column_name' => 'date', 'column_title' => 'Date', 'clean_attr' => '1'),
    array('column_name' => 'contact', 'column_title' => 'Contact Name',),
    array('column_name' => 'company', 'column_title' => 'Contact Company',),
    array('column_name' => 'receipt', 'column_title' => 'Receipt',),
    array('column_name' => 'po', 'column_title' => 'PO',),
    array('column_name' => 'note', 'column_title' => 'Note',),
    array('column_name' => 'amount', 'column_title' => 'Amount',),
);

$query = 'SELECT accounts.name AS account_name, actions.date, contacts.name, contacts.company, actions.receipt, actions.po, actions.note, actions.amount, actions.accountid FROM actions LEFT JOIN contacts USING (contactid) LEFT JOIN accounts USING (accountid)';

if ( !empty($op) ) {
    $s_date = input( 'start_date', INPUT_HTML_NONE );
    $e_date = input( 'end_date', INPUT_HTML_NONE );
    $locationid = input( 'locationid', INPUT_PINT );
    $accounts = input( 'accountid', INPUT_PINT );
    $where = array();
    $data = array();

    if ( !empty($s_date) ) {
        $where[] = "date >= ?";
        $data[] = $s_date;
    }
    if ( !empty($e_date) ) {
        $where[] = "date <= ?";
        $data[] = $e_date;
    }
    if ( !empty($locationid) ) {
        $where[] = "actions.locationid = ?";
        $data[] = $locationid;
    }
    if ( !empty($accounts) ) {
        if ( ! is_array($accounts) ) {
            $accounts = array( $accounts );
        }
        $where[] = "accounts.accountid IN (". implode($accounts,',') .")";
    }
    if ( !empty($where) ) {
        $query .= " WHERE ". implode( ' AND ', $where );
    }
    $query .= " ORDER BY account_name";

    $dbh = db_connect('core');
    $sth = $dbh->prepare($query);
    $sth->execute($data);

    $query = "SELECT SUM(amount) AS amount FROM actions WHERE accountid = ? AND date < ? GROUP BY accountid";
    $sth2 = $dbh->prepare($query);
    $query = "SELECT SUM(amount) AS amount FROM actions WHERE accountid IS NULL AND date < ? GROUP BY accountid";
    $sth3 = $dbh->prepare($query);

    while ( $row = $sth->fetch( PDO::FETCH_ASSOC ) ) {
        if ( empty($row['account_name']) ) { $row['account_name'] = '[No Account]'; }
        if ( empty($rows[ $row['account_name'] ]) ) {
            if ( empty($row['accountid']) ) {
                $sth3->execute( array($s_date) );
                $previous = $sth3->fetch( PDO::FETCH_ASSOC );
            }
            else {
                $sth2->execute( array($row['accountid'],$s_date) );
                $previous = $sth2->fetch( PDO::FETCH_ASSOC );
            }

            $rows[ $row['account_name'] ] = array(
                'name' => $row['account_name'],
                'total' => $previous['amount'] ?? 0,
                'previous' => number_format($previous['amount'] ?? 0,2),
                'rows' => array(),
            );
        }
        $rows[ $row['account_name'] ]['total'] += $row['amount'];
        $rows[ $row['account_name'] ]['rows'][] = array(
            array(
                'column_name' => 'date',
                'clean_value' => $row['date'],
                'value' => date('m/d/Y',strtotime($row['date'])),
            ),
            array('column_name' => 'contact','value' => $row['name'],),
            array('column_name' => 'company','value' => $row['company'],),
            array('column_name' => 'receipt','value' => $row['receipt'],),
            array('column_name' => 'po','value' => $row['po'],),
            array('column_name' => 'note','value' => $row['note'],),
            array('column_name' => 'amount','value' => number_format($row['amount'],2),),
        );
    }
}
else {
    $s_date = "2004-07-01";
    $e_date = "";
    $month = date('n'); $year = date('Y');
    if ( $month >= 7 ) {
        $e_date = date( 'Y-m-d', mktime(1,1,1,6,30,$year+1) );
    } else {
        $e_date = date( 'Y-m-d', mktime(1,1,1,6,30,$year) );
    }
    $params[] = array(
        'type' => 'date',
        'label' => 'Start Date',
        'name' => 'start_date',
        'value' => $s_date,
    );
    $params[] = array(
        'type' => 'date',
        'label' => 'End Date',
        'name' => 'end_date',
        'value' => $e_date,
    );
    $loc_loop = array();
    foreach ( $locations as $loc ) {
        $loc_loop[] = array('value'=>$loc['locationid'],'label'=>$loc['name']);
    }
    $params[] = array(
        'type' => 'select',
        'label' => 'Location',
        'name' => 'locationid',
        'option_loop' => $loc_loop,
        'onchange' => 'onchange="update_account_select(this.value)"',
        'first_blank' => 1,
    );

    $acct_loop = array();
    foreach ( $all_accounts as $acct ) {
        $acct_loop[] = array('value'=>$acct['accountid'],'label'=>$acct['name']);
    }
    $params[] = array(
        'id' => 'account_select',
        'type' => 'select',
        'label' => 'Account(s)',
        'name' => 'accountid[]',
        'option_loop' => $acct_loop,
        'first_blank' => 1,
        'filter_value_label' => 1,
        'multiple' => 1,
        'size' => 8,
    );
}

$output = array(
    'op' => $op,
    'params' => $params,
    'report_title' => $title,
    'report_header' => $header,
    'report_body' => $rows,
    'all_accounts' => $all_accounts,
);
output( $output, 'reports/transactions' );
?>
