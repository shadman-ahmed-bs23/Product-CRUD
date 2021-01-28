<?php 
  $servername = 'localhost';
  $user = 'root';
  $password = 'bT4sM2h8SuBV&@2a';
  $dbname = 'products_crud';

  //Set DSN
  $dsn = 'mysql:host='. $host . ';dbname='. $dbname;

  //Create a pdo instance
  $pdo = new PDO($dsn, $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $id = $_POST['id'] ?? null;

  if(!$id) {
    header('Location: index.php');
    exit;
  }

  $statement  = $pdo->prepare('DELETE FROM products WHERE id = :id');
  $statement->bindValue(':id', $id);
  $statement->execute();
  header("Location: index.php");
?>