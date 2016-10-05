<!DOCTYPE html>
<html>
<head>
<?php include $data['_config']['base_dir'].'/htdocs/head.php';?>
</head>
<body>
<?php include $data['_config']['base_dir'].'/htdocs/header.php'; ?>
<div class="uk-flex uk-margin-left uk-margin-right">
<?php include $data['_config']['base_dir'].'/htdocs/menu.php'; ?>
<div class="uk-margin uk-margin-left">
<ul class="uk-list uk-list-line uk-margin-left">
      <li><a href="<?= $data['_config']['base_url'] ?>reports/transactions.php">Transactions</a></li>
      <li><a href="<?= $data['_config']['base_url'] ?>reports/account_balance.php">Account Balance</a></li>
      <li><a href="<?= $data['_config']['base_url'] ?>reports/receipts.php">Receipts</a></li>
      <li><a href="<?= $data['_config']['base_url'] ?>reports/500.php">500+</a></li>
      <li><a href="<?= $data['_config']['base_url'] ?>reports/closeout.php">Close Out</a></li>
      <li><a href="<?= $data['_config']['base_url'] ?>reports/action_amounts.php">Action Amount</a></li>
</ul>
</div>
</div>
<?php
include $data['_config']['base_dir'].'/htdocs/footer.php';
?>

</body>
</html>
