<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );

authorize( 'reports' );

$op = input( 'op', INPUT_STR );

$params = array();
$locations = all_locations();
uasort( $locations, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );

$rows = array();
$title = 'Transactions entered on Date';

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
    $date = input( 'date', INPUT_HTML_NONE );
    $locationid = input( 'locationid', INPUT_PINT );
    $accounts = input( 'accountid', INPUT_PINT );
    $where = array();
    $data = array();

    if ( !empty($date) ) {
        $where[] = "DATE(udate) = ?";
        $data[] = $date;
    }
    if ( !empty($locationid) ) {
        $where[] = "actions.locationid = ?";
        $data[] = $locationid;
    }
    if ( !empty($accounts) ) {
        if ( ! is_array($accounts) ) {
            $accounts = array( $accounts );
        }
        $where[] = "accounts.accountid IN (". implode(',',$accounts) .")";
    }
    if ( !empty($where) ) {
        $query .= " WHERE ". implode( ' AND ', $where );
    }
    $query .= " ORDER BY account_name";

    $dbh = db_connect('core');
    $sth = $dbh->prepare($query);
    $sth->execute($data);

    $total = 0;

    while ( $row = $sth->fetch( PDO::FETCH_ASSOC ) ) {
        if ( empty($row['account_name']) ) { $row['account_name'] = '[No Account]'; }
        $total += $row['amount'];
        $rows[] = array(
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
            array('column_name' => 'amount','value' => number_format($row['amount'] ?? 0,2),),
        );
    }
    $rows[] = array(
        array(
            'width' => 6,
            'value' => 'Total',
        ),
        array('column_name' => 'total','value' => number_format($total,2) ),
    );
}
else {
    $params[] = array(
        'type' => 'date',
        'label' => 'Date Entered',
        'name' => 'date',
        'value' => date( 'Y-m-d' ),
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
        'first_blank' => true,
    );
}

$output = array(
    'op' => $op,
    'params' => $params,
    'paged' => false,
    'report_title' => $title,
    'report_header' => $header,
    'report_body' => $rows,
);
output( $output, 'reports/report_template' );
?>
