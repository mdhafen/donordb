<!DOCTYPE html>
<html>
<head>
<?php $page_title='Management'; include $data['_config']['base_dir'] .'/htdocs/head.php';?>
</head>
<body>
<?php include $data['_config']['base_dir'] .'/htdocs/header.php'; ?>
<div class="uk-flex uk-margin-left">
<?php include $data['_config']['base_dir'] .'/htdocs/menu.php'; ?>
<div class="uk-flex-item-auto">
    <div class="uk-container uk-container-center">
        <h2 class="uk-text-center">Site Management</h2>
        <div class="uk-panel uk-panel-box">
            <a class="uk-button uk-width-1-1 uk-margin-bottom" href="locations.php">Locations</a>

            <a class="uk-button uk-width-1-1 uk-margin-bottom" href="users.php">Users</a>
        </div>
    </div>
</div>
</div>
<?php
include $data['_config']['base_dir'] .'/htdocs/footer.php';
?>

</body>
</html>
