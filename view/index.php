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
<p>Welcome to the WCSD Foundation Donor Database.</p>
<p>Total Assets: $<?= number_format($data['total'],2) ?></p>
<?php if ($data['retired_total']) { ?>
<p>Total Assets in Retired Accounts: $<?= number_format($data['retired_total'],2) ?></p>
<?php } ?>
</div>
</div>
<?php
include 'footer.php';
?>

</body>
</html>
