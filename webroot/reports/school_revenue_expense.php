<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );

authorize( 'reports' );

$op = input( 'op', INPUT_STR );

$params = array();

$rows = array();
$title = 'Revenue and Expense by School';

$header = array(
    array('column_name' => 'name', 'column_title' => 'School'),
    array('column_name' => 'beginning', 'column_title' => 'Beginning Balance'),
    array('column_name' => 'transfers', 'column_title' => 'Transfers'),
    array('column_name' => 'revenues', 'column_title' => 'Revenues'),
    array('column_name' => 'expenses', 'column_title' => 'Expenses'),
    array('column_name' => 'ending', 'column_title' => 'Ending Balance'),
);

$query = 'SELECT location.name, (SELECT SUM(amount) from actions AS a1 WHERE udate <= ? AND a1.locationid = a0.locationid) AS beginning, (SELECT SUM(amount) from actions AS a2 WHERE udate <= ? AND udate >= ? AND is_transfer = 1 AND a2.locationid = a0.locationid) AS transfer, (SELECT SUM(amount) from actions AS a3 WHERE udate <= ? AND udate >= ? AND amount > 0 AND is_transfer = 0 AND a3.locationid = a0.locationid) AS revenue, (SELECT SUM(amount) from actions AS a4 WHERE udate <= ? AND udate >= ? AND amount < 0 AND is_transfer = 0 AND a4.locationid = a0.locationid) AS expense, SUM(amount) AS ending FROM location LEFT JOIN actions AS a0 USING (locationid) WHERE udate <= ? GROUP BY locationid';

if ( !empty($op) ) {
    $s_date = input( 'start_date', INPUT_HTML_NONE );
    $e_date = input( 'end_date', INPUT_HTML_NONE );
    $data = array( $s_date, $e_date, $s_date, $e_date, $s_date, $e_date, $s_date, $e_date );
    $title = 'Revenue and Expense by School For '.date('m/d/Y',strtotime($s_date)).' to '.date('m/d/Y',strtotime($e_date));

    $dbh = db_connect('core');
    $sth = $dbh->prepare($query);
    $sth->execute($data);

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
                'value' => number_format($row['beginning']),
            ),
            array(
                'column_name' => 'transfers',
                'value' => number_format($row['transfer']),
            ),
            array(
                'column_name' => 'revenues',
                'value' => number_format($row['revenue']),
            ),
            array(
                'column_name' => 'expenses',
                'value' => number_format($row['expense']),
            ),
            array(
                'column_name' => 'ending',
                'value' => number_format($row['ending']),
            ),
        );
    }
    $rows[] = array(
        array('column_name' => 'name','value' => 'Totals', 'new_group' => true),
        array(
            'column_name' => 'beginning',
            'value' => number_format($beginning),
        ),
        array(
            'column_name' => 'transfers',
            'value' => number_format($transfers),
        ),
        array(
            'column_name' => 'revenues',
            'value' => number_format($revenues),
        ),
        array(
            'column_name' => 'expenses',
            'value' => number_format($expenses),
        ),
        array(
            'column_name' => 'ending',
            'value' => number_format($ending),
        ),
    );
}
else {
    $params[] = array(
        'type' => 'date',
        'label' => 'Start Date',
        'name' => 'start_date',
        'value' => date( 'Y-m-d', time() - 31622400 ),
    );
    $params[] = array(
        'type' => 'date',
        'label' => 'End Date',
        'name' => 'end_date',
        'value' => date( 'Y-m-d', time() ),
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
