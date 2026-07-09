<?php   
include_once 'admin_header.php';

//UPDATE ORDER STATUS
if (isset($_POST['update_status'])) {

    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();

    header("Location: admin_orders.php");
    exit();
}

//FILTER STATUS
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

$query = "
SELECT orders.*, users.name 
FROM orders 
JOIN users ON orders.user_id = users.id
";

if (!empty($filter_status)) {
    $query .= " WHERE orders.status = ?";
}

$query .= " ORDER BY orders.id DESC";

$stmt = $conn->prepare($query);

if (!empty($filter_status)) {
    $stmt->bind_param("s", $filter_status);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders - Admin</title>

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
    .status{padding:0.3rem 0.8rem;border-radius:20px;font-size:0.8rem;font-weight:500}
    .status.pending{background:#fef3c7;color:#92400e}
    .status.completed{background:#dcfce7;color:#166534}
    .status.cancelled{background:#fee2e2;color:#991b1b}
    .status.shipping{background:#dbeafe;color:#1e40af}
    .btn{display:inline-block;background:var(--primary);color:#fff;padding:0.5rem 1rem;border-radius:var(--radius);text-decoration:none;font-weight:500;font-size:0.9rem}
    .btn:hover{background:#1d4ed8}
    .btn-sm{padding:0.3rem 0.6rem;font-size:0.8rem}
    select{padding:0.4rem;border:1px solid var(--border);border-radius:var(--radius);background:#fff}
    @media(max-width:768px){body{flex-direction:column}.sidebar{width:100%;height:auto;position:relative}.sidebar a{display:inline-block;margin-right:0.5rem}}
  </style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <h2>🛡️ ShopLite Admin</h2>
    <a href="admin_dash.php" >📊 Dashboard</a>
    <a href="admin_products.php">📦 Products</a>
    <a href="admin_orders.php" class="active">🛒 Orders</a>
    <a href="admin_users.php">👥 Users</a>
    <a href="../index.php" target="_blank">🌐 View Store</a>
    <a href="admin_logout.php" class="logout">🚪 Logout</a>
  </aside>

<!-- MAIN -->
<main class="main">

  <div class="header">
    <h1>Orders</h1>

    <form method="GET">
      <select name="status" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="pending" <?php if($filter_status=="pending") echo "selected"; ?>>Pending</option>
        <option value="shipping" <?php if($filter_status=="shipping") echo "selected"; ?>>Shipping</option>
        <option value="completed" <?php if($filter_status=="completed") echo "selected"; ?>>Completed</option>
        <option value="cancelled" <?php if($filter_status=="cancelled") echo "selected"; ?>>Cancelled</option>
      </select>
    </form>
  </div>

  <div class="card">

    <table>
      <thead>
        <tr>
          <th>Order #</th>
          <th>Customer</th>
          <th>Total</th>
          <th>Status</th>
          <th>Date</th>
          <th>Update</th>
        </tr>
      </thead>

      <tbody>

      <?php if ($result->num_rows > 0) { ?>

        <?php while ($row = $result->fetch_assoc()) { ?>

        <tr>
          <td>#<?php echo str_pad($row['id'], 5, "0", STR_PAD_LEFT); ?></td>
          <td><?php echo $row['name']; ?></td>
          <td>$<?php echo $row['total']; ?></td>

          <td>
            <span class="status <?php echo $row['status']; ?>">
              <?php echo ucfirst($row['status']); ?>
            </span>
          </td>

          <td><?php echo date("M d, Y", strtotime($row['created_at'])); ?></td>

          <td>
            <form method="POST">
              <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">

              <select name="status" onchange="this.form.submit()">
                <option value="pending" <?php if($row['status']=="pending") echo "selected"; ?>>Pending</option>
                <option value="shipping" <?php if($row['status']=="shipping") echo "selected"; ?>>Shipping</option>
                <option value="completed" <?php if($row['status']=="completed") echo "selected"; ?>>Completed</option>
                <option value="cancelled" <?php if($row['status']=="cancelled") echo "selected"; ?>>Cancelled</option>
              </select>

              <input type="hidden" name="update_status" value="1">
            </form>
          </td>
        </tr>

        <?php } ?>

      <?php } else { ?>

        <tr>
          <td colspan="6">No orders found</td>
        </tr>

      <?php } ?>

      </tbody>
    </table>

  </div>

</main>

</body>
</html>