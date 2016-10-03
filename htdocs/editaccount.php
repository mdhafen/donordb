<!DOCTYPE html>
<html>
<head>
<?php include 'head.php';?>
</head>
<body>
<?php include 'header.php'; ?>
<div class="uk-flex uk-margin-left uk-margin-right">
<?php include 'menu.php'; ?>
<div class="uk-container uk-margin uk-width-1-1">
<h2><?= empty($data['account']['accountid']) ? "New" : "Edit" ?> Account</h2>

<?php if ( $data['op'] == 'SaveSuccess' ) { ?>
<div class="uk-alert uk-alert-success">
     Save Successful!
</div>
<?php } ?>

<form class="uk-form" method="post" action="editaccount.php">
    <input type="hidden" name="accountid" value="<?= $data['accountid'] ?>">
    <fieldset class="uk-form-horizontal">
        <div class="uk-form-row">
            <label class="uk-form-label" for="name">Name</label>
            <div class="uk-form-controls"><input type="text" id="name" name="name" value="<?= (!empty($data['account']['name'])) ? $data['account']['name'] : "" ?>"></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="locationid">Location</label>
            <div class="uk-form-controls">
                <select id="locationid" name="locationid">
                    <option value="">Select Location</option>
<?php foreach ( $data['locations'] as $loc ) { ?>
                    <option value="<?= $loc['locationid'] ?>"<?= !empty($loc['selected']) ? " selected" : "" ?>><?= $loc['name'] ?></option>
<?php } ?>
                </select>
            </div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="note">Note</label>
            <div class="uk-form-controls"><textarea id="note" name="note"><?= (!empty($data['account']['note'])) ? $data['account']['note'] : "" ?></textarea></div>
        </div>

        <div class="uk-form-row">
            <div class="uk-form-controls">
                 <input class="uk-button" type="submit" name="op" value="Save">
            </div>
        </div>

    </fieldset>
</form>
</div>

</div>
</div>
<?php
include 'footer.php';
?>

</body>
</html>
