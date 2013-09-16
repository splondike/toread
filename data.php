<?php

require 'spyc/Spyc.php';

/**
 * Abstracts away the data layer.
 */
class Data {
  const SAVE_FILE = 'toread.yaml';
  private $data;

  public function get($id = 'all') {
    $this->loadData();

    if ($id === 'all') {
      return new ArrayIterator($this->data);
    }
    else {
      return $this->data[$id];
    }
  }

  public function move($id, $new_pos) {
    $this->loadData();

    if (count($this->data) - 1 < $id || $id < 0) {
      throw "Invalid id: $id";
    }
    if (count($this->data) - 1 < $new_pos || $new_pos < 0) {
      throw "Invalid new_pos: $new_pos";
    }

    $item = $this->data[$id];
    array_splice($this->data, $id, 1);
    array_splice($this->data, $new_pos, 0, array($item));
    $this->saveData();
  }

  public function add($new_value) {
    $this->loadData();

    $this->data[] = $new_value;
    $this->saveData();
  }

  public function edit($id, $new_value) {
    $this->loadData();

    if (count($this->data) - 1 < $id || $id < 0) {
      throw "Invalid id: $id";
    }

    $this->data[$id] = $new_value;
    $this->saveData();
  }

  public function delete($id) {
    $this->loadData();

    if (count($this->data) - 1 < $id || $id < 0) {
      throw "Invalid id: $id";
    }

    array_splice($this->data, $id, 1);
    $this->saveData();
  }

  public function lastModified() {
    return filemtime(self::SAVE_FILE);
  }

  private function saveData() {
    $this->loadData();

    $raw_items = array();
    foreach ($this->data as $id=>$item) {
      unset($item['id']);
      $raw_items[] = $item;
    }
    file_put_contents(self::SAVE_FILE, spyc_dump($raw_items));
  }

  private function loadData() {
    if (!is_null($this->data)) {
      return;
    }

    if (file_exists(self::SAVE_FILE)) {
      $raw_items = spyc_load_file(file_get_contents(self::SAVE_FILE));
      $ided_items = array();
      foreach ($raw_items as $id=>$item) {
        $item['id'] = $id;
        $ided_items[] = $item;
      }
      $this->data = $ided_items;
    }
    else {
      $this->data = array();
    }
  }
}

// Singleton for data access
$data = new Data();
