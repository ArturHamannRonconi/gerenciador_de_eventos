<?php

namespace core;

use PDO;

class Connection {
  private function __construct() {}
  
  public static function getConnection() {
    return  new PDO(
      Config::DB_DRIVER.":dbname=".Config::DB_NAME.";host=".Config::DB_HOST,
      Config::DB_USER, 
      Config::DB_PASS
    );
  }
}
