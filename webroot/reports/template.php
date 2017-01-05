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
$title = 'FIXME Report Name Here';

$header = array(
    array('width' => 0, 'column_name' => 'FIXME name', 'column_title' => 'FIXME display name', 'sort' => 1, 'clean_attr' => '1'),
);

//FIXME enter query
$query = 'SELECT actions.date, contacts.name, contacts.company, contacts.street, contacts.city, contacts.state, contacts.zip, actions.amount FROM actions LEFT JOIN contacts USING (contactid) WHERE actions.amount >= 500';

if ( !empty($op) ) {
    // FIXME gather params
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
                'width' => 0,
                'new_group' => false,
                'column_name' => 'FIXME name',
                'clean_value' => $row['FIXME name'],
                'link' => '',
                'value' => date('m/d/Y',strtotime($row['FIXME name'])),
            ),
            array('column_name' => 'FIXME','value' => $row['FIXME'],),
        );
    }
}
else {
    $params[] = array(
        'type' => 'date', // input,select,check,radio,number
        'label' => 'FIXME Label',
        'name' => 'FIXME name',
        'min' => 0,
        'max' => 0,
        'step' => 1,
        'option_loop' => array(), // array('value'=>'','label'=>'','selected'=>)
        'first_blank' => 0,
        'size' => 0,
        'multiple' => 0,
        'checked' => 0,
        'id' => '',
        'value' => '',
        'onchange' => '',
        'pattern' => '',
    );
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
