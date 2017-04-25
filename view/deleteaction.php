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
<h2>Delete Action</h2>

<?php if ( !empty($data['errors']) ) { ?>
<div class="uk-alert uk-alert-warning">
     There was an error!
</div>
<?php } ?>

<form class="uk-form" method="post" action="deleteaction.php">
    <h2>Are you sure you want to delete this action?</h2>
    <input type="hidden" name="actionid" value="<?= $data['actionid'] ?>">
    <input type="hidden" name="confirm" value="Confirmed">
    <fieldset class="uk-form-horizontal">
        <div class="uk-form-row">
            <label class="uk-form-label" for="date">Date</label>
            <div class="uk-form-controls uk-form-controls-text"><span id="date" name="date"><?= $data['action']['date'] ?></span></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="amount">Amount</label>
            <div class="uk-form-controls uk-form-controls-text"><span id="amount" name="amount">$<?= number_format($data['action']['amount'],2) ?></span></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="contactid">Contact</label>
            <div class="uk-form-controls uk-form-controls-text"><span id="contactid" name="contactid"><?= $data['action']['contact_name'] ?></span></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="locationid">Location</label>
            <div class="uk-form-controls uk-form-controls-text"><span id="locationid" name="locationid"><?= $data['action']['location_name'] ?></span></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="accountid">Account</label>
            <div class="uk-form-controls uk-form-controls-text"><span id="accountid" name="accountid"><?= $data['action']['account_name'] ?></span></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="receipt">Receipt</label>
            <div class="uk-form-controls uk-form-controls-text"><span id="receipt" name="receipt"><?= (!empty($data['action']['receipt'])) ? $data['action']['receipt'] : "" ?></span></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="po">Po</label>
            <div class="uk-form-controls uk-form-controls-text"><span id="po" name="po"><?= (!empty($data['action']['po'])) ? $data['action']['po'] : "" ?></span></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="note">Note</label>
            <div class="uk-form-controls uk-form-controls-text"><span id="note" name="note"><?= (!empty($data['action']['note'])) ? $data['action']['note'] : "" ?></span></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="in_kind">In Kind</label>
            <div class="uk-form-controls uk-form-controls-text"><span id="in_kind" name="in_kind"><?= (!empty($data['action']['in_kind'])) ? "Yes" : "No" ?></span></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="is_transfer">Is A Transfer</label>
            <div class="uk-form-controls uk-form-controls-text"><span id="is_transfer" name="is_transfer"><?= (!empty($data['action']['is_transfer'])) ? "Yes" : "No" ?></span></div>
        </div>

        <div class="uk-form-row">
            <div class="uk-form-controls">
                 <input class="uk-button" type="submit" name="op" value="Delete">
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
