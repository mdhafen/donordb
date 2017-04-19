<!DOCTYPE html>
<html>
<head>
 <?php $page_title='Delete Location'; include $data['_config']['base_dir'] .'/view/head.php';?>
</head>
<body>
<?php include $data['_config']['base_dir'] .'/view/header.php'; ?>
<div class="uk-flex uk-margin-left">
<?php include $data['_config']['base_dir'] .'/view/menu.php'; ?>
<div class="uk-flex-item-auto">
    <div class="uk-container uk-container-center">

<?php if ( !empty($data['deleted']) ) { ?>
<div class="uk-alert uk-alert-success">Location Deleted</div>
<META HTTP-EQUIV="Refresh" CONTENT="0;URL=<?= $data['_config']['base_url'] ?>manage/locations.php">
<?php } else { ?>

        <form method="post" action="<?= $data['_config']['base_url'] ?>manage/delete_location.php" class="uk-form">
            <input type="hidden" name="locationid" id="locationid" value="<?= $data['location']['locationid'] ?>">
            <div class="uk-panel uk-panel-box">
                <h2 class="uk-panel-title uk-text-center">Are you sure you want to delete <?= $data['location']['name'] ?> ( <?= $data['location']['locationid'] ?> )?</h2>

                <div class="uk-form-row">
                    <input class="uk-button" type="submit" name="op" id="op" value="Delete">
                    <a class="uk-button" href="locations.php">Back</a>
                </div>
            </div>
        </form>
<?php } ?>
    </div>
</div>
</div>
<?php
include $data['_config']['base_dir'] .'/view/footer.php';
?>

</body>
</html>
