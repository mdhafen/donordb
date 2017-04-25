<!DOCTYPE html>
<html>
<head>
<?php include 'head.php';?>
</head>
<body>
<?php include 'header.php'; ?>
<div class="uk-flex uk-margin-left uk-margin-right">
<?php include 'menu.php'; ?>
<div class="uk-margin uk-margin-left">
Welcome to the WCSD Foundation Donor Database.
</div>
<div class="uk-margin uk-margin-left">
Total Assets: $<?= number_format($data['total'],2) ?>
</div>
</div>
<?php
include 'footer.php';
?>

</body>
</html>
