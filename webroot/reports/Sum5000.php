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
$title = 'Sum of Donations that are 5000+';

$header = array(
    array('column_name' => 'contact', 'column_title' => 'Contact Name', 'sort' => 1,),
    array('column_name' => 'company', 'column_title' => 'Contact Company', 'sort' => 1,),
    array('column_name' => 'street', 'column_title' => 'Address',),
    array('column_name' => 'city', 'column_title' => 'City',),
    array('column_name' => 'state', 'column_title' => 'State',),
    array('column_name' => 'zip', 'column_title' => 'Zip',),
    array('column_name' => 'amount', 'column_title' => 'Amount', 'sort' => 1,),
);

$query = 'SELECT contactid, contacts.name, contacts.company, contacts.street, contacts.city, contacts.state, contacts.zip, SUM(actions.amount) AS amount FROM actions LEFT JOIN contacts USING (contactid) WHERE in_kind = 0 ';

if ( !empty($op) ) {
    $s_date = input( 'start_date', INPUT_HTML_NONE );
    $e_date = input( 'end_date', INPUT_HTML_NONE );
    $locationid = input( 'locationid', INPUT_PINT );
    $wheres = array();
    $data = array();

    if ( !empty($s_date) ) {
        $wheres[] = "udate >= ?";
        $data[] = $s_date;
    }
    if ( !empty($e_date) ) {
        $wheres[] = "udate <= ?";
        $data[] = $e_date;
    }
    if ( !empty($locationid) ) {
        $wheres[] = "locationid = ?";
        $data[] = $locationid;
    }
    if ( !empty($wheres) ) {
        $query .= "AND ". ( implode( ' AND ', $wheres ) ) ." ";
    }

    $query .= 'GROUP BY contactid HAVING amount >= 5000';

    $dbh = db_connect('core');
    $sth = $dbh->prepare($query);
    $sth->execute($data);

    while ( $row = $sth->fetch( PDO::FETCH_ASSOC ) ) {
        $rows[] = array(
            array('column_name' => 'contact','value' => $row['name'],),
            array('column_name' => 'company','value' => $row['company'],),
            array('column_name' => 'street','value' => $row['street'],),
            array('column_name' => 'city','value' => $row['city'],),
            array('column_name' => 'state','value' => $row['state'],),
            array('column_name' => 'zip','value' => $row['zip'],),
            array('column_name' => 'amount','value' => number_format($row['amount'],2),),
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
