<!DOCTYPE html>
<html>
<head>
 <?php $page_title=( $data['edit'] ? "Edit" : "New" ) .' Location'; include $data['_config']['base_dir'] .'/view/head.php';?>
</head>
<body>
<?php include $data['_config']['base_dir'] .'/view/header.php'; ?>
<div class="uk-flex uk-margin-left">
<?php include $data['_config']['base_dir'] .'/view/menu.php'; ?>
<div class="uk-flex-item-auto">
    <div class="uk-container uk-container-center">
        <form method="post" action="<?= $data['_config']['base_url'] ?>manage/edit_location.php" class="uk-form uk-form-horizontal">
            <input type="hidden" name="locationid" id="locationid" value="<?= empty($data['location']['locationid']) ? '' : $data['location']['locationid'] ?>">
            <div class="uk-panel uk-panel-box">
                <h2 class="uk-panel-title uk-text-center"><?= ($data['edit'])?"Edit":"New" ?> Location</h2>

                <?php if ( $data['error'] ) { ?>
                <div class="uk-alert uk-alert-warning">
                    There was an error
                    <?php
                    foreach ( (array) $data['error'] as $err ) {
                        print "<div>$err</div>\n";
                    }
                    ?>
                </div>
                <?php } ?>

                <?php if ( $data['saved'] ) { ?>
                <div class="uk-alert uk-alert-success">
                    Changes saved.
                </div>
                <?php } ?>

                <div class="uk-form-row">
                    <label for="new_locationid" class="uk-form-label">Location Number</label>
                    <div class="uk-form-controls"><input type="text" name="new_locationid" id="locationid" value="<?= empty($data['location']['locationid']) ? '' : $data['location']['locationid'] ?>" ></div>
                </div>

                <div class="uk-form-row">
                    <label for="name" class="uk-form-label">Name</label>
                    <div class="uk-form-controls"><input type="text" name="name" id="name" value="<?= empty($data['location']['name']) ? '' : $data['location']['name'] ?>" ></div>
                </div>

                <div class="uk-form-row">
                    <input class="uk-button" type="submit" name="op" id="op" value="Save">
                    <a class="uk-button" href="locations.php">Back</a>
                </div>

            </div>
        </form>
    </div>
</div>
</div>
<?php
include $data['_config']['base_dir'] .'/view/footer.php';
?>

</body>
</html>
