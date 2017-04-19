<!DOCTYPE html>
<html>
<head>
 <?php $page_title=( $data['edit'] ? "Edit" : "New" ) .' User'; include $data['_config']['base_dir'] .'/view/head.php';?>
</head>
<body>
<?php include $data['_config']['base_dir'] .'/view/header.php'; ?>
<div class="uk-flex uk-margin-left">
<?php include $data['_config']['base_dir'] .'/view/menu.php'; ?>
<div class="uk-flex-item-auto">
    <div class="uk-container uk-container-center">
        <form method="post" action="<?= $data['_config']['base_url'] ?>manage/edit_user.php" class="uk-form uk-form-horizontal">
            <input type="hidden" name="userid" id="userid" value="<?= empty($data['user']['userid']) ? '' : $data['user']['userid'] ?>">

            <div class="uk-panel uk-panel-box">
                <h2 class="uk-panel-title uk-text-center"><?= ($data['edit'])?"Edit":"New" ?> User</h2>
                <?php if ( $data['saved'] ) { ?>
                <div class="uk-alert uk-alert-success">
                    Changes saved.
                </div>
                <?php } ?>

                <div class="uk-form-row">
                    <label for="username" class="uk-form-label">Username</label>
                    <div class="uk-form-controls"><input type="text" name="username" id="username" value="<?= empty($data['user']['username']) ? '' : $data['user']['username'] ?>" ></div>
                </div>

                <div class="uk-form-row">
                    <label for="fullname" class="uk-form-label">Full Name</label>
                    <div class="uk-form-controls"><input type="text" name="fullname" id="fullname" value="<?= empty($data['user']['fullname']) ? '' : $data['user']['fullname'] ?>" ></div>
                </div>

                <div class="uk-form-row">
                    <label for="email" class="uk-form-label">Email Address</label>
                    <div class="uk-form-controls"><input name="email" id="email" value="<?= empty($data['user']['email']) ? '' : $data['user']['email'] ?>" ></div>
                </div>

                <div class="uk-form-row">
                    <label for="role" class="uk-form-label">User Role</label>
                    <div class="uk-form-controls">
                        <select name="role" id="role">
<?php foreach ( (array) $data['roles'] as $roleid => $role ) { ?>
                        <option value="<?= $roleid ?>" <?= (!empty($role['selected'])) ? "selected='selected'" : "" ?>><?= $role['name'] ?></option>
<?php } ?>
                        </select>
                    </div>
                </div>

                <div class="uk-form-row">
                    <label for="locations" class="uk-form-label">User Location</label>
                    <div class="uk-form-controls">
                        <select name="locations[]" id="locations" multiple>
<?php foreach ( $data['locations'] as $loc ) { ?>
                        <option value="<?= $loc['locationid'] ?>" <?= (!empty($loc['selected'])) ? "selected='selected'" : "" ?>><?= $loc['name'] ?></option>
<?php } ?>
                        </select>
                    </div>
                </div>

                <div class="uk-form-row">
                    <label for="password" class="uk-form-label">Password</label>
                    <div class="uk-form-controls"><input type="password" name="password" id="password" value="<?= !empty($data['user']['password']) ? '*****' : '' ?>" ></div>
                </div>

                <div class="uk-form-row">
                    <label for="password_2" class="uk-form-label">Repeat Password to confirm</label>
                    <div class="uk-form-controls"><input type="password" name="password_2" id="password_2" value="" ></div>
                </div>

                <div class="uk-form-row">
                    <input class="uk-button" type="submit" name="op" id="op" value="Save">
                    <a class="uk-button" href="users.php">Back</a>
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
