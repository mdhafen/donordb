<!DOCTYPE html>
<html>
<head>
 <?php $page_title='Manage Locations'; include $data['_config']['base_dir'] .'/htdocs/head.php';?>
</head>
<body>
<?php include $data['_config']['base_dir'] .'/htdocs/header.php'; ?>
<div class="uk-flex uk-margin-left uk-margin-right">
<?php include $data['_config']['base_dir'] .'/htdocs/menu.php'; ?>
<div class="uk-container uk-margin uk-width-1-1">
    <h2>Manage Locations</h2>
<a href="<?= $data['_config']['base_url'] ?>manage/edit_location.php">Add Location</a>

<table class="uk-table uk-table-striped">
<thead>
<tr>
<th>Location ID</th><th>Name</th><th>&nbsp;</th>
</tr>
</thead>
<tbody>
<?php foreach ( $data['locations'] as $loc ) { ?>
<tr>
<td><?= $loc['locationid'] ?></td>
<td><?= $loc['name'] ?></td>
<td>
<div class="uk-button-group">
<a href="<?= $data['_config']['base_url'] ?>manage/edit_location.php?locationid=<?= $loc['locationid'] ?>" class="uk-button">Edit</a> 
<a href="<?= $data['_config']['base_url'] ?>manage/delete_location.php?locationid=<?= $loc['locationid'] ?>" class="uk-button">Delete</a>
</div>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
<?php
include $data['_config']['base_dir'] .'/htdocs/footer.php';
?>

</body>
</html>
