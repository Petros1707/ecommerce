<?php
session_start();
include_once 'dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// GET CART ITEMS
$stmt = $conn->prepare("SELECT name, price, quantity FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
$items = [];

while ($row = $result->fetch_assoc()) {
    $row_total = $row['price'] * $row['quantity'];
    $total += $row_total;
    $items[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $address = $_POST['address'];

    // save order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();

    // clear cart
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");

    header("Location: success.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShopLite - Checkout</title>
  <style>
    :root{--primary:#2563eb;--bg:#f8fafc;--surface:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--radius:8px;--shadow:0 2px 8px rgba(0,0,0,0.05)}
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);line-height:1.6}
    header{background:var(--surface);border-bottom:1px solid var(--border);padding:1rem 5%;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:100}
    nav a{margin-left:1.5rem;text-decoration:none;color:var(--text);font-weight:500}
    nav a:hover{color:var(--primary)}
    main{max-width:1000px;margin:2rem auto;padding:0 5%;min-height:60vh}
    .btn{display:inline-block;background:var(--primary);color:#fff;padding:0.6rem 1.2rem;border-radius:var(--radius);text-decoration:none;border:none;cursor:pointer;font-weight:500}
    .btn:hover{background:#1d4ed8}
    .form-group{margin-bottom:1rem}
    .form-group label{display:block;margin-bottom:0.3rem;font-weight:500}
    .form-group input,.form-group select{width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:var(--radius)}
    .checkout{display:grid;grid-template-columns:1.5fr 1fr;gap:2rem}
    .card{background:var(--surface);padding:1.5rem;border-radius:var(--radius);box-shadow:var(--shadow)}
    .summary .row{display:flex;justify-content:space-between;margin:0.5rem 0}
    .summary .total{font-weight:700;margin-top:1rem;border-top:1px solid var(--border);padding-top:0.5rem}
    footer{text-align:center;padding:2rem;background:var(--surface);border-top:1px solid var(--border);margin-top:2rem}
    @media(max-width:768px){header{flex-direction:column;gap:1rem}nav{display:flex;flex-wrap:wrap;gap:0.5rem}nav a{margin:0}.checkout{grid-template-columns:1fr}}
  </style>
</head>
<body>
  <header>
    <h2>🛍️ ShopLite</h2>
    <nav>
      <a href="index.html">Home</a>
      <a href="products.html">Products</a>
      <a href="cart.html">Cart (2)</a>
      <a href="login.html">Login</a>
    </nav>
  </header>
  <main>
    <h1 style="margin-bottom:1.5rem">Checkout</h1>
    <form class="checkout" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
      <div>
        <div class="card" style="margin-bottom:1.5rem">
          <h2 style="margin-bottom:1rem">Shipping Information</h2>
          <div class="form-group"><label>Full Name</label><input type="text" required></div>
          <div class="form-group"><label>Email</label><input type="email" required></div>
          <div class="form-group"><label>Address</label><input type="text" required></div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div class="form-group"><label>City</label><input type="text" required></div>
            <div class="form-group"><label>ZIP</label><input type="text" required></div>
          </div>
        </div>
        <div class="card">
          <h2 style="margin-bottom:1rem">Payment Details</h2>
          <div class="form-group"><label>Card Number</label><input type="text" placeholder="0000 0000 0000 0000" required></div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div class="form-group"><label>Expiry</label><input type="text" placeholder="MM/YY" required></div>
            <div class="form-group"><label>CVC</label><input type="text" placeholder="123" required></div>
          </div>
        </div>
      </div>
      <div class="card summary">
        <h2 style="margin-bottom:1rem">Order Review</h2>
        <?php foreach ($items as $item) { ?>
  <div class="row">
    <span><?php echo $item['name']; ?></span>
    <span>$<?php echo $item['price'] * $item['quantity']; ?></span>
  </div>
<?php } ?>


        <div class="row total">
  <span>Total</span>
  <span>$<?php echo $total; ?></span>
</div>

        <button type="submit" class="btn" style="width:100%;margin-top:1.5rem">Place Order</button>
      </div>
    </form>
  </main>
  <footer>&copy; 2026 ShopLite. All rights reserved.</footer>
</body>
</html>