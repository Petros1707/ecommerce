<?php 

session_start();
include_once 'dbconnect.php';

//check login status
if (!isset($_SESSION['user_id'])) {
  echo "login as admin first";
  header("refresh: 3, url=admin_login.php");
  exit();
}

// CHECK LOGIN
if (!isset($_SESSION['user_id'])) {
  echo "Login required";
  header("refresh: 2; url=admin_login.php");
  exit();
}

/* -----------------------------
   GET FILTER VALUES
------------------------------*/
$search   = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$stock    = isset($_GET['stock']) ? $_GET['stock'] : '';

/* -----------------------------
   BUILD QUERY
------------------------------*/
$query = "SELECT * FROM products WHERE 1=1";

if (!empty($search)) {
    $query .= " AND name LIKE '%" . $conn->real_escape_string($search) . "%'";
}

if (!empty($category)) {
  $query = "
  SELECT products.*, category.category_name
   FROM products 
   JOIN category ON products.category_name = category_id
   ";
    $query .= " AND category = '" . $conn->real_escape_string($category) . "'";
}

if ($stock === "in") {
    $query .= " AND stock > 0";
} elseif ($stock === "out") {
    $query .= " AND stock = 0";
}

$query .= " ORDER BY id DESC";

$result = $conn->query($query);

 // DELETE PRODUCT
if (isset($_POST['remove'])) {

    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_products.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products - Admin</title>
  <style>
    :root{--primary:#2563eb;--bg:#f8fafc;--surface:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--radius:8px;--shadow:0 2px 8px rgba(0,0,0,0.05);--sidebar:#1e293b;--sidebar-text:#f1f5f9}
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh}
    .sidebar{width:240px;background:var(--sidebar);color:var(--sidebar-text);padding:1.5rem 1rem;position:sticky;top:0;height:100vh;display:flex;flex-direction:column}
    .sidebar h2{font-size:1.2rem;margin-bottom:2rem;padding-bottom:1rem;border-bottom:1px solid rgba(255,255,255,0.1)}
    .sidebar a{display:block;padding:0.7rem 1rem;color:var(--sidebar-text);text-decoration:none;border-radius:var(--radius);margin-bottom:0.3rem;font-weight:500}
    .sidebar a:hover,.sidebar a.active{background:rgba(255,255,255,0.1)}
    .sidebar .logout{margin-top:auto;background:#ef4444;color:#fff;border:none;padding:0.7rem 1rem;border-radius:var(--radius);cursor:pointer;font-weight:500;text-align:left;width:100%}
    .sidebar .logout:hover{background:#dc2626}
    .main{flex:1;padding:2rem;overflow:auto}
    .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;padding-bottom:1rem;border-bottom:1px solid var(--border)}
    .header h1{font-size:1.5rem}
    .card{background:var(--surface);border-radius:var(--radius);box-shadow:var(--shadow);padding:1.5rem;margin-bottom:1.5rem}
    .card h3{margin-bottom:1rem;padding-bottom:0.5rem;border-bottom:1px solid var(--border)}
    table{width:100%;border-collapse:collapse}
    th,td{padding:0.8rem 1rem;text-align:left;border-bottom:1px solid var(--border)}
    th{font-weight:600;color:var(--muted);font-size:0.9rem}
    .btn{display:inline-block;background:var(--primary);color:#fff;padding:0.5rem 1rem;border-radius:var(--radius);text-decoration:none;font-weight:500;font-size:0.9rem}
    .btn:hover{background:#1d4ed8}
    .btn-sm{padding:0.3rem 0.6rem;font-size:0.8rem}
    .btn-danger{background:#ef4444}
    .btn-danger:hover{background:#dc2626}
    .product-img{width:60px;height:60px;object-fit:cover;border-radius:var(--radius)}
    .search-bar{display:flex;gap:0.5rem;margin-bottom:1rem;flex-wrap:wrap}
    .search-bar input,.search-bar select{padding:0.5rem;border:1px solid var(--border);border-radius:var(--radius)}
    @media(max-width:768px){body{flex-direction:column}.sidebar{width:100%;height:auto;position:relative}.sidebar a{display:inline-block;margin-right:0.5rem}}
  </style>
</head>
<body>
  <aside class="sidebar">
    <h2>🛡️ ShopLite Admin</h2>
    <a href="admin_dash.php" >📊 Dashboard</a>
    <a href="admin_products.php" class="active">📦 Products</a>
    <a href="admin_orders.php">🛒 Orders</a>
    <a href="admin_users.php">👥 Users</a>
    <a href="../index.php" target="_blank">🌐 View Store</a>
    <a href="admin_logout.php" class="logout">🚪 Logout</a>
  </aside>

  <main class="main">
    <div class="header">
      <h1>Products</h1>
      <a href="admin_product.php" class="btn">+ Add Product</a>
    </div>

    <div class="card">
      <div class="search-bar">
        <form method="GET" class="search-bar">

            <input type="text" name="search" placeholder="Search products..." 
                   value="<?php echo $search; ?>">

            <select name="category">
              <option value="">All Categories</option>
              <option value="Electronics" <?php if($category=="Electronics") echo "selected"; ?>>Electronics</option>
              <option value="Fashion" <?php if($category=="Fashion") echo "selected"; ?>>Fashion</option>
              <option value="Home" <?php if($category=="Home") echo "selected"; ?>>Home</option>
            </select>

            <select name="stock">
              <option value="">All Stock</option>
              <option value="in" <?php if($stock=="in") echo "selected"; ?>>In Stock</option>
              <option value="out" <?php if($stock=="out") echo "selected"; ?>>Out of Stock</option>
            </select>

            <button class="btn" type="submit">Filter</button>

          </form>
      </div>
      <table>
        <thead>
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Category</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>

         <?php if ($result && $result->num_rows > 0) { ?>

        <?php while ($row = $result->fetch_assoc()) { ?>

        <tr>
          <td>
            <img src="image/<?php echo $row['image']; ?>" width="50">
          </td>

          <td><?php echo $row['name']; ?></td>

          <td>$<?php echo $row['price']; ?></td>

          <td><?php echo $row['category_id']; ?></td>

          <td>
            <?php echo ($row['stock'] > 0) ? "In Stock" : "Out of Stock"; ?>
          </td>

          <td>

            <!-- EDIT -->
            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn">
              Edit
            </a>

            <!-- DELETE -->
            <form method="POST" style="display:inline;">
              <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

              <button type="submit"
                      name="remove"
                      class="btn btn-danger"
                      onclick="return confirm('Delete this product?')">
                Delete
              </button>
            </form>

          </td>
        </tr>

        <?php } ?>

        <?php } else { ?>

        <tr>
          <td colspan="6">No products found</td>
        </tr>

        <?php } ?>

<!-- 
          <tr>
            <td><img src="https://placehold.co/60x60/e2e8f0/1e293b?text=SW" class="product-img" alt="Smartwatch"></td>
            <td>Smartwatch Pro</td>
            <td>$149.00</td>
            <td>23</td>
            <td>Electronics</td>
            <td>
              <a href="product-form.html" class="btn btn-sm">Edit</a>
              <a href="#" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
            </td>
          </tr>
          <tr>
            <td><img src="https://placehold.co/60x60/e2e8f0/1e293b?text=BP" class="product-img" alt="Backpack"></td>
            <td>Travel Backpack</td>
            <td>$65.50</td>
            <td>12</td>
            <td>Fashion</td>
            <td>
              <a href="product-form.html" class="btn btn-sm">Edit</a>
              <a href="#" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
            </td>
          </tr>
          <tr>
            <td><img src="https://placehold.co/60x60/e2e8f0/1e293b?text=PH" class="product-img" alt="Phone"></td>
            <td>Smartphone X</td>
            <td>$699.00</td>
            <td>0</td>
            <td>Electronics</td>
            <td>
              <a href="product-form.html" class="btn btn-sm">Edit</a>
              <a href="#" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
            </td>
          </tr>
          <tr>
            <td><img src="https://placehold.co/60x60/e2e8f0/1e293b?text=LP" class="product-img" alt="Laptop"></td>
            <td>UltraBook 14</td>
            <td>$1,099.00</td>
            <td>8</td>
            <td>Electronics</td>
            <td>
              <a href="product-form.html" class="btn btn-sm">Edit</a>
              <a href="#" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
            </td>
          </tr> -->

        </tbody>
      </table>
    </div>
  </main>
</body>
</html>