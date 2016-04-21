<!DOCTYPE html>
<html>
<head>
<?php include 'head.php';?>
<script src="<?= $data['_config']['base_url'] ?>tablefilter/tablefilter.js"></script>
</head>
<body>
<?php include 'header.php'; ?>
<div class="uk-flex uk-margin-left uk-margin-right">
<?php include 'menu.php'; ?>
<div class="uk-margin uk-margin-left">
<h2>Actions</h2>
<table class="uk-table" id="actions_table">
<thead>
<tr><th>Date</th><th>Amount</th><th>Contact Name</th><th>Contact Company</th><th>Account Name</th><th>Site</th><th>Receipt</th><th>P.O.</th><th>Notes</th></tr>
</thead>
<tbody>
<?php
   if ( !empty($data['actions_list']) ) {
     foreach ( $data['actions_list'] as $row ) {
?>
<tr id="actions_<?= $row['actionid'] ?>">
<td><?= $row['date'] ?></td>
<td><span class="<?= $row['amount'] < 0 ? 'uk-text-danger' : '' ?>"><?= $row['amount'] ?></span></td>
<td><?= $row['contact_name'] ?></td>
<td><?= $row['company'] ?></td>
<td><?= $row['account_name'] ?></td>
<td><?= $row['location_name'] ?></td>
<td><?= $row['receipt'] ?></td>
<td><?= $row['po'] ?></td>
<td><?= $row['note'] ?></td>
</tr>
<?php
     }
   }

?>
</tbody>
</table>
<script>
   var table_filter = new TableFilter( document.querySelector('#actions_table') , {
      base_path: '<?= $data["_config"]["base_url"] ?>tablefilter/',
      alternate_rows: true,
      extensions: [{
         name: 'sort',
         images_path: '<?= $data["_config"]["base_url"] ?>tablefilter/style/themes/'
      }]
   })
   table_filter.init();
</script>
</div>
</div>
<?php
include 'footer.php';
?>

</body>
</html>
