<div>
<ul class="uk-nav uk-nav-side uk-nav-parent-icon uk-panel uk-panel-box" data-uk-nav>
<li><a href="<?= $data['_config']['base_url'] ?>index.php">Home</a></li>
<?php if ( !empty($data['_session']['CAN_actions']) ) { ?>
<li class="uk-parent"><a href="#">Actions</a>
  <ul class="uk-nav-sub">
    <li><a href="<?= $data['_config']['base_url'] ?>editaction.php">Add an Action</a></li>
    <li><a href="<?= $data['_config']['base_url'] ?>actions.php">Search Actions</a></li>
  </ul>
</li>
<?php } ?>

<?php if ( !empty($data['_session']['CAN_accounts']) ) { ?>
<li class="uk-parent"><a href="#">Accounts</a>
  <ul class="uk-nav-sub">
    <li><a href="<?= $data['_config']['base_url'] ?>editaccount.php">Add an Account</a></li>
    <li><a href="<?= $data['_config']['base_url'] ?>accounts.php">List Accounts</a></li>
  </ul>
</li>
<?php } ?>

<?php if ( !empty($data['_session']['CAN_contacts']) ) { ?>
<li class="uk-parent"><a href="#">Contacts</a>
  <ul class="uk-nav-sub">
    <li><a href="<?= $data['_config']['base_url'] ?>editcontact.php">Add a Contact</a></li>
    <li><a href="<?= $data['_config']['base_url'] ?>contacts.php">List Contacts</a></li>
  </ul>
</li>
<?php } ?>

<?php if ( !empty($data['_session']['CAN_reports']) ) { ?>
<li><a href="<?= $data['_config']['base_url'] ?>reports/index.php">Reports</a></li>
<?php } ?>

<?php if ( !empty($data['_session']['CAN_manage_site']) ) { ?>
<li class="uk-parent"><a href="#">Site Management</a>
  <ul class="uk-nav-sub">
  <li><a href="<?= $data['_config']['base_url'] ?>manage/users.php">Manage Users</a></li>
  <li><a href="<?= $data['_config']['base_url'] ?>manage/locations.php">Manage Locations</a></li>
  </ul>
</li>
<?php } ?>

<li><a href="<?= $data['_config']['base_url'] ?>index.php?_logout=1">Logout</a></li>
</ul>
</div>