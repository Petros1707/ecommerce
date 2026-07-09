<?php 
session_start();
include_once 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];


  $stmt=$conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param('s', $email);
  $stmt->execute();

  $result= $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user  > 0) {
    if (password_verify(password, $user['hashedpwd'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_email'] = $user['email'];
      $_SESSION['user_name'] = $user['name'];
      $_SESSION['user_role'] = $user['role'];


      if ($user['role'] == 'admin') {
        header("location: admin_dash.php");
        exit();
      }else{
        echo "error or u not an admin: " . $stmt->error();
        header("refresh: 3, url=admin_login.php");
        exit();

      }

    }
  }
}

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - ShopLite</title>
  <style>
    :root{--primary:#2563eb;--bg:#f1f5f9;--surface:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--radius:8px;--shadow:0 4px 12px rgba(0,0,0,0.1)}
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1rem}
    .card{background:var(--surface);padding:2.5rem;border-radius:var(--radius);box-shadow:var(--shadow);width:100%;max-width:400px}
    .card h1{text-align:center;margin-bottom:1.5rem;font-size:1.5rem}
    .form-group{margin-bottom:1.2rem}
    .form-group label{display:block;margin-bottom:0.4rem;font-weight:500;font-size:0.9rem}
    .form-group input{width:100%;padding:0.7rem;border:1px solid var(--border);border-radius:var(--radius);font-size:1rem}
    .btn{width:100%;background:var(--primary);color:#fff;padding:0.8rem;border:none;border-radius:var(--radius);font-weight:600;cursor:pointer;font-size:1rem;margin-top:0.5rem}
    .btn:hover{background:#1d4ed8}
    .error{background:#fef2f2;color:#dc2626;padding:0.8rem;border-radius:var(--radius);margin-bottom:1rem;font-size:0.9rem;text-align:center}
    .back{display:block;text-align:center;margin-top:1.5rem;color:var(--primary);text-decoration:none;font-size:0.9rem}
    .back:hover{text-decoration:underline}
  </style>
</head>
<body>
  <div class="card">
    <h1>🛡️ Admin Login</h1>
    <!-- <div class="error">Invalid credentials</div> -->
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required autocomplete="email">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">
      </div>
      <button type="submit" class="btn">Sign In</button>
    </form>
    <a href="../index.html" class="back">← Back to Store</a>
  </div>
</body>
</html>