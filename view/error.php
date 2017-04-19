<!DOCTYPE html>
<html>
<head>
<?php include 'head.php'; ?>
</head>
<body>
<?php include 'header.php'; ?>
<h2>There was an error</h2>
<div id="errors" class="uk-alert uk-alert-danger">
<?php
if ( !empty($data['errors']) ) {
   foreach ( $data['errors'] as $flag => $text ) {
?>
<p><!-- <?= $flag ?> --><?= $text ?></p>
<?php
   }
}
?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
