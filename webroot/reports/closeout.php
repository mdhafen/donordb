<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );

authorize( 'reports' );

$op = input( 'op', INPUT_STR );

$params = array();

$rows = array();
$title = 'Close Out';

$header = array(
    array('column_name' => 'date', 'column_title' => 'Date', 'sort' => 1, 'clean_attr' => '1'),
    array('column_name' => 'contact', 'column_title' => 'Contact Name', 'sort' => 1,),
    array('column_name' => 'company', 'column_title' => 'Contact Company', 'sort' => 1,),
    array('column_name' => 'receipt', 'column_title' => 'Receipt',),
    array('column_name' => 'po', 'column_title' => 'PO',),
    array('column_name' => 'note', 'column_title' => 'Action Note',),
    array('column_name' => 'acc_id', 'column_title' => 'Account Number', 'sort' => 1,),
    array('column_name' => 'account', 'column_title' => 'Account', 'sort' => 1,),
    array('column_name' => 'account_note', 'column_title' => 'Account Note',),
    array('column_name' => 'amount', 'column_title' => 'Amount', 'sort' => 1,),
);

$query = 'SELECT actions.date, contacts.name, contacts.company, actions.receipt, actions.po, actions.note, accounts.accountid, accounts.name AS account_name, accounts.note AS account_note, actions.amount FROM actions LEFT JOIN accounts USING (accountid) LEFT JOIN contacts USING (contactid)';

if ( !empty($op) ) {
    $s_date = input( 'start_date', INPUT_HTML_NONE );
    $e_date = input( 'end_date', INPUT_HTML_NONE );
    $where = array();
    $data = array();

    if ( !empty($s_date) ) {
        $where[] = "udate >= ?";
        $data[] = $s_date;
    }
    if ( !empty($e_date) ) {
        $where[] = "udate <= ?";
        $data[] = $e_date;
    }
    if ( !empty($where) ) {
        $query .= " WHERE ". implode( ' AND ', $where);
    }

    $dbh = db_connect('core');
    $sth = $dbh->prepare($query);
    $sth->execute($data);

    while ( $row = $sth->fetch( PDO::FETCH_ASSOC ) ) {
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
            array('column_name' => 'acc_id','value' => $row['accountid'],),
            array('column_name' => 'account','value' => $row['account_name'],),
            array('column_name' => 'account_note','value' => $row['account_note'],),
            array('column_name' => 'amount','value' => number_format($row['amount'],2),),
        );
    }
}
else {
    $params[] = array(
        'type' => 'date',
        'label' => 'Start Date',
        'name' => 'start_date',
    );
    $params[] = array(
        'type' => 'date',
        'label' => 'End Date',
        'name' => 'end_date'
    );
}

$output = array(
    'op' => $op,
    'params' => $params,
    'paged' => true,
    'report_title' => $title,
    'report_header' => $header,
    'report_body' => $rows,
);
output( $output, 'reports/report_template' );
?>
