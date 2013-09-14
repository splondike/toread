<?php
require 'data.php';

$action_name = isset($_GET['id']) ? 'edit' : 'add';
$fields = array('name' => 'required', 'type' => 'required', 'author' => 'optional', 'url' => 'optional', 'description' => 'required');

function is_valid($item) {
  global $fields;

  foreach($fields as $field=>$required) {
    if ($required === 'required' && !isset($item[$field])) {
      return false;
    }
  }

  return true;
}

function add_fields($item) {
  global $fields;

  foreach($fields as $field=>$required) {
    $val = trim($_POST[$field]);
    if ($val !== '') {
      $item[$field] = $_POST[$field];
    }
    else {
      unset($item[$field]);
    }
  }

  return $item;
}

$item = array();
$valid = true;
if (isset($_POST['saving'])) {
  if ($action_name === 'add') {
    $item = add_fields(array());
    if (is_valid($item)) {
      $data->add($item);
      header('Location: index.php');
      die();
    }
    else {
      $valid = false;
    }
  }
  else {
    $id = $_GET['id'];
    $item = add_fields($data->get($id));
    if (is_valid($item)) {
      $data->edit($id, $item);
    }
    else {
      $valid = false;
    }
  }
}
elseif ($action_name === 'edit') {
  $id = $_GET['id'];
  $item = $data->get($id);
}

foreach($fields as $field => $required) {
  $val = isset($item[$field]) ? htmlentities($item[$field]) : '';
  $$field = $val;
}

?>
<!doctype html>
<html>
  <head>
    <title>To read: <?php echo $action_name ?></title>
    <link rel="stylesheet" href="screen.css">
  </head>
  <body>
    <h1 class="full-width">To read: <?php echo $action_name ?></h1>
    <?php if(!$valid) echo "<p class=\"message error\">Invalid input, check you've input everything.</p>" ?>
    <form class="edit form" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
      <label for="name" class="required">Name</label>
      <input type="text" id="name" name="name" value="<?php echo $name ?>">
      <label for="type" class="required" >Type</label>
      <input type="text" id="type" name="type" value="<?php echo $type ?>">
      <label for="author">Author</label>
      <input type="text" id="author" name="author" value="<?php echo $author ?>">
      <label for="url">URL</label>
      <input type="text" id="url" name="url" value="<?php echo $url ?>">
      <label for="description" class="required">Description</label>
      <textarea id="description" name="description" rows="6"><?php echo $description ?></textarea>
      <button name="saving">Submit</button>
      <a href="index.php">Back to list</a>
    </form>
  </body>
</html>
