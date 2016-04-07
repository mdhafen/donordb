<ul class="uk-nav uk-nav-side uk-nav-parent-icon uk-panel uk-panel-box" data-uk-nav>
<li><a href="index.php">Home</a></li>
<?php if ( !empty($data['_session']['CAN_actions']) ) { ?>
<li><a href="actions.php">Actions</a></li>
<?php } ?>

<?php if ( !empty($data['_session']['CAN_accounts']) ) { ?>
<li><a href="accounts.php">Accounts</a></li>
<?php } ?>

<?php if ( !empty($data['_session']['CAN_contacts']) ) { ?>
<li><a href="contacts.php">Contacts</a></li>
<?php } ?>

<?php if ( !empty($data['_session']['CAN_reports']) ) { ?>
<li><a href="reports.php">Reports</a></li>
<?php } ?>

<?php if ( !empty($data['_session']['CAN_manage_site']) ) { ?>
<li class="uk-parent"><a href="#">Site Management</a>
  <ul class="uk-nav-sub">
  <li><a href="manage/users.php">Manage Users</a></li>
  </ul>
</li>
<?php } ?>

<li><a href="index.php?_logout=1">Logout</a></li>
</ul>
