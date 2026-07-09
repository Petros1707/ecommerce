<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShopLite - Order Confirmed</title>
  <style>
    :root{--primary:#2563eb;--bg:#f8fafc;--surface:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--radius:8px;--shadow:0 2px 8px rgba(0,0,0,0.05)}
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);line-height:1.6}
    header{background:var(--surface);border-bottom:1px solid var(--border);padding:1rem 5%;display:flex;justify-content:space-between;align-items:center}
    nav a{margin-left:1.5rem;text-decoration:none;color:var(--text);font-weight:500}
    main{max-width:600px;margin:4rem auto;padding:0 5%;text-align:center}
    .card{background:var(--surface);padding:3rem 2rem;border-radius:var(--radius);box-shadow:var(--shadow)}
    .check{font-size:3rem;margin-bottom:1rem}
    .btn{display:inline-block;background:var(--primary);color:#fff;padding:0.6rem 1.2rem;border-radius:var(--radius);text-decoration:none;font-weight:500;margin-top:1.5rem}
    footer{text-align:center;padding:2rem;background:var(--surface);border-top:1px solid var(--border);margin-top:2rem}
  </style>
</head>
<body>
  <header>
    <h2>🛍️ ShopLite</h2>
    <nav>
      <a href="index.php">Home</a>
      <a href="products.php">Products</a>
    </nav>
  </header>
  <main>
    <div class="card">
      <div class="check">✅</div>
      <h1>Order Placed!</h1>
      <p style="color:var(--muted);margin-top:0.5rem">Thanks for shopping with us. Your order has been received and is now pending.</p>
      <a href="products.php" class="btn">Continue Shopping</a>
    </div>
  </main>
  <footer>&copy; 2026 ShopLite. All rights reserved.</footer>
</body>
</html>
