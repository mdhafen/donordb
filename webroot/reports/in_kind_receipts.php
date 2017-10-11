<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );

authorize( 'reports' );

$op = input( 'op', INPUT_STR );

$params = array();

$rows = array();
$title = 'In Kind Receipts';

$header = array(
    array('column_name' => 'date', 'column_title' => 'Date', 'sort' => 1, 'clean_attr' => '1'),
    array('column_name' => 'contact', 'column_title' => 'Contact Name', 'sort' => 1,),
    array('column_name' => 'company', 'column_title' => 'Contact Company', 'sort' => 1,),
    array('column_name' => 'street', 'column_title' => 'Address',),
    array('column_name' => 'city', 'column_title' => 'City',),
    array('column_name' => 'state', 'column_title' => 'State',),
    array('column_name' => 'zip', 'column_title' => 'Zip',),
    array('column_name' => 'amount', 'column_title' => 'Amount', 'sort' => 1,),
    array('column_name' => 'location', 'column_title' => 'Location', 'sort' => 1,),
    array('column_name' => 'acc_id', 'column_title' => 'Account Number', 'sort' => 1,),
    array('column_name' => 'account', 'column_title' => 'Account', 'sort' => 1,),
    array('column_name' => 'receipt', 'column_title' => 'Receipt',),
);

$query = 'SELECT actions.date, contacts.name, contacts.company, contacts.street, contacts.city, contacts.state, contacts.zip, actions.amount, accounts.accountid, accounts.name AS account_name, location.name AS location_name, receipt FROM actions LEFT JOIN location USING (locationid) LEFT JOIN accounts USING (accountid) LEFT JOIN contacts USING (contactid) WHERE actions.amount >= 0 AND in_kind = 1';

if ( !empty($op) ) {
    $s_date = input( 'start_date', INPUT_HTML_NONE );
    $e_date = input( 'end_date', INPUT_HTML_NONE );
    $data = array();

    if ( !empty($s_date) ) {
        $query .= " AND udate >= ?";
        $data[] = $s_date;
    }
    if ( !empty($e_date) ) {
        $query .= " AND udate <= ?";
        $data[] = $e_date;
    }

    $dbh = db_connect('core');
    $sth = $dbh->prepare($query);
    $sth->execute($data);

    while ( $row = $sth->fetch( PDO::FETCH_ASSOC ) ) {
        if ( empty($row['account_name']) ) { $row['account_name'] = '[No Account]'; }
        $rows[] = array(
            array(
                'column_name' => 'date',
                'clean_value' => $row['date'],
                'value' => date('m/d/Y',strtotime($row['date'])),
            ),
            array('column_name' => 'contact','value' => $row['name'],),
            array('column_name' => 'company','value' => $row['company'],),
            array('column_name' => 'street','value' => $row['street'],),
            array('column_name' => 'city','value' => $row['city'],),
            array('column_name' => 'state','value' => $row['state'],),
            array('column_name' => 'zip','value' => $row['zip'],),
            array('column_name' => 'amount','value' => number_format($row['amount'],2),),
            array('column_name' => 'location','value' => $row['location_name'],),
            array('column_name' => 'acc_id','value' => $row['accountid'],),
            array('column_name' => 'account','value' => $row['account_name'],),
            array('column_name' => 'receipt','value' => $row['receipt'],),
        );
    }
}
else {
    $s_date = $e_date = "";
    $month = date('n'); $year = date('Y');
    if ( $month >= 7 ) {
        $s_date = date( 'Y-m-d', mktime(1,1,1,7,1,$year) );
        $e_date = date( 'Y-m-d', mktime(1,1,1,6,30,$year+1) );
    } else {
        $s_date = date( 'Y-m-d', mktime(1,1,1,7,1,$year-1) );
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
