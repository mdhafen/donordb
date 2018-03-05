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
<h2><?= empty($data['contact']['contactid']) ? "New" : "Edit" ?> Contact</h2>

<?php if ( $data['op'] == 'SaveSuccess' ) { ?>
<div class="uk-alert uk-alert-success">
     Save Successful!
</div>
<?php } ?>

<form class="uk-form" method="post" action="editcontact.php">
    <input type="hidden" name="contactid" value="<?= $data['contactid'] ?>">
    <fieldset class="uk-form-horizontal">
        <div class="uk-form-row">
            <span class="uk-form-label">Id</span>
            <div class="uk-form-controls uk-form-controls-text"><?= $data['contact']['contactid'] ?></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="name">Name</label>
            <div class="uk-form-controls"><input type="text" class="uk-form-width-large" id="name" name="name" value="<?= (!empty($data['contact']['name'])) ? $data['contact']['name'] : "" ?>"></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="company">Company</label>
            <div class="uk-form-controls"><input type="text" class="uk-form-width-large" id="company" name="company" value="<?= (!empty($data['contact']['company'])) ? $data['contact']['company'] : "" ?>"></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="street">Street</label>
            <div class="uk-form-controls"><input type="text" class="uk-form-width-medium" id="street" name="street" value="<?= (!empty($data['contact']['street'])) ? $data['contact']['street'] : "" ?>"></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="city">City</label>
            <div class="uk-form-controls"><input type="text" id="city" name="city" value="<?= (!empty($data['contact']['city'])) ? $data['contact']['city'] : "" ?>"></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="state">State</label>
            <div class="uk-form-controls"><input type="text" id="state" name="state" value="<?= (!empty($data['contact']['state'])) ? $data['contact']['state'] : "" ?>"></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="zip">Zip</label>
            <div class="uk-form-controls"><input type="text" id="zip" name="zip" value="<?= (!empty($data['contact']['zip'])) ? $data['contact']['zip'] : "" ?>"></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="phone">Phone</label>
            <div class="uk-form-controls"><input type="text" id="phone" name="phone" value="<?= (!empty($data['contact']['phone'])) ? $data['contact']['phone'] : "" ?>"></div>
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
