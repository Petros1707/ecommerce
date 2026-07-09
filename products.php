<?php
session_start();
include_once 'dbconnect.php';

if (!isset($_SESSION['user_id'])) {
  echo "login required";
  header("refresh: 3, url=login.php");
  exit();
}

//cart count
include_once 'cart_count.php';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';



 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShopLite - Products</title>
  <style>
    :root{--primary:#2563eb;--bg:#f8fafc;--surface:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--radius:8px;--shadow:0 2px 8px rgba(0,0,0,0.05)}
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);line-height:1.6}
    header{background:var(--surface);border-bottom:1px solid var(--border);padding:1rem 5%;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:100}
    nav a{margin-left:1.5rem;text-decoration:none;color:var(--text);font-weight:500}
    nav a:hover{color:var(--primary)}
    main{max-width:1200px;margin:2rem auto;padding:0 5%;min-height:60vh}
    .btn{display:inline-block;background:var(--primary);color:#fff;padding:0.6rem 1.2rem;border-radius:var(--radius);text-decoration:none;border:none;cursor:pointer;font-weight:500}
    .btn:hover{background:#1d4ed8}
    .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem}
    .card{background:var(--surface);padding:1.5rem;border-radius:var(--radius);box-shadow:var(--shadow)}
    .card img{max-width:100%;border-radius:var(--radius);margin-bottom:1rem}
    .price{font-weight:700;color:var(--primary);margin:0.5rem 0}
    .controls{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem}
    select, input{padding:0.5rem;border:1px solid var(--border);border-radius:var(--radius)}
    footer{text-align:center;padding:2rem;background:var(--surface);border-top:1px solid var(--border);margin-top:2rem}
    @media(max-width:768px){header{flex-direction:column;gap:1rem}nav{display:flex;flex-wrap:wrap;gap:0.5rem}nav a{margin:0}}
  </style>
</head>
<body>
  <header>
    <h2>🛍️ ShopLite</h2>
    <nav>
      <a href="index.php">Home</a>
      <a href="products.php">Products</a>
      <a href="cart.php">Cart (<?php echo $cart_count; ?>)</a>
      <a href="login.php">Login</a>
    </nav>
  </header>
  <main>
    <form method="GET">
  <div class="controls">
    <h1>All Products</h1>

    <div>
      <!-- CATEGORY -->
      <select name="category">
        <option value="Electronics" <?php if($category=="Electronics") echo "selected"; ?>>All Categories</option>
        <option value="Electronics">Electronics</option>
        <option value="Fashion">Fashion</option>
        <option value="Home">Home</option>
      </select>

      <!-- SORT -->
      <select name="sort">
        <option value="newest">Sort: Newest</option>
        <option value="low">Price: Low to High</option>
        <option value="high">Price: High to Low</option>
      </select>

      <button type="submit" name="apply" class="btn">Apply</button>
    </div>
  </div>
</form>
    <div class="grid">
      <?php 
       

$query = "SELECT * FROM products";

// CATEGORY FILTER
if (!empty($category_id)) {
    $query .= " WHERE category_id = ?";
}

// SORTING
if ($sort == "low") {
    $query .= " ORDER BY price ASC";
} elseif ($sort == "high") {
    $query .= " ORDER BY price DESC";
} else {
    $query .= " ORDER BY id DESC"; // newest
}

// PREPARE
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

// BIND IF NEEDED
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// DISPLAY PRODUCTS
if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $image = $row['image'];
        $name = $row['name'];
        $price = $row['price'];
?>

    <div class="card">
        <img src="image/<?php echo $image; ?>">
        <h3><?php echo $name; ?></h3>
        <p class="price">$<?php echo $price; ?></p>
        <a href="product.php?id=<?php echo $id; ?>" class="btn">View</a>
    </div>

<?php
    }

} else {
    echo "<p>No products found</p>";
}

?>


      <!-- <div class="card"><img src="https://placehold.co/250x200/e2e8f0/1e293b?text=Laptop" alt="Laptop"><h3>UltraBook 14</h3><p class="price">$1,099.00</p><a href="product.html" class="btn">View</a></div>
      <div class="card"><img src="https://placehold.co/250x200/e2e8f0/1e293b?text=Shoes" alt="Shoes"><h3>Running Sneakers</h3><p class="price">$89.99</p><a href="product.html" class="btn">View</a></div>
      <div class="card"><img src="https://placehold.co/250x200/e2e8f0/1e293b?text=Camera" alt="Camera"><h3>Digital Camera</h3><p class="price">$449.00</p><a href="product.html" class="btn">View</a></div>
      <div class="card"><img src="https://placehold.co/250x200/e2e8f0/1e293b?text=Desk" alt="Desk"><h3>Standing Desk</h3><p class="price">$299.50</p><a href="product.html" class="btn">View</a></div>
      <div class="card"><img src="https://placehold.co/250x200/e2e8f0/1e293b?text=Plant" alt="Plant"><h3>Indoor Plant Kit</h3><p class="price">$34.99</p><a href="product.html" class="btn">View</a></div> -->
    </div>
  </main>
  <footer>&copy; 2026 ShopLite. All rights reserved.</footer>
</body>
</html>