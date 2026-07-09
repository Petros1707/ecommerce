<?php  
include_once 'admin_header.php';
 
$user_id= $_SESSION['user_id'];

//get total orders
$stmt =$conn->prepare("SELECT COUNT(*) AS total_orders FROM orders");
$stmt->execute();

$result=$stmt->get_result();
$row=$result->fetch_assoc();

$count_orders = isset($row['total_orders']) ? $row['total_orders'] : 0;

//get total products
$stmt =$conn->prepare("SELECT COUNT(*) AS total_products FROM products");
$stmt->execute();

$result=$stmt->get_result();
$row=$result->fetch_assoc();

$count_products = isset($row['total_products']) ? $row['total_products'] : 0;

//get registered users
$stmt=$conn->prepare("SELECT COUNT(*) AS total_users FROM users");
$stmt->execute();

$result=$stmt->get_result();
$row=$result->fetch_assoc();

$count_users = isset($row['total_users']) ? $row['total_users'] : 0;

//total revenue
$stmt = $conn->prepare("SELECT SUM(total) AS revenue FROM orders WHERE status = 'completed'");
$stmt->execute();


$result= $stmt->get_result();
$row = $result->fetch_assoc();

$count_revenue = isset($row['revenue']) ? $row['revenue'] : 0;


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - ShopLite</title>
  <style>
    :root{--primary:#2563eb;--bg:#f8fafc;--surface:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--radius:8px;--shadow:0 2px 8px rgba(0,0,0,0.05);--sidebar:#1e293b;--sidebar-text:#f1f5f9}
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh}
    
    /* Sidebar */
    .sidebar{width:240px;background:var(--sidebar);color:var(--sidebar-text);padding:1.5rem 1rem;position:sticky;top:0;height:100vh;display:flex;flex-direction:column}
    .sidebar h2{font-size:1.2rem;margin-bottom:2rem;padding-bottom:1rem;border-bottom:1px solid rgba(255,255,255,0.1)}
    .sidebar a{display:block;padding:0.7rem 1rem;color:var(--sidebar-text);text-decoration:none;border-radius:var(--radius);margin-bottom:0.3rem;font-weight:500}
    .sidebar a:hover,.sidebar a.active{background:rgba(255,255,255,0.1)}
    .sidebar .logout{margin-top:auto;background:#ef4444;color:#fff;border:none;padding:0.7rem 1rem;border-radius:var(--radius);cursor:pointer;font-weight:500;text-align:left;width:100%}
    .sidebar .logout:hover{background:#dc2626}
    
    /* Main */
    .main{flex:1;padding:2rem;overflow:auto}
    .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;padding-bottom:1rem;border-bottom:1px solid var(--border)}
    .header h1{font-size:1.5rem}
    
    /* Stats Grid */
    .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:2rem}
    .stat-card{background:var(--surface);padding:1.5rem;border-radius:var(--radius);box-shadow:var(--shadow);text-align:center}
    .stat-card .value{font-size:1.8rem;font-weight:700;color:var(--primary);margin:0.5rem 0}
    .stat-card .label{color:var(--muted);font-size:0.9rem}
    
    /* Table */
    .card{background:var(--surface);border-radius:var(--radius);box-shadow:var(--shadow);padding:1.5rem;margin-bottom:1.5rem}
    .card h3{margin-bottom:1rem;padding-bottom:0.5rem;border-bottom:1px solid var(--border)}
    table{width:100%;border-collapse:collapse}
    th,td{padding:0.8rem 1rem;text-align:left;border-bottom:1px solid var(--border)}
    th{font-weight:600;color:var(--muted);font-size:0.9rem}
    .status{padding:0.3rem 0.8rem;border-radius:20px;font-size:0.8rem;font-weight:500}
    .status.pending{background:#fef3c7;color:#92400e}
    .status.completed{background:#dcfce7;color:#166534}
    .status.cancelled{background:#fee2e2;color:#991b1b}
    .btn{display:inline-block;background:var(--primary);color:#fff;padding:0.5rem 1rem;border-radius:var(--radius);text-decoration:none;font-weight:500;font-size:0.9rem}
    .btn:hover{background:#1d4ed8}
    .btn-sm{padding:0.3rem 0.6rem;font-size:0.8rem}
    .btn-danger{background:#ef4444}
    .btn-danger:hover{background:#dc2626}
    
    @media(max-width:768px){
      body{flex-direction:column}
      .sidebar{width:100%;height:auto;position:relative}
      .sidebar a{display:inline-block;margin-right:0.5rem}
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <h2>🛡️ ShopLite Admin</h2>
    <a href="admin_dash.php" class="active">📊 Dashboard</a>
    <a href="admin_products.php">📦 Products</a>
    <a href="admin_orders.php">🛒 Orders</a>
    <a href="admin_users.php">👥 Users</a>
    <a href="../index.php" target="_blank">🌐 View Store</a>
    <a href="admin_logout.php" class="logout">🚪 Logout</a>
  </aside>

  <!-- Main Content -->
  <main class="main">
    <div class="header">
      <h1>Dashboard</h1>
      <div>Welcome, <strong><?php  echo $_SESSION['user_name'];?></strong></div>
    </div>

    <!-- Stats -->
    <div class="stats">
      <div class="stat-card">
        <div class="label">Total Products</div>
        <div class="value"><?php echo $count_products;?></div>
      </div>
      <div class="stat-card">
        <div class="label">Total Orders</div>
        <div class="value"><?php echo $count_orders;?></div>
      </div>
      <div class="stat-card">
        <div class="label">Registered Users</div>
        <div class="value"><?php echo $count_users;?></div>
      </div>
      <div class="stat-card">
        <div class="label">Revenue</div>
        <div class="value">$<?php echo $count_revenue;?></div>
      </div>
    </div>

    <!-- Recent Orders -->
    <div class="card">
      <h3>Recent Orders</h3>
      <table>
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php 

          //get recent orders
          $stmt = $conn->prepare("
            SELECT o.id, o.total, o.status, o.created_at, u.name as customer 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC LIMIT 5"
          );
          $stmt->execute();

          $result=$stmt->get_result();
          $orders = $result->fetch_assoc();

          foreach ($stmt as $orders) {
             
            

          ?>

          <tr>
            <td>#<?php echo $orders['id'], 5, '0', STR_PAD_LEFT;?></td>
            <td><?php echo $orders['name'];?></td>
            <td>$<?php echo $orders['price'];?></td>
            <td><span class="status completed"><?php echo $orders['status'];?></span></td>
            <td><?php echo date('M j, Y', strtotime($orders['created_at'])); ?></td>
            <td><a href="admin_orders.php" class="btn btn-sm">View</a></td>
          </tr>
          <?php } ?>

          <!-- <tr>
            <td>#00341</td>
            <td>Mike Johnson</td>
            <td>$89.99</td>
            <td><span class="status pending">Pending</span></td>
            <td>Apr 21, 2026</td>
            <td><a href="admin_orders.php" class="btn btn-sm">View</a></td>
          </tr>
          <tr>
            <td>#00340</td>
            <td>Sarah Lee</td>
            <td>$1,099.00</td>
            <td><span class="status completed">Completed</span></td>
            <td>Apr 20, 2026</td>
            <td><a href="admin_orders.php" class="btn btn-sm">View</a></td>
          </tr>
          <tr>
            <td>#00339</td>
            <td>Tom Wilson</td>
            <td>$65.50</td>
            <td><span class="status cancelled">Cancelled</span></td>
            <td>Apr 19, 2026</td>
            <td><a href="admin_orders.php" class="btn btn-sm">View</a></td>
          </tr>
          <tr>
            <td>#00338</td>
            <td>Emma Davis</td>
            <td>$449.00</td>
            <td><span class="status pending">Pending</span></td>
            <td>Apr 18, 2026</td>
            <td><a href="admin_orders.php" class="btn btn-sm">View</a></td>
          </tr> -->

        </tbody>
      </table>
      <a href="admin_orders.php" class="btn" style="margin-top:1rem">View All Orders</a>
    </div>
  </main>
</body>
</html>