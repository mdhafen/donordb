<!DOCTYPE html>
<html>
<head>
<?php include 'head.php';?>
<script src="<?= $data['_config']['base_url'] ?>list.min.js"></script>
<script src="<?= $data['_config']['base_url'] ?>list.pagination.min.js"></script>
<style>
.paginationTop li, .paginationBottom li {
  display: inline-block;
  list-style: none;
  padding-right: 10px;
}

.paginationTop li.active a, .paginationBottom li.active a {
  color: #000;
}

.paginationTop li.disabled a, .paginationBottom li.disabled a,
.paginationTop li.disabled a:hover, .paginationBottom li.disabled a:hover {
  color: #000;
  cursor: default;
  text-decoration: none;
}

.list_sort:after {
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-bottom: 5px solid transparent;
  content:"";
  position: relative;
  top:-10px;
  right:-5px;
}

.list_sort.asc:after {
  width: 0;
  height: 0;
  content:"";
  position: relative;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-bottom: 5px solid #000;
  top:-15px;
  right:-5px;
}

.list_sort.desc:after {
  width: 0;
  height: 0;
  content:"";
  position: relative;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 5px solid #000;
  top:13px;
  right:-5px;
}
</style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="uk-flex uk-margin-left uk-margin-right">
<?php include 'menu.php'; ?>
<div class="uk-margin uk-container uk-width-1-1">
<h2>All Actions</h2>
<div id="actions_table_container">
<span class="uk-padding-right"><a href="<?= $data['_config']['base_url'] ?>actions.php">Recent Actions</a></span> <ul class="paginationTop uk-display-inline-block"></ul>
<table class="uk-table" id="actions_table">
<thead>
<tr>
  <th>
    <span class="list_sort" data-sort="list_date">Date</span><br>
    <input type="text" size="10" onkeyup="list_obj.search(this.value,['list_date'])">
  </th>
  <th>Amount</th>
  <th>
    <span class="list_sort" data-sort="list_contact_name">Contact Name</span><br>
    <input type="text" size="10" onkeyup="list_obj.search(this.value,['list_contact_name'])">
  </th>
  <th>
    <span class="list_sort" data-sort="list_contact_company">Contact Company</span><br>
    <input type="text" size="10" onkeyup="list_obj.search(this.value,['list_contact_company'])">
  </th>
  <th>
    <span class="list_sort" data-sort="list_account">Account Name</span><br>
    <input type="text" size="10" onkeyup="list_obj.search(this.value,['list_account'])">
  </th>
  <th>
    <span class="list_sort" data-sort="list_location">Site</span><br>
    <input type="text" size="10" onkeyup="list_obj.search(this.value,['list_location'])">
  </th>
  <th>
    Receipt<br>
    <input type="text" size="10" onkeyup="list_obj.search(this.value,['list_receipt'])">
  </th>
  <th>
    P.O.<br>
    <input type="text" size="10" onkeyup="list_obj.search(this.value,['list_po'])">
  </th>
  <th>
    Notes<br>
    <input type="text" size="10" onkeyup="list_obj.search(this.value,['list_note'])">
  </th>
</tr>
</thead>
<tbody class="list">
<?php
   if ( !empty($data['actions_list']) ) {
     foreach ( $data['actions_list'] as $row ) {
?>
<tr id="actions_<?= $row['actionid'] ?>">
<td class="list_date" data-list-isodate="<?= $row['date'] ?>"><a href="editaction.php?actionid=<?= $row['actionid'] ?>" class="uk-button"><span class="uk-icon-pencil"></span></a> <?= date('m/d/Y',strtotime($row['date'])) ?></td>
<td class="list_amount"><span class="<?= $row['amount'] < 0 ? 'uk-text-danger' : '' ?>"><?= number_format($row['amount'],2) ?></span></td>
<td class="list_contact_name"><?= $row['contact_name'] ?></td>
<td class="list_contact_company"><?= $row['company'] ?></td>
<td class="list_account"><?= $row['account_name'] ?></td>
<td class="list_location"><?= $row['location_name'] ?></td>
<td class="list_receipt"><?= $row['receipt'] ?></td>
<td class="list_po"><?= $row['po'] ?></td>
<td class="list_note"><?= $row['note'] ?></td>
</tr>
<?php
     }
   }

?>
</tbody>
</table>
<ul class="paginationBottom"></ul>
</div>
<script>
var list_options = {
  valueNames: [
    { name: 'list_date', attr: 'data-list-isodate' },
    'list_amount','list_contact_name','list_contact_company','list_account',
    'list_location','list_receipt','list_po','list_note'
  ],
  searchClass: 'list_search',
  sortClass: 'list_sort',
  //indexAsync: true,
  page: 10,
  plugins: [
    ListPagination({
      name: "paginationTop",
      paginationClass: "paginationTop",
      innerWindow: 2,
      outerWindow: 1
    }),
    ListPagination({
      name: "paginationBottom",
      paginationClass: "paginationBottom",
      innerWindow: 2,
      outerWindow: 1
    })
  ]
};

var list_obj = new List('actions_table_container', list_options);
</script>

</div>
</div>
<?php
include 'footer.php';
?>

</body>
</html>
