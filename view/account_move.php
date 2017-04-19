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
<h2>Move Account</h2>

<form class="uk-form" method="post" action="account_action.php">
    <input type="hidden" name="accountid" value="<?= $data['accountid'] ?>">
    <input type="hidden" name="op" value="move">
    <input type="hidden" name="action" value="do_it">
    <input type="hidden" name="to_accountid" value="<?= $data['accountid'] ?>">
    <input type="hidden" name="amount" value="<?= !empty($data['balance']) ? $data['balance'] : 0 ?>">

    <fieldset class="uk-form-horizontal">
        <div class="uk-form-row">
            <label class="uk-form-label" for="amount">Balance</label>
            <div class="uk-form-controls uk-form-controls-text"><?= !empty($data['balance']) ? $data['balance'] : 0 ?></div>
        </div>

        <div class="uk-form-row">
            <label class="uk-form-label" for="accountid">Contact</label>
            <div class="uk-form-controls">
                <input type="number" id="con_id" name="con_id" value="" onkeyup="select_contact(this.value)">
                <select id="contactid" name="contactid">
                    <option value="">Select Contact</option>
<?php foreach ( $data['contacts'] as $con ) { ?>
                    <option value="<?= $con['contactid'] ?>"><?= $con['name'] ?></option>
<?php } ?>
                </select>
            </div>
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
                 <input class="uk-button" type="submit" value="Move">
            </div>
        </div>

    </fieldset>
</form>
</div>

<script>
function select_contact(contactid) {
    var el_contact = document.getElementById('contactid');

    for ( var i = 0; i < el_contact.options.length; i++ ) {
        if ( el_contact.options[i].value == contactid ) {
            el_contact.value = el_contact.options[i].value;
            break;
        }
    }
}
</script>

</div>
</div>
<?php
include 'footer.php';
?>

</body>
</html>
