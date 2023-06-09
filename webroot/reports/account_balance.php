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
$title = 'School Account Balance';

$header = array(
    array('column_name' => 'acc_id', 'column_title' => 'Account Number', 'sort' => 1,),
    array('column_name' => 'name', 'column_title' => 'Account Name', 'sort' => 1,),
    array('column_name' => 'amount', 'column_title' => 'Amount', 'sort' => 1,),
    array('column_name' => 'note', 'column_title' => 'Notes',),
);

$nonzero = input( 'nonzero', INPUT_STR );
$nonzero_str = '';
if ( !empty($nonzero) ) {
    $nonzero_str .= " HAVING total <> 0 ";
}

$query = '( SELECT actions.accountid,"[No Account]" AS name, SUM(actions.amount) AS total, "" AS note FROM actions WHERE actions.locationid = ? AND actions.accountid IS NULL AND ( actions.in_kind = 0 OR actions.in_kind IS NULL ) GROUP BY actions.accountid '. $nonzero_str .') UNION ( SELECT accounts.accountid,name, SUM(actions.amount) AS total, accounts.note FROM accounts LEFT JOIN actions USING (accountid) WHERE accounts.locationid = ? AND ( actions.in_kind = 0 OR actions.in_kind IS NULL ) GROUP BY actions.accountid,accounts.accountid '. $nonzero_str .') ';

if ( !empty($op) ) {
    $locationid = input( 'locationid', INPUT_PINT );
    $data = array( $locationid, $locationid );

    $query .= "ORDER BY name";

    $dbh = db_connect('core');
    $sth = $dbh->prepare($query);
    $sth->execute($data);

    while ( $row = $sth->fetch( PDO::FETCH_ASSOC ) ) {
        if ( empty($row['name']) ) { $row['name'] = '[No Account]'; }
        $rows[] = array(
            array('column_name' => 'acc_id','value' => $row['accountid'],),
            array('column_name' => 'name','value' => $row['name'],),
            array('column_name' => 'amount','value' => number_format($row['total'],2),),
            array('column_name' => 'note','value' => $row['note'],),
        );
    }
}
else {
    foreach ( $locations as $loc ) {
        $loc_loop[] = array('value'=>$loc['locationid'],'label'=>$loc['name']);
    }
    $params[] = array(
        'type' => 'select',
        'label' => 'Location',
        'name' => 'locationid',
        'option_loop' => $loc_loop,
        'first_blank' => 0,
        'filter_value_label' => 1,
        'filter_label' => 0
    );
    $params[] = array(
        'type' => 'check',
        'label' => 'Non-Zero Only',
        'name' => 'nonzero',
        'value' => 'NotEmpty',
        'checked' => 0,
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
