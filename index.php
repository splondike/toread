<?php
require 'data.php';
// TODO: Find me
/*
$date = 'file-modified-time';
header('Last-Modified: ' . $date);
header('Etag: ' . $date);
 */
?>
<!doctype html>
<html>
  <head>
    <title>To read</title>
    <link rel="stylesheet" href="screen.css">
  </head>
  <body>
    <h1 class="full-width">To read</h1>
    <?php
$items = $data->get();
$count = $items->count();
if ($count > 0) {
  echo "<ol class=\"items full-width\">\n";
  foreach ($items as $pos=>$item) {
    $down_pos = $pos == 0 ? $pos : $pos - 1;
    $up_pos = $pos == $count - 1 ? $pos : $pos + 1;

    $i = array();
    foreach ($item as $key=>$val) {
      $i[$key] = htmlentities($val);
    }
    $i = (object) $i;
    $author = '';
    if (isset($i->author)) {
      $author = " <span class=\"author\">$i->author</span>";
    }
    $out = <<<EOT
      <li class="item type-$i->type">
        <div class="title">$i->name$author</div>
        <p>$i->description</p>
        <div class="actions">
EOT;
    if (isset($i->url) && $i->url !== '') {
      $out .= "<a href=\"$i->url\">View</a>";
    }
    $out .= <<<EOT
          <a href="edit.php?id=$i->id">Edit</a>
          <a href="delete.php?id=$i->id">Delete</a>
          <form method="post" action="move.php">
            <input type="hidden" name="id" value="$i->id">
            <input type="hidden" name="new_pos" value="{$down_pos}">
            <button>Move up</button>
          </form>
          <form method="post" action="move.php">
            <input type="hidden" name="id" value="$i->id">
            <input type="hidden" name="new_pos" value="{$up_pos}">
            <button>Move down</button>
          </form>
        </div>
      </li>
EOT;
    echo $out;
  }
  echo "</ol>\n";
}
else {
  echo '<p>No items</p>';
}
    ?>
    <a class="large-add-button" href="edit.php">Add</a>
  </body>
</html>

