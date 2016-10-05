<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );

authorize( 'reports' );

$op = input( 'op', INPUT_STR );

$params = array();

$rows = array();
$title = 'Action Amounts';

$header = array(
    array('column_name' => 'date', 'column_title' => 'Date', 'sort' => 1, 'clean_attr' => '1'),
    array('column_name' => 'contact', 'column_title' => 'Contact Name', 'sort' => 1,),
    array('column_name' => 'company', 'column_title' => 'Contact Company', 'sort' => 1,),
    array('column_name' => 'location', 'column_title' => 'Location', 'sort' => 1,),
    array('column_name' => 'acc_id', 'column_title' => 'Account Number', 'sort' => 1,),
    array('column_name' => 'account', 'column_title' => 'Account', 'sort' => 1,),
    array('column_name' => 'note', 'column_title' => 'Note',),
    array('column_name' => 'amount', 'column_title' => 'Amount', 'sort' => 1,),
);

$query = 'SELECT actions.date, contacts.name, contacts.company, location.name AS location_name, accounts.accountid, accounts.name AS account_name, actions.note, actions.amount FROM actions LEFT JOIN location USING (locationid) LEFT JOIN contacts USING (contactid) LEFT JOIN accounts USING (accountid) WHERE actions.amount = ?';

if ( !empty($op) ) {
    $amount = input( 'amount', INPUT_NUM );
    $data = array( $amount );

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
            array('column_name' => 'location','value' => $row['location_name'],),
            array('column_name' => 'acc_id','value' => $row['accountid'],),
            array('column_name' => 'account','value' => $row['account_name'],),
            array('column_name' => 'note','value' => $row['note'],),
            array('column_name' => 'amount','value' => number_format($row['amount'],2),),
        );
    }
}
else {
    $params[] = array(
        'type' => 'number',
        'label' => 'Amount',
        'name' => 'amount',
        'step' => '0.01',
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
