<?php  
session_start();
include_once 'dbconnect.php';

//CHECK LOGIN
if (!isset($_SESSION['user_id'])) {
    echo "Login required";
    header("Refresh: 2; url=login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

//cart count
include_once 'cart_count.php';
  
  if (isset($_POST['remove'])) {
    $id= $_POST['id'];


    $stmt= $conn->prepare("DELETE  FROM cart WHERE id=? AND user_id =?");
    $stmt->bind_param("ii", $id, $user_id);

    if ($stmt->execute()) {
      echo "item removed";
      header("location: cart.php");
      exit();
    }else{
      echo "error: " .$stmt->error();
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShopLite - Cart</title>
  <style>
    :root{--primary:#2563eb;--bg:#f8fafc;--surface:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--radius:8px;--shadow:0 2px 8px rgba(0,0,0,0.05)}
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);line-height:1.6}
    header{background:var(--surface);border-bottom:1px solid var(--border);padding:1rem 5%;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:100}
    nav a{margin-left:1.5rem;text-decoration:none;color:var(--text);font-weight:500}
    nav a:hover{color:var(--primary)}
    main{max-width:900px;margin:2rem auto;padding:0 5%;min-height:60vh}
    .btn{display:inline-block;background:var(--primary);color:#fff;padding:0.6rem 1.2rem;border-radius:var(--radius);text-decoration:none;border:none;cursor:pointer;font-weight:500}
    .btn:hover{background:#1d4ed8}
    .cart-item{display:flex;align-items:center;gap:1.5rem;padding:1.5rem;background:var(--surface);border-radius:var(--radius);margin-bottom:1rem;box-shadow:var(--shadow)}
    .cart-item img{width:80px;height:80px;object-fit:cover;border-radius:var(--radius)}
    .cart-item .info{flex:1}
    .cart-item .price{font-weight:700;color:var(--primary)}
    .summary{background:var(--surface);padding:1.5rem;border-radius:var(--radius);box-shadow:var(--shadow);margin-top:1rem}
    .summary h3{margin-bottom:1rem;border-bottom:1px solid var(--border);padding-bottom:0.5rem}
    .summary .row{display:flex;justify-content:space-between;margin:0.5rem 0}
    .summary .total{font-weight:700;font-size:1.2rem;margin-top:1rem;border-top:1px solid var(--border);padding-top:0.5rem}
    footer{text-align:center;padding:2rem;background:var(--surface);border-top:1px solid var(--border);margin-top:2rem}
    @media(max-width:768px){header{flex-direction:column;gap:1rem}nav{display:flex;flex-wrap:wrap;gap:0.5rem}nav a{margin:0}.cart-item{flex-direction:column;text-align:center}}
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
    <h1 style="margin-bottom:1.5rem">Your Cart</h1>
    <form method="POST" action="<?Php echo $_SERVER['PHP_SELF'];?>">
    <div class="cart-item">
      <?php
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


$subtotal = 0;
$shipping = 0;
$total = 0;

 while ($row = $result->fetch_assoc()) { 
    $id = $row['id'];
    $image = $row['image'];
    $name = $row['name'];
    $price = $row['price'];
    $quantity = $row['quantity'];

    $subtotal = $price * $quantity;
    $shipping = $subtotal * 0.1;
    $total += $subtotal + $shipping;
?>

    <img src="image/<?php echo $image; ?>">

    <div class="info">
        <h3><?php echo $name; ?></h3>
        <p>Qty: <?php echo $quantity; ?></p>
    </div>

    <div class="price">$<?php echo $price; ?></div>


    <form method="POST" style="margin-left:auto;">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <button class="btn" style="background:#ef4444" type="submit" name="remove">
            Remove
        </button>
    </form>
</div>

<?php } ?>
    </div>
    </form>


    <div class="summary">
      <h3>Order Summary</h3>
      <div class="row"><span>Subtotal</span><span>$<?php echo $subtotal; ?></span></div>
      <div class="row"><span>Shipping</span><span>$<?php echo $shipping;?></span></div>
      <div class="row total"><span>Total</span><span>$<?php echo $total;?></span></div>
      <a href="checkout.php" class="btn" style="width:100%;text-align:center;margin-top:1rem">Proceed to Checkout</a>
    </div> 
  </main>
  <footer>&copy; 2026 ShopLite. All rights reserved.</footer>
</body>
</html>

