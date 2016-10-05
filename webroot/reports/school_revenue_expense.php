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

//FIXME enter query
$query = 'SELECT location.name, (SELECT SUM(actions.amount) from actions WHERE udate <= ? AND locationid = locationid) AS beginning, 0 AS transfers, (SELECT SUM(actions.amount) from actions WHERE udate <= ? AND udate >= ? AND amount > 0 AND locationid = locationid) AS revenue, (SELECT SUM(actions.amount) from actions WHERE udate <= ? AND udate >= ? AND amount < 0 AND locationid = locationid) AS expense, SUM(actions.amount) AS ending FROM actions LEFT JOIN locations USING (locationid) WHERE udate <= ? GROUP BY locationid';

if ( !empty($op) ) {
    // FIXME gather params
    $s_date = input( 'start_date', INPUT_HTML_NONE );
    $e_date = input( 'end_date', INPUT_HTML_NONE );
    $data = array( $s_date, $e_date, $s_date, $e_date, $s_date, $e_date );

    $dbh = db_connect('core');
    $sth = $dbh->prepare($query);
    $sth->execute($data);

    while ( $row = $sth->fetch( PDO::FETCH_ASSOC ) ) {
        $rows[] = array(
            array('column_name' => 'name','value' => $row['name'],),
            array(
                'column_name' => 'beginning',
                'value' => number_format($row['beginning']),
            ),
            array(
                'column_name' => 'transfers',
                'value' => number_format($row['transfers']),
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
    'paged' => true,
    'report_title' => $title,
    'report_header' => $header,
    'report_body' => $rows,
);
output( $output, 'reports/report_template' );
?>
