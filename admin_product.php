<?php 
include_once 'admin_header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = trim($_POST['name']);
  $price = trim($_POST['price']);
  $quantity = $_POST['quantity'];
  $category = $_POST['category'];
  $image = $_POST['image'];
  $info = $_POST['info'];


  $stmt = $conn->prepare("INSERT INTO products (name, price, quantity, category,image, info) VALUES(?,?,?,?,?,?,)");
  $stmt->bind_param('siisss', $name, $price, $quantity, $category, $image, $info);
  $stmt->execute();


  

}



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Product - Admin</title>
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
    .card{background:var(--surface);border-radius:var(--radius);box-shadow:var(--shadow);padding:1.5rem;margin-bottom:1.5rem;max-width:700px}
    .card h3{margin-bottom:1rem;padding-bottom:0.5rem;border-bottom:1px solid var(--border)}
    .form-group{margin-bottom:1rem}
    .form-group label{display:block;margin-bottom:0.4rem;font-weight:500;font-size:0.9rem}
    .form-group input,.form-group select,.form-group textarea{width:100%;padding:0.7rem;border:1px solid var(--border);border-radius:var(--radius);font-size:1rem}
    .form-group textarea{min-height:100px;resize:vertical}
    .form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
    .btn{display:inline-block;background:var(--primary);color:#fff;padding:0.6rem 1.2rem;border-radius:var(--radius);text-decoration:none;border:none;cursor:pointer;font-weight:500}
    .btn:hover{background:#1d4ed8}
    .btn-secondary{background:var(--muted)}
    .btn-secondary:hover{background:#475569}
    .actions{display:flex;gap:0.5rem;margin-top:1.5rem}
    .preview{width:100px;height:100px;object-fit:cover;border-radius:var(--radius);margin-top:0.5rem;border:1px solid var(--border)}
    @media(max-width:768px){body{flex-direction:column}.sidebar{width:100%;height:auto;position:relative}.sidebar a{display:inline-block;margin-right:0.5rem}.form-row{grid-template-columns:1fr}}
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
      <h1>Add Product</h1>
      <a href="admin_products.php" class="btn btn-secondary">← Back</a>
    </div>

    <div class="card">
      <form method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <div class="form-group">
          <label for="name">Product Name *</label>
          <input type="text" id="name" name="name" required placeholder="e.g., Wireless Headphones Pro">
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="price">Price ($) *</label>
            <input type="number" id="price" name="price" step="0.01" min="0" required placeholder="0.00">
          </div>
          <div class="form-group">
            <label for="stock">Stock Quantity *</label>
            <input type="number" id="stock" name="quantity" min="0" required placeholder="0">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category">
              <option value="">Select Category</option>
              <option>Electronics</option>
              <option>Fashion</option>
              <option>Home</option>
              <option>Sports</option>
            </select>
          </div>
          <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" id="image" name="image" accept="image/*">
            <img src="" class="preview" id="preview" alt="Preview" style="display:none">
          </div>
        </div>

        <div class="form-group">
          <label for="description">Description</label>
          <textarea id="description" name="info" placeholder="Product details, features, specs..."></textarea>
        </div>

        <div class="actions">
          <button type="submit" class="btn">Save Product</button>
          <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
      </form>
    </div>
  </main>

  <!-- <script>
    // Optional: Simple image preview (can be removed if you want pure HTML/CSS)
    document.getElementById('image')?.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
          const preview = document.getElementById('preview');
          preview.src = ev.target.result;
          preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
      }
    });
  </script> -->
</body>
</html>