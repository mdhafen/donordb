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
$title = 'Actions that are 500+';

$header = array(
    array('column_name' => 'date', 'column_title' => 'Date', 'sort' => 1, 'clean_attr' => '1'),
    array('column_name' => 'contact', 'column_title' => 'Contact Name', 'sort' => 1,),
    array('column_name' => 'company', 'column_title' => 'Contact Company', 'sort' => 1,),
    array('column_name' => 'street', 'column_title' => 'Address',),
    array('column_name' => 'city', 'column_title' => 'City',),
    array('column_name' => 'state', 'column_title' => 'State',),
    array('column_name' => 'zip', 'column_title' => 'Zip',),
    array('column_name' => 'amount', 'column_title' => 'Amount', 'sort' => 1,),
);

$query = 'SELECT actions.date, contacts.name, contacts.company, contacts.street, contacts.city, contacts.state, contacts.zip, actions.amount FROM actions LEFT JOIN contacts USING (contactid) WHERE actions.amount >= 500';

if ( !empty($op) ) {
    $s_date = input( 'start_date', INPUT_HTML_NONE );
    $e_date = input( 'end_date', INPUT_HTML_NONE );
    $locationid = input( 'locationid', INPUT_PINT );
    $data = array();

    if ( !empty($s_date) ) {
        $query .= " AND udate >= ?";
        $data[] = $s_date;
    }
    if ( !empty($e_date) ) {
        $query .= " AND udate <= ?";
        $data[] = $e_date;
    }
    if ( !empty($locationid) ) {
        $query .= " AND locationid = ?";
        $data[] = $locationid;
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
            array('column_name' => 'street','value' => $row['street'],),
            array('column_name' => 'city','value' => $row['city'],),
            array('column_name' => 'state','value' => $row['state'],),
            array('column_name' => 'zip','value' => $row['zip'],),
            array('column_name' => 'amount','value' => $row['amount'],),
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
    'paged' => true,
    'report_title' => $title,
    'report_header' => $header,
    'report_body' => $rows,
);
output( $output, 'reports/report_template' );
?>
