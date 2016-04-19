<!DOCTYPE html>
<html>
<head>
 <?php $page_title='Delete User'; include $data['_config']['base_dir'] .'/htdocs/head.php';?>
</head>
<body>
<?php include $data['_config']['base_dir'] .'/htdocs/header.php'; ?>
<div class="uk-flex uk-margin-left">
<?php include $data['_config']['base_dir'] .'/htdocs/menu.php'; ?>
<div class="uk-flex-item-auto">
    <div class="uk-container uk-container-center">

<?php if ( !empty($data['deleted']) ) { ?>
<div class="uk-alert uk-alert-success">User Deleted</div>
<META HTTP-EQUIV="Refresh" CONTENT="0;URL=<?= $data['_config']['base_url'] ?>manage/users.php">
<?php } else { ?>

        <form method="post" action="<?= $data['_config']['base_url'] ?>manage/delete_user.php" class="uk-form">
            <input type="hidden" name="userid" id="userid" value="<?= $data['user']['userid'] ?>">
            <div class="uk-panel uk-panel-box">
                <h2 class="uk-panel-title uk-text-center">Are you sure you want to delete <?= $data['user']['fullname'] ?> ( <?= $data['user']['username'] ?> )?</h2>

                <div class="uk-form-row">
                    <input class="uk-button" type="submit" name="op" id="op" value="Delete">
                    <a class="uk-button" href="users.php">Back</a>
                </div>
            </div>
        </form>
<?php } ?>
    </div>
</div>
</div>
<?php
include $data['_config']['base_dir'] .'/htdocs/footer.php';
?>

</body>
</html>
