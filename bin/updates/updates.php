<?php

$updates = array(
  '0001-Change_receipt_to_varchar',
  '0002-Add_modlog_actions',
);
$results = array();

foreach ( $updates as $file ) {
  if ( is_readable( $file .".php" ) ) {
    $result = include( $file .".php" );
    if ( strlen($result) > 1 ) { $results[] = $result; }
  }
}

foreach ( $results as $msg ) {
  print $msg ."\n";
}

?>
