<?php
class Database {
  private $_dbh;

  public function __construct($host, $dbname, $username, $password) {
    try {
      $this->_dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
      $this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("Database connection failed: " . $e->getMessage());
    }
  }
  
  public function prepare($query) {
    $stmt = $this->_dbh->prepare($query);
    if (!$stmt) {
      throw new Exception("Erreur lors de la préparation de la requête : " . implode(" / ", $this->_dbh->errorInfo()));
    }
    return $stmt;
  }
}