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
    if ( !empty($locationid) ) {
        $where[] = "actions.locationid = ?";
        $data[] = $locationid;
    }
    if ( !empty($where) ) {
        $query .= " WHERE ". implode( ' AND ', $where );
    }
    $query .= " ORDER BY account_name";

    $dbh = db_connect('core');
    $sth = $dbh->prepare($query);
    $sth->execute($data);
    $page = null;
    $total = null;

    $query = "SELECT SUM(amount) AS amount FROM actions WHERE accountid = ? AND date < ? GROUP BY accountid";
    $sth2 = $dbh->prepare($query);

    while ( $row = $sth->fetch( PDO::FETCH_ASSOC ) ) {
        if ( $page != $row['account_name'] ) {
            if ( !is_null($total) ) {
                $rows[] = array(
                    array('width'=>'6','column_name' => 'note','value' => 'Total','header'=>true,),
                    array('column_name' => 'amount','value' => $total,'header'=>true,),
            );
            }
            $rows[] = array(
                array(
                    'new_group' => true,
                    'width' => "7",
                    'column_name' => 'note',
                    'value' => $row['account_name'],
                    'header'=>true,
                ),
            );
            $sth2->execute( array($row['accountid'],$s_date) );
            $previous = $sth2->fetch( PDO::FETCH_ASSOC );

            $total = $previous['amount'];
            $rows[] = array(
                array('width'=>'6','column_name' => 'note','value' => 'Calculated Previous Balance',),
                array('column_name' => 'amount','value' => $previous['amount'],),
            );
        }
        $page = $row['account_name'];
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
            array('column_name' => 'amount','value' => $row['amount'],),
        );
    }
    if ( !is_null($total) ) {
        $rows[] = array(
            array('width'=>'6','column_name' => 'note','value' => 'Total','header'=>true,),
            array('column_name' => 'amount','value' => $total,'header'=>true,),
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
    $loc_loop = array();
    foreach ( $locations as $loc ) {
        $loc_loop[] = array('value'=>$loc['locationid'],'label'=>$loc['name']);
    }
    $params[] = array(
        'type' => 'select',
        'label' => 'Location',
        'name' => 'locationid',
        'option_loop' => $loc_loop,
        'first_blank' => 0,
    );
}

$output = array(
    'op' => $op,
    'params' => $params,
    'report_title' => $title,
    'paged' => false,
    'report_header' => $header,
    'report_body' => $rows,
);
output( $output, 'reports/report_template' );
?>
