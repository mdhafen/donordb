<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );

authorize( 'reports' );

$default_loc = '950';
$op = input( 'op', INPUT_STR );

$params = array();
$locations = all_locations();
uasort( $locations, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );

$rows = array();
$title = 'School Revenue and Expense by Account';

$header = array(
    array('column_name' => 'name', 'column_title' => 'Account'),
    array('column_name' => 'beginning', 'column_title' => 'Beginning Balance'),
    array('column_name' => 'transfers', 'column_title' => 'Transfers'),
    array('column_name' => 'revenues', 'column_title' => 'Revenues'),
    array('column_name' => 'expenses', 'column_title' => 'Expenses'),
    array('column_name' => 'ending', 'column_title' => 'Ending Balance'),
);


if ( !empty($op) ) {
    $s_date = input( 'start_date', INPUT_HTML_NONE );
    $e_date = input( 'end_date', INPUT_HTML_NONE );
    $loc = input( 'locationid', INPUT_PINT );
    $title = $title .' For '.date('m/d/Y',strtotime($s_date)).' to '.date('m/d/Y',strtotime($e_date));

    $dbh = db_connect('core');

    $s_date = $dbh->quote($s_date);
    $e_date = $dbh->quote($e_date);
    $query = "SELECT '[No Account]' as name, (SELECT COALESCE(SUM(amount),0.00) from actions AS a1 WHERE date < $s_date AND in_kind = 0 AND a1.accountid IS NULL AND a1.locationid = a0.locationid) AS beginning, (SELECT COALESCE(SUM(amount),0.00) from actions AS a2 WHERE date <= $e_date AND date >= $s_date AND is_transfer = 1 AND in_kind = 0 AND a2.accountid IS NULL AND a2.locationid = a0.locationid) AS transfer, (SELECT COALESCE(SUM(amount),0.00) from actions AS a3 WHERE date <= $e_date AND date >= $s_date AND amount > 0 AND in_kind = 0 AND is_transfer = 0 AND a3.accountid IS NULL AND a3.locationid = a0.locationid) AS revenue, (SELECT COALESCE(SUM(amount),0.00) from actions AS a4 WHERE date <= $e_date AND date >= $s_date AND amount < 0 AND in_kind = 0 AND is_transfer = 0 AND a4.accountid IS NULL AND a4.locationid = a0.locationid) AS expense, COALESCE(SUM(amount),0.00) AS ending FROM actions a0  WHERE in_kind = 0 AND date <= $e_date AND accountid IS NULL AND locationid = ? GROUP BY accountid ".
        "UNION ALL ".
        "SELECT a1.name, (SELECT COALESCE(SUM(amount),0.00) from actions AS a2 WHERE date < $s_date AND in_kind = 0 AND a2.accountid = a1.accountid) AS beginning, (SELECT COALESCE(SUM(amount),0.00) from actions AS a3 WHERE date <= $e_date AND date >= $s_date AND is_transfer = 1 AND in_kind = 0 AND a3.accountid = a1.accountid) AS transfer, (SELECT COALESCE(SUM(amount),0.00) from actions AS a4 WHERE date <= $e_date AND date >= $s_date AND amount > 0 AND in_kind = 0 AND is_transfer = 0 AND a4.accountid = a1.accountid) AS revenue, (SELECT COALESCE(SUM(amount),0.00) from actions AS a5 WHERE date <= $e_date AND date >= $s_date AND amount < 0 AND in_kind = 0 AND is_transfer = 0 AND a5.accountid = a1.accountid) AS expense, COALESCE(SUM(amount),0.00) AS ending FROM location AS l1 LEFT JOIN accounts AS a1 ON a1.locationid = l1.locationid LEFT JOIN actions a0 USING (accountid) WHERE in_kind = 0 AND date <= $e_date AND l1.locationid = ? GROUP BY a1.accountid ORDER BY name";

    $sth = $dbh->prepare($query);
    $sth->execute([$loc,$loc]);

    $beginning = $ending = $transfers = $revenues = $expenses = 0;

    while ( $row = $sth->fetch( PDO::FETCH_ASSOC ) ) {
        $beginning += $row['beginning'];
        $ending += $row['ending'];
        $transfers += $row['transfer'];
        $revenues += $row['revenue'];
        $expenses += $row['expense'];
        $rows[] = array(
            array('column_name' => 'name','value' => $row['name'],),
            array(
                'column_name' => 'beginning',
                'value' => number_format($row['beginning'],2),
            ),
            array(
                'column_name' => 'transfers',
                'value' => number_format($row['transfer'],2),
            ),
            array(
                'column_name' => 'revenues',
                'value' => number_format($row['revenue'],2),
            ),
            array(
                'column_name' => 'expenses',
                'value' => number_format($row['expense'],2),
            ),
            array(
                'column_name' => 'ending',
                'value' => number_format($row['ending'],2),
            ),
        );
    }
    $rows[] = array(
        array('column_name' => 'name','value' => 'Totals', 'new_group' => true),
        array(
            'column_name' => 'beginning',
            'value' => number_format($beginning,2),
        ),
        array(
            'column_name' => 'transfers',
            'value' => number_format($transfers,2),
        ),
        array(
            'column_name' => 'revenues',
            'value' => number_format($revenues,2),
        ),
        array(
            'column_name' => 'expenses',
            'value' => number_format($expenses,2),
        ),
        array(
            'column_name' => 'ending',
            'value' => number_format($ending,2),
        ),
    );
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

    $loc_loop = array();
    foreach ( $locations as $loc ) {
        $selected = ( $loc['locationid'] == $default_loc ) ? 1 : 0;
        $loc_loop[] = array('value'=>$loc['locationid'],'label'=>$loc['name'],'selected'=>$selected);
    }
    $params[] = array(
        'id' => 'location',
        'type' => 'select',
        'label' => 'Location',
        'name' => 'locationid',
        'option_loop' => $loc_loop,
        'filter_value_label' => 1,
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
