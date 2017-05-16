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
<h2>Contacts</h2>
<div id="contacts_table_container">
<ul class="paginationTop"></ul>
<table class="uk-table" id="contacts_table">
<thead>
<tr>
  <th>
    <span class="list_sort" data-sort="list_con_id">ID</span><br>
    <input type="text" id="contacts_filter_id" size="10" onkeyup="do_filter()">
  </th>
  <th>
    <span class="list_sort" data-sort="list_name">Name</span><br>
    <input type="text" id="contacts_filter_name" size="10" onkeyup="do_filter()">
  </th>
  <th>
    <span class="list_sort" data-sort="list_company">Company</span><br>
    <input type="text" id="contacts_filter_company" size="10" onkeyup="do_filter()">
  </th>
  <th>
    <span class="list_sort" data-sort="list_street">Street</span><br>
    <input type="text" id="contacts_filter_street" size="10" onkeyup="do_filter()">
  </th>
  <th>
    <span class="list_sort" data-sort="list_city">City</span><br>
    <input type="text" id="contacts_filter_city" size="10" onkeyup="do_filter()">
  </th>
  <th>
    <span class="list_sort" data-sort="list_state">State</span><br>
    <input type="text" id="contacts_filter_state" size="10" onkeyup="do_filter()">
  </th>
  <th>
    <span class="list_sort" data-sort="list_zip">Zip</span><br>
    <input type="text" id="contacts_filter_zip" size="10" onkeyup="do_filter()">
  </th>
  <th>
    <span class="list_sort" data-sort="list_phone">Phone</span><br>
    <input type="text" id="contacts_filter_phone" size="10" onkeyup="do_filter()">
  </th>
</tr>
</thead>
<tbody class="list">
<?php
   if ( !empty($data['contacts_list']) ) {
     foreach ( $data['contacts_list'] as $row ) {
?>
<tr id="contacts_<?= $row['contactid'] ?>">
<td class="list_con_id"><a href="editcontact.php?contactid=<?= $row['contactid'] ?>" class="uk-button"><span class="uk-icon-pencil"></span></a> <?= $row['contactid'] ?></td>
<td class="list_name" data-list-clean-name="<?= $row['name'] ?>"><?= $row['name'] ?></td>
<td class="list_company"><?= $row['company'] ?></td>
<td class="list_street"><?= $row['street'] ?></td>
<td class="list_city"><?= $row['city'] ?></td>
<td class="list_state"><?= $row['state'] ?></td>
<td class="list_zip"><?= $row['zip'] ?></td>
<td class="list_phone"><?= $row['phone'] ?></td>
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
        document.getElementById('contacts_filter_id').value.toLowerCase(),
        document.getElementById('contacts_filter_name').value.toLowerCase(),
        document.getElementById('contacts_filter_company').value.toLowerCase(),
        document.getElementById('contacts_filter_street').value.toLowerCase(),
        document.getElementById('contacts_filter_city').value.toLowerCase(),
        document.getElementById('contacts_filter_state').value.toLowerCase(),
        document.getElementById('contacts_filter_zip').value.toLowerCase(),
        document.getElementById('contacts_filter_phone').value.toLowerCase()
    ];
    list_obj.filter(function(item){
        var match = [
            item.values().list_con_id.toLowerCase(),
            decodeURIComponent((item.values().list_name+'').replace(/%D?/g,'%25')).replace(/\+/g,' ').toLowerCase(),
            decodeURIComponent((item.values().list_company+'').replace(/%D?/g,'%25')).replace(/\+/g,' ').toLowerCase(),
            item.values().list_street.toLowerCase(),
            item.values().list_city.toLowerCase(),
            item.values().list_state.toLowerCase(),
            item.values().list_zip.toLowerCase(),
            item.values().list_phone.toLowerCase()
        ];
        return ( match[0].indexOf(input[0]) > -1 && match[1].indexOf(input[1]) > -1 && match[2].indexOf(input[2]) > -1 && match[3].indexOf(input[3]) > -1 && match[4].indexOf(input[4]) > -1 && match[5].indexOf(input[5]) > -1 && match[6].indexOf(input[6]) > -1 && match[7].indexOf(input[7]) > -1 );
    })
}

var list_options = {
  valueNames: [ 'list_con_id',
    { name: 'list_name', attr: 'data-list-clean-name' },
    'list_company','list_street','list_city','list_state','list_zip','list_phone'
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

var list_obj = new List('contacts_table_container', list_options);
</script>

</div>
</div>
<?php
include 'footer.php';
?>

</body>
</html>
