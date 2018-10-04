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

<div class="uk-grid">
    <div class="<?= empty($data['account']['accountid']) ? 'uk-width-1-1' : 'uk-width-1-2' ?>">
<form class="uk-form" method="post" action="editaccount.php">
    <input type="hidden" name="accountid" value="<?= $data['accountid'] ?>">
    <fieldset class="uk-form-horizontal">
        <div class="uk-form-row">
            <span class="uk-form-label">Id</span>
            <div class="uk-form-controls uk-form-controls-text"><?= $data['account']['accountid'] ?></div>
        </div>

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
            <label class="uk-form-label" for="retired">Retired</label>
            <div class="uk-form-controls"><input type="checkbox" id="retired" name="retired"<?= $data['account']['retired'] ? " checked" : "" ?>></div>
        </div>

        <div class="uk-form-row">
            <div class="uk-form-controls">
                 <input class="uk-button" type="submit" name="op" value="Save">
            </div>
        </div>

    </fieldset>
</form>

<form class="uk-form uk-margin-top" method="post" action="editaccount.php">
    <input type="hidden" name="accountid" value="<?= $data['accountid'] ?>">
    <fieldset class="uk-form-horizontal">
        <div class="uk-form-row uk-panel uk-panel-box uk-text-center">
<?php if ( !empty($data['num_actions']) ) { ?>
            <div class="uk-form-controls-text">Account is used by <?= $data['num_actions'] ?> actions.  Can not be deleted.</div>
<?php } else { ?>
            <input class="uk-button" type="submit" name="op" value="Delete">
<?php } ?>
        </div>
    </fieldset>
</form>

    </div>
<?php if ( !empty($data['account']['accountid']) ) { ?>
    <div class="uk-width-1-2">
         <div class="uk-panel uk-panel-box uk-text-center">
             <a href="<?= $data['_config']['base_url'] ?>actions.php?op=search&amp;account=<?= $data['account']['accountid'] ?>" class="uk-button">View transactions</a>
         </div>
         <div class="uk-panel uk-panel-box uk-text-center">
             <a href="<?= $data['_config']['base_url'] ?>account_action.php?op=transfer&amp;accountid=<?= $data['account']['accountid'] ?>" class="uk-button">Transfer to another account</a>
         </div>
         <div class="uk-panel uk-panel-box uk-text-center">
             <a href="<?= $data['_config']['base_url'] ?>account_action.php?op=move&amp;accountid=<?= $data['account']['accountid'] ?>" class="uk-button">Move to another location</a>
         </div>
    </div>
<?php } ?>
</div>
</div>

</div>
</div>
<?php
include 'footer.php';
?>

</body>
</html>
