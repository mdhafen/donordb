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
<div class="uk-container uk-margin uk-width-1-1">
<h2>Accounts</h2>
<div id="accounts_table_container">
<ul class="paginationTop"></ul>
<table class="uk-table" id="accounts_table">
<thead>
<tr>
  <th>
    <span class="list_sort" data-sort="list_acc_id">Account Number</span><br>
    <input type="text" id="account_accountid_filter" size="5" onkeyup="list_obj.search(this.value,['list_acc_id'])">
  </th>
  <th>
    <span class="list_sort" data-sort="list_account">Account Name</span><br>
    <input type="text" id="account_account_filter" size="10" onkeyup="do_filter(this.value,'account')">
  </th>
  <th>
    <span class="list_sort" data-sort="list_location">Site</span><br>
    <input type="text" id="account_location_filter" size="10" onkeyup="do_filter(this.value,'location')">
  </th>
  <th>
    Total
  </th>
  <th>
    Notes<br>
    <input type="text" id="account_note_filter" size="10" onkeyup="do_filter(this.value,'note')">
  </th>
  <th></th>
</tr>
</thead>
<tbody class="list">
<?php
   if ( !empty($data['accounts_list']) ) {
     foreach ( $data['accounts_list'] as $row ) {
?>
<tr id="accounts_<?= $row['accountid'] ?>">
<td class="list_acc_id"><a href="editaccount.php?accountid=<?= $row['accountid'] ?>" class="uk-button"><span class="uk-icon-pencil"></span></a> <?= $row['accountid'] ?></td>
<td class="list_account"><?= $row['name'] ?></td>
<td class="list_location"><?= $row['location_name'] ?></td>
<td class="list_total"><span class="<?= $row['total'] < 0 ? 'uk-text-danger' : '' ?>"><?= number_format(floatval($row['total']),2) ?></span></td>
<td class="list_note"><?= $row['note'] ?></td>
<td>
  <a href="<?= $data['_config']['base_url'] ?>actions.php?op=search&amp;account=<?= $row['accountid'] ?>" class="uk-button uk-text-nowrap" title="Transactions"><i class="uk-icon-file-text-o"></i></a>
  <a href="<?= $data['_config']['base_url'] ?>account_action.php?op=transfer&amp;accountid=<?= $row['accountid'] ?>" class="uk-button uk-text-nowrap" title="Transfer"><i class="uk-icon-file-text-o"></i><i class="uk-icon-arrow-right"></i><i class="uk-icon-file-text-o"></i></a>
  <a href="<?= $data['_config']['base_url'] ?>account_action.php?op=move&amp;accountid=<?= $row['accountid'] ?>" class="uk-button uk-text-nowrap" title="Move"><i class="uk-icon-file-text-o"></i><i class="uk-icon-arrow-right"></i></a>
</td>
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
function do_filter() {
    var input = [
        document.getElementById('account_account_filter').value.toLowerCase(),
        document.getElementById('account_location_filter').value.toLowerCase(),
        document.getElementById('account_note_filter').value.toLowerCase()
    ];
    list_obj.filter(function(item){
        var match = [
            decodeURIComponent((item.values().list_account+'').replace(/%D?/g,'%25')).replace(/\+/g,' ').toLowerCase(),
            item.values().list_location.toLowerCase(),
            item.values().list_note.toLowerCase()
        ];
        return ( match[0].indexOf(input[0]) > -1 && match[1].indexOf(input[1]) > -1 && match[2].indexOf(input[2]) > -1 );
    })
}

var list_options = {
  valueNames: [
    'list_acc_id','list_account','list_location','list_total','list_note'
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

var list_obj = new List('accounts_table_container', list_options);
</script>

</div>
</div>
<?php
include 'footer.php';
?>

</body>
</html>
