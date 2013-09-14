<?php
require 'data.php';

$id = $_POST['id'];
$new_pos = $_POST['new_pos'];

$data->move($id, $new_pos);
header('Location: index.php');
die();
