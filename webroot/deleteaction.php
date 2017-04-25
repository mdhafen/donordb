<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'actions' );

$confirm = input( 'confirm', INPUT_STR );
$actionid = input( 'actionid', INPUT_PINT );

$action = array();
$errors = array();
if ( !empty($actionid) ) {
    $action = get_actions_cross( array($actionid) )[$actionid];
}

if ( !empty($confirm) ) {
    $result = delete_action( $actionid );
    if ( $result ) {
        $errors['ACTION_DELETE'] = 'There was an error deleting the action!';
    }
    else {
        redirect("actions.php");
    }
}

$output = array(
    'actionid' => $actionid,
    'action' => $action,
    'errors' => $errors,
);
output( $output, 'deleteaction' );
?>
