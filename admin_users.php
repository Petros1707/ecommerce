<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users - Admin</title>
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
    .search-bar{display:flex;gap:0.5rem;margin-bottom:1rem;flex-wrap:wrap}
    .search-bar input,.search-bar select{padding:0.5rem;border:1px solid var(--border);border-radius:var(--radius)}
    @media(max-width:768px){body{flex-direction:column}.sidebar{width:100%;height:auto;position:relative}.sidebar a{display:inline-block;margin-right:0.5rem}}
  </style>
</head>
<body>
 <aside class="sidebar">
    <h2>🛡️ ShopLite Admin</h2>
    <a href="admin_dash.php" >📊 Dashboard</a>
    <a href="admin_products.php">📦 Products</a>
    <a href="admin_orders.php">🛒 Orders</a>
    <a href="admin_users.php" class="active">👥 Users</a>
    <a href="../index.php" target="_blank">🌐 View Store</a>
    <a href="admin_logout.php" class="logout">🚪 Logout</a>
  </aside>
  <main class="main">
    <div class="header">
      <h1>Users</h1>
      <div class="search-bar">
        <input type="text" placeholder="Search users...">
        <select>
          <option>All Roles</option>
          <option>Customer</option>
          <option>Admin</option>
        </select>
      </div>
    </div>

    <div class="card">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Joined</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>#1024</td>
            <td>Jane Smith</td>
            <td>jane@email.com</td>
            <td>Customer</td>
            <td>Mar 15, 2026</td>
            <td>
              <a href="#" class="btn btn-sm">View</a>
              <a href="#" class="btn btn-sm btn-danger">Block</a>
            </td>
          </tr>
          <tr>
            <td>#1023</td>
            <td>Mike Johnson</td>
            <td>mike@email.com</td>
            <td>Customer</td>
            <td>Mar 10, 2026</td>
            <td>
              <a href="#" class="btn btn-sm">View</a>
              <a href="#" class="btn btn-sm btn-danger">Block</a>
            </td>
          </tr>
          <tr>
            <td>#1022</td>
            <td>Sarah Lee</td>
            <td>sarah@email.com</td>
            <td>Customer</td>
            <td>Feb 28, 2026</td>
            <td>
              <a href="#" class="btn btn-sm">View</a>
              <a href="#" class="btn btn-sm btn-danger">Block</a>
            </td>
          </tr>
          <tr>
            <td>#1001</td>
            <td>Admin User</td>
            <td>admin@shoplite.com</td>
            <td>Admin</td>
            <td>Jan 01, 2026</td>
            <td>
              <a href="#" class="btn btn-sm">View</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>