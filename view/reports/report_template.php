<!DOCTYPE html>
<html>
<head>
<?php include $data['_config']['base_dir'].'/view/head.php';?>
<style>
<?php if ( $data['paged'] !== false ) { ?>
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
<?php } ?>

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

tbody tr {
    border: solid 1px gray;
}

tbody.report_multi_body::before {
    content: "";
    display: table-row;
    height: 30px;
}
</style>
<script src="<?= $data['_config']['base_url'] ?>list.min.js"></script>
<script src="<?= $data['_config']['base_url'] ?>list.pagination.min.js"></script>
</head>
<body>
<?php include $data['_config']['base_dir'].'/view/header.php'; ?>
<div class="uk-flex uk-margin-left uk-margin-right">
<?php include $data['_config']['base_dir'].'/view/menu.php'; ?>
<div class="uk-margin uk-margin-left uk-container uk-width-1-1">
  <h2><?= $data['report_title'] ?></h2>

<?php if ( !empty($data['op']) ) { ?>
    <div id="reports_table_container">
      <ul class="paginationTop"></ul>
      <table class="uk-table" id="report_table">
<?php
   if ( !empty($data['report_header']) ) {
     print "<thead>\n<tr>\n";
     foreach ( $data['report_header'] as $row ) {
?>
        <th<?= !empty($row['width']) ? " colspan='${row['width']}'" : "" ?>><?= !empty($row['sort']) ? "<span class='list_sort' data-sort='list_${row['column_name']}'>" : "" ?><?= $row['column_title'] ?><?= !empty($row['sort']) ? "</span>" : "" ?></th>
<?php
     }
     print "</tr>\n</thead>\n";
   }
?>
      <tbody class="list">
<?php
   if ( !empty($data['report_body']) ) {
     $count = 1;
     foreach ( $data['report_body'] as $row ) {
       if ( !empty($row[0]['new_group']) && $count != 1 ) {
           print "</tbody>\n<tbody class='list report_multi_body'>\n";
       }
       $count++;
?>
        <tr>
<?php
       foreach ( $row as $column ) {
?>
          <td<?= !empty($column['width']) ? " colspan='${column['width']}'" : "" ?><?= !empty($column['clean_value']) ? " data-list-${column['column_name']}='${column['clean_value']}'" : "" ?><?= !empty($column['column_name']) ? " class='list_${column['column_name']}'" : "" ?>><?= !empty($column['link']) ? "<a href='${column['link']}'>" : "" ?><?= $column['value'] ?><?= !empty($column['link']) ? "</a>" : "" ?></td>
<?php
       }
?>
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
  valueNames: [ <?php 
   if ( !empty($data['report_header']) ) {
     $columns = array();
     foreach ( $data['report_header'] as $row ) {
         $columns[] = !empty($row['clean_attr']) ? "{ name: 'list_${row['column_name']}', attr: 'data-list-${row['column_name']}' }" : "'list_${row['column_name']}'";
     }
     print implode( ',', $columns );
   }
?>
  ],
  searchClass: 'list_search',
  sortClass: 'list_sort',
  //indexAsync: true,
<?php if ( $data['paged'] !== false ) { ?>
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
<?php } ?>
};

var list_obj = new List('reports_table_container', list_options);
</script>
<?php
} else {
    if ( !empty($data['params']) ) {
      $count = 1;
?>
  <form class="uk-form" method="post">
    <input type="hidden" name="op" value="do_it">
    <fieldset class="uk-form-horizontal">
<?php foreach ( $data['params'] as $row ) { ?>
      <div class="uk-form-row">
<?php
      if ( $row['type'] != 'check' ) {
?>
        <label class="uk-form-label" for="<?= $row['name'] ?>"><?= $row['label'] ?></label>
<?php
      }
?>
        <div class="uk-form-controls">
<?php
      switch( $row['type'] ) {
        case 'input' :
?>
          <input type="text" id="<?= !empty($row['id']) ? $row['id'] : 'report_input_'.$count++ ?>" name="<?= $row['name'] ?>" value="<?= !empty($row['value']) ? $row['value'] : "" ?>"<?= !empty($row['pattern']) ? " pattern='${row['pattern']}'" : "" ?><?= !empty($row['onchange']) ? " ${row['onchange']}" : "" ?>>
<?php
          break;
        case 'select' :
          if ( !empty($row['filter_label']) || !empty($row['filter_value_label']) ) {
?>
          <input type="text" id="filter_<?= !empty($row['id']) ? $row['id'] : 'report_input_'.$count++ ?>" name="filter_<?= !empty($row['id']) ? $row['id'] : 'report_input_'.$count++ ?>" onkeyup="filter_select(this.value,'<?= !empty($row['id']) ? $row['id'] : 'report_input_'.$count++ ?>,<?= $row['filter_label'] ? 'label' : 'value_label' ?>')">
<?php
          }
?>
          <select id="<?= !empty($row['id']) ? $row['id'] : 'report_input_'.$count++ ?>" name="<?= $row['name'] ?>"<?= !empty($row['size']) ? " size='${row['size']}'" : "" ?><?= !empty($row['multiple']) ? " multiple" : "" ?><?= !empty($row['onchange']) ? " ${row['onchange']}" : "" ?>>
<?= !empty($row['first_blank']) ? "<option value=''></option>\n" : "" ?>
<?php foreach ( $row['option_loop'] as $option ) { ?>
            <option value="<?= $option['value'] ?>"<?= !empty($option['selected']) ? " selected" : "" ?>><?= $option['label'] ?></option>
<?php } ?>
          </select>
<?php
          break;
        case 'check' :
?>
          <input type="checkbox" id="<?= !empty($row['id']) ? $row['id'] : 'report_input_'.$count++ ?>" name="<?= $row['name'] ?>" value="<?= !empty($row['value']) ? $row['value'] : "" ?>"<?= !empty($row['checked']) ? " checked" : "" ?><?= !empty($row['onchange']) ? " ${row['onchange']}" : "" ?>>
<?php
          break;
        case 'radio' :
          print "            <span class='uk-form-label'>${row['label']}</span>\n";
          foreach ( $row['option_loop'] as $option ) {
?>
          <label class=""><?= $option['label'] ?>
            <input type="radio" id="<?= !empty($row['id']) ? $row['id'] .'_'. $count++ : 'report_input_'.$count++ ?>" name="<?= $row['name'] ?>" value="<?= !empty($option['value']) ? $option['value'] : "" ?>"<?= !empty($option['selected']) ? " checked" : "" ?><?= !empty($row['onchange']) ? " ${row['onchange']}" : "" ?>>
          </label><br>
<?php
          }
          break;
        case 'number' :
?>
          <input type="number" id="<?= !empty($row['id']) ? $row['id'] : 'report_input_'.$count++ ?>" name="<?= $row['name'] ?>" value="<?= !empty($row['value']) ? $row['value'] : "" ?>"<?= !empty($row['min']) ? " min='${row['min']}'" : "" ?><?= !empty($row['max']) ? " max='${row['max']}'" : "" ?><?= !empty($row['step']) ? " step='${row['step']}'" : "" ?><?= !empty($row['onchange']) ? " ${row['onchange']}" : "" ?>>
<?php
          break;
        case 'date' :
?>
          <input type="date" id="<?= !empty($row['id']) ? $row['id'] : 'report_input_'.$count++ ?>" name="<?= $row['name'] ?>" data-uk-datepicker="{format:'YYYY-MM-DD'}" value="<?= !empty($row['value']) ? $row['value'] : "" ?>"<?= !empty($row['onchange']) ? " ${row['onchange']}" : "" ?>>
<?php   } ?>
        </div>
      </div>
<?php } ?>
      <div class="uk-form-row uk-form-controls">
        <input type="submit" value="Run Report" class="uk-button">
      </div>
    </fieldset>
  </form>
<?php   } ?>
<?php } ?>
</div>

<script>
function filter_select(value,el_id,mode) {
    if ( mode === undefined ) { mode = 'label_value'; }
    var el = document.getElementById(el_id);
    value = value.toLowerCase();
    var found = -1;

    for ( var i = 0; i < el.options.length; i++ ) {
        var this_opt = el.options[i];
        var text = this_opt.text.toLowerCase();
        var val = this_opt.value;
        var test = false;
        switch ( mode ) {
            case 'value':
                test = val == value;
                break;
            case 'label':
                test = text.indexOf(value) > -1;
                break;
            case 'value_label':
            default:
                test = text.indexOf(value) > -1 || val == value;
                break;
        }
        if ( test ) {
            if ( found < 0 ) { found = i; }
            if ( this_opt.style.display == 'none' ) {
                if ( this_opt.data_old_display ) {
                    this_opt.style.display = this_opt.data_old_display;
                }
                else {
                    this_opt.style.display = '';
                }
            }
        }
        else {
            if ( ! this_opt.data_old_display && this_opt.style.display != 'none' ) {
                this_opt.data_old_display = this_opt.style.display;
            }
            this_opt.style.display = 'none';
        }
    }
    if ( found >= 0 ) {
        el.value = el.options[found].value;
    }
}
</script>

</div>
</div>
<?php include $data['_config']['base_dir'].'/view/footer.php'; ?>

</body>
</html>
