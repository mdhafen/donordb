<?php

include_once( '../../lib/config.phpm' );
include_once( '../../lib/data.phpm' );

$db_settings = $config['database']['core']['write'];
$table = $db_settings['schema'];

$dbh = db_connect();
$query = "SELECT NUMERIC_PRECISION AS count FROM information_schema.columns WHERE table_schema = '$table' AND table_name = 'actions' AND column_name = 'receipt'";
$sth = $dbh->query( $query );
$row = $sth->fetch();
if ( $row['count'] == 10 ) {

  $query = "ALTER TABLE actions CHANGE COLUMN receipt receipt VARCHAR(8) DEFAULT NULL";
  $dbh->exec( $query );

  return "Change actions.receipt from int(10) to varchar(8)";
}

?>
