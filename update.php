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
  $id = $_GET['id'] ?? null;

  if(!$id) {
    header('Location: index.php');
    exit;
  }

  $statement  = $pdo->prepare('SELECT * FROM products WHERE id = :id');
  $statement->bindValue(':id', $id);
  $statement->execute();
  $product = $statement->fetch(PDO::FETCH_ASSOC);

  $errors = [];

  $title = $product['title'];
  $description = $product['description'];
  $price = $product['price'];
  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $date = date('Y-m-d H:i:s');

    if(!$title) {
      $errors[] = 'Product title is required';
    }
    if(!$price) {
      $errors[] = 'Product price is required';
    }

    if(!is_dir('images')) {
      mkdir('images');
    }
    if(empty($errors)) {

      $image = $_FILES['image'] ?? null;
      $imagePath = $product['image'];

      

      if($image && $image['tmp_name']) {
        if($product['image']) {
          unlink($product['image']);
        }
        $imagePath = 'images/'.randomString(8).'/'.$image['name'];
        mkdir(dirname($imagePath));

        move_uploaded_file($image['tmp_name'], $imagePath);
      }


      $statement = $pdo->prepare("UPDATE products SET title = :title, image = :image, description = :description, price= :price WHERE id = :id");
      $statement->bindValue(':title', $title);
      $statement->bindValue(':image', $imagePath);
      $statement->bindValue(':description', $description);
      $statement->bindValue(':price', $price);
      $statement->bindValue(':id', $id);
      $statement->execute();

      header('Location: index.php');
    }
  }

  function randomString ($n) 
  {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $str = '';
    for($i = 1; $i < $n; $i++) {
      $index = rand(0, strlen($characters) - 1); 
      $str .= $characters[$index];
    }
    return $str;
  }
?>

<?php include_once "views/partials/header.php" ?>


<div class="container">
  <a href="index.php" class="btn btn-secondary">Go Back to Products</a>
  <h1>Update Product: <b><?php echo $product['title'] ?></b></h1>
  <?php if(!empty($errors)) : ?>
    <div class="alert alert-danger">
      <?php 
        foreach($errors as $error): ?>
          <div><?php echo $error ?></div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form action="" method="post" enctype="multipart/form-data">

    <?php if($product['image']): ?>
      <img src="<?php echo $product['image'] ?>" class="show-image" alt="Image Not Found">
    <?php endif; ?>
    <div class="form-group">
      <label for="image">Product Image</label>
      <br>
      <input type="file" name="image">
    </div>
    <div class="form-group">
      <label for="title">Product Title</label> 
      <input type="text" name="title" class="form-control" value="<?php echo $title ?>">
    </div>
    <div class="form-group">
      <label for="title">Product Description</label>
      <textarea name="description" class="form-control"><?php echo $description ?></textarea>
    </div>
    <div class="form-group">
      <label for="title">Product Price</label>
      <input type="number" step=".01" name="price" class="form-control" value="<?php echo $price ?>">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>

  </form>
</div>


</body>
</html>