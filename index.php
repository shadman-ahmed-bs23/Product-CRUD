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

  $search = $_GET['search'] ?? '';

  if($search) {
    $statement = $pdo->prepare('SELECT * FROM products WHERE title LIKE :title ORDER BY create_date DESC');
    $statement->bindValue(':title', "%$search%");
  } 
  else {
    $statement = $pdo->prepare('SELECT * FROM products ORDER BY create_date DESC');
  }
  
  
  $statement->execute();
  $products = $statement->fetchAll(PDO::FETCH_ASSOC);
  // echo '<pre>';
  // var_dump($products);
  // echo '</pre>';  
?>

<?php include_once "views/partials/header.php" ?>
    
<div class="container">
  <h1>Products CRUD</h1>
  <p>
    <a href="create.php" class="btn btn-success">Create Product</a>
  </p>

  <form action="">
    <div class="input-group mb-3">
      <input 
        type="text" 
        class="form-control" 
        name="search" placeholder="Search for products"
        value="<?php echo $search ?>"
      >
      <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="submit">Search</button>
      </div>
    </div>
  </form>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Image</th>
        <th scope="col">Title</th>
        <th scope="col">Price</th>
        <th scope="col">Create Date</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($products as $i => $product): ?>
        <tr>
          <td><?php echo $i+1 ?></td>
          <td>
            <img src="<?php echo $product['image'] ?>"  class="thumb-image" alt="Img not founds">
          </td>
          <td><?php echo $product['title'] ?></td>
          <td><?php echo $product['price'] ?></td>
          <td><?php echo $product['create_date'] ?></td>
          <td>
            <a href="update.php?id=<?php echo $product['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <form style="display: inline-block" action="delete.php" method="post">
              <input type="hidden" name="id" value="<?php echo $product['id'] ?> ">
              <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>


</body>
</html>