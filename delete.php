<?php
require 'data.php';

if (!isset($_GET['id'])) die('No id.');

$id = $_GET['id'];
$item = $data->get($id);
$name = $item['name'];

if (isset($_POST['deleting'])) {
  header('Location: index.php');
  $data->delete($id);
  die();

}
?>
<!doctype html>
<html>
  <head>
    <title>To read: delete</title>
    <link rel="stylesheet" href="screen.css">
  </head>
  <body>
    <h1 class="full-width">To read: delete</h1>
    <p>Are you sure you want to delete "<?php echo $name ?>"?</p>
    <form class="edit form" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
      <button name="deleting">Yes</button>
      <a href="index.php">Back to list</a>
    </form>
  </body>
</html>
