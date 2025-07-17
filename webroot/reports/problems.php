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
$title = 'Possible Problems Report';

$checks = array(
    array(
        'legend' => 'Transactions at other locations',
        'columns' => array(
            array('column_name' => 'date', 'column_title' => 'Transaction Date', 'sort' => 1, 'clean_attr' => '1'),
            array('column_name' => 'amount', 'column_title' => 'Amount',),
            array('column_name' => 'note', 'column_title' => 'Note',),
            array('column_name' => 'po', 'column_title' => 'PO',),
            array('column_name' => 't_loc_name', 'column_title' => 'Transaction Location', 'sort' => 1,),
            array('column_name' => 'name', 'column_title' => 'Account', 'sort' => 1),
            array('column_name' => 'a_loc_name', 'column_title' => 'Account Location', 'sort' => 1),
            array('column_name' => 'c_name', 'column_title' => 'Contact'),
            array('column_name' => 'company', 'column_title' => 'Company'),
        ),
        'query' => 'SELECT actions.date, actions.amount, actions.note, actions.po, l1.name AS t_loc_name, accounts.name, l2.name AS a_loc_name, contacts.name AS c_name, contacts.company, actions.actionid FROM actions LEFT JOIN location AS l1 USING (locationid) LEFT JOIN contacts USING (contactid) LEFT JOIN accounts USING (accountid) LEFT JOIN location AS l2 ON (accounts.locationid = l2.locationid) LEFT JOIN modlog_accounts AS mla ON (mla.accountid = accounts.accountid AND field = "locationid" AND old_value = actions.locationid AND new_value = accounts.locationid AND mla.timestamp >= actions.udate) WHERE actions.in_kind = 0 AND actions.locationid <> accounts.locationid AND mla.timestamp IS NULL',
        'sql_params' => array(),
        'rows' => array(),
    ),
);

if ( !empty($op) ) {
    // FIXME gather params
    $s_date = input( 'start_date', INPUT_HTML_NONE );
    $e_date = input( 'end_date', INPUT_HTML_NONE );
    $locationid = input( 'locationid', INPUT_PINT );
    $no_trans = input( 'no_trans', INPUT_STR );

    // Start Transactions at other locations check
    if ( !empty($no_trans) ) {
        $checks[0]['query'] .= " AND is_transfer = 0";
    }
    if ( !empty($s_date) ) {
        $checks[0]['query'] .= " AND date >= ?";
        $checks[0]['sql_params'][] = $s_date;
    }
    if ( !empty($e_date) ) {
        $checks[0]['query'] .= " AND date <= ?";
        $checks[0]['sql_params'][] = $e_date;
    }
    if ( !empty($locationid) ) {
        $checks[0]['query'] .= " AND actions.locationid = ?";
        $checks[0]['sql_params'][] = $locationid;
    }

    $dbh = db_connect('core');
    $sth = $dbh->prepare($checks[0]['query']);
    $sth->execute($checks[0]['sql_params']);

    while ( $row = $sth->fetch( PDO::FETCH_ASSOC ) ) {
        $checks[0]['rows'][] = array(
            array(
                'column_name' => 'date',
                'clean_value' => $row['date'],
                'value' => date('m/d/Y',strtotime($row['date'])),
            ),
            array('column_name' => 'amount','value' => number_format($row['amount'],2), 'link' => '../editaction.php?actionid='. $row['actionid']),
            array('column_name' => 'note','value' => $row['note'],),
            array('column_name' => 'po','value' => $row['po'],),
            array('column_name' => 't_loc_name','value' => $row['t_loc_name'],),
            array('column_name' => 'name','value' => $row['name'],),
            array('column_name' => 'a_loc_name','value' => $row['a_loc_name'],),
            array('column_name' => 'c_name','value' => $row['c_name'],),
            array('column_name' => 'company','value' => $row['company'],),
        );
    }
    // End Transactions at other locations check
}
else {
    $s_date = $e_date = "";
    $month = date('n'); $year = date('Y');
    if ( $month >= 7 ) {
        $s_date = date( 'Y-m-d', mktime(1,1,1,7,1,$year) );
    } else {
        $s_date = date( 'Y-m-d', mktime(1,1,1,7,1,$year-1) );
    }
    $params[] = array(
        'type' => 'date',
        'label' => 'Date Between',
        'name' => 'start_date',
        'value' => $s_date,
    );
    $params[] = array(
        'type' => 'date',
        'label' => 'And',
        'name' => 'end_date',
        'value' => $e_date,
    );

    $params[] = array(
        'type' => 'check',
        'label' => 'Skip transfers',
        'name' => 'no_trans',
        'value' => 'NotEmpty',
        'checked' => 1,
    );

    $loc_loop = array();
    foreach ( $locations as $loc ) {
        $loc_loop[] = array('value' => $loc['locationid'],'label' => $loc['name']);
    }
    $params[] = array(
        'type' => 'select',
        'label' => 'Location',
        'name' => 'locationid',
        'option_loop' => $loc_loop,
        'first_blank' => 1,
    );
}

$output = array(
    'op' => $op,
    'params' => $params,
    'paged' => true,
    'report_title' => $title,
    'checks' => $checks,
);
output( $output, 'reports/problems' );
?>
