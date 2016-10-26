<!DOCTYPE html>
<html>
<head>
<?php $page_title='Manage Users'; include $data['_config']['base_dir'] .'/htdocs/head.php';?>
</head>
<body>
<?php include $data['_config']['base_dir'] .'/htdocs/header.php'; ?>
<div class="uk-flex uk-margin-left uk-margin-right">
<?php include $data['_config']['base_dir'] .'/htdocs/menu.php'; ?>
<div class="uk-container uk-margin uk-width-1-1">
    <h2>Manage Users</h2>

    <a href="<?= $data['_config']['base_url'] ?>manage/edit_user.php">Add User</a>

<table class="uk-table uk-table-striped">
<thead>
<tr>
<td>Full Name</td><td>username</td><td>Role</td><td>Password</td><td>&nbsp;</td>
</tr>
</thead>
<tbody>
<?php foreach ( $data['users'] as $user ) { ?>
<tr>
<td><?= $user['fullname'] ?></td>
<td><?= $user['username'] ?></td>
<td><?= $user['role'] ?></td>
<td><?= $user['password']?"Yes":"No" ?></td>
<td>
<div class="uk-button-group">
<a href="<?= $data['_config']['base_url'] ?>manage/edit_user.php?userid=<?= $user['userid'] ?>" class="uk-button">Edit</a> 
<a href="<?= $data['_config']['base_url'] ?>manage/delete_user.php?userid=<?= $user['userid'] ?>" class="uk-button">Delete</a>
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
