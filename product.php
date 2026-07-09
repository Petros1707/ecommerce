<?php  
session_start();
include_once 'dbconnect.php';

//CHECK LOGIN
if (!isset($_SESSION['user_id'])) {
    echo "Login required";
    header("Refresh: 2; url=login.php");
    exit();
}
//cart count
include_once 'cart_count.php';

//ADD TO CART
if (isset($_POST['add'])) {

    $image = $_POST['image'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    // Check if item already exists in cart
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE name = ? AND user_id = ?");
    $stmt->bind_param("si", $name, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        // UPDATE quantity
        $newQty = $row['quantity'] + $quantity;

        $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update->bind_param("ii", $newQty, $row['id']);
        $update->execute();

    } else {

        // INSERT new item
        $insert = $conn->prepare("INSERT INTO cart (user_id, image, name, price, quantity) VALUES (?,?,?,?,?)");
        $insert->bind_param("issii", $user_id, $image, $name, $price, $quantity);
        $insert->execute();
    }

    header("Refresh: 2; url=cart.php");
    exit();
}

//GET PRODUCT

if (!isset($_GET['id'])) {
    die("Product ID missing");
}

$stmt = $conn->prepare("SELECT id, name, image, price, info FROM products WHERE id = ?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();

if (!($row = $result->fetch_assoc())) {
    die("Product not found");
}

$id = $row['id'];
$name = $row['name'];
$image = $row['image'];
$price = $row['price'];
$info = $row['info'];
?>

 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShopLite - Product</title>
  <style>
    :root {
      --primary: #2563eb;
      --bg: #f8fafc;
      --surface: #fff;
      --text: #0f172a;
      --muted: #64748b;
      --border: #e2e8f0;
      --radius: 8px;
      --shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: system-ui, -apple-system, sans-serif;
      background: var(--bg);
      color: var(--text);
      line-height: 1.6;
    }

    /* 🔹 HEADER & NAV */
    header {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      padding: 0.8rem 5%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 100;
      gap: 1rem;
      flex-wrap: wrap;
    }
    nav { display: flex; align-items: center; gap: 1.5rem; }
    nav a { text-decoration: none; color: var(--text); font-weight: 500; }
    nav a:hover { color: var(--primary); }

    /* 🔹 USER PROFILE (STATIC) */
    .user-profile {
      display: flex;
      align-items: center;
      gap: 0.8rem;
      background: var(--bg);
      padding: 0.4rem 0.8rem;
      border-radius: 50px;
      border: 1px solid var(--border);
    }
    .avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      overflow: hidden;
      flex-shrink: 0;
    }
    .avatar img { width: 100%; height: 100%; object-fit: cover; }
    .user-info { display: flex; flex-direction: column; line-height: 1.2; }
    .user-info .name { font-weight: 600; font-size: 0.85rem; color: var(--text); }
    .user-info .email { font-size: 0.7rem; color: var(--muted); }

    /* 🔹 MAIN CONTENT */
    main { max-width: 1000px; margin: 2rem auto; padding: 0 5%; min-height: 60vh; }
    .product {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
      background: var(--surface);
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }
    .product img { max-width: 100%; border-radius: var(--radius); }
    .details h1 { margin-bottom: 0.5rem; }
    .price { font-size: 1.5rem; font-weight: 700; color: var(--primary); margin: 1rem 0; }
    .controls { display: flex; gap: 1rem; margin: 1.5rem 0; align-items: center; }
    .controls input { width: 60px; text-align: center; padding: 0.5rem; border: 1px solid var(--border); border-radius: var(--radius); }
    .btn {
      display: inline-block;
      background: var(--primary);
      color: #fff;
      padding: 0.6rem 1.2rem;
      border-radius: var(--radius);
      text-decoration: none;
      border: none;
      cursor: pointer;
      font-weight: 500;
    }
    .btn:hover { background: #1d4ed8; }

    /* 🔹 FOOTER */
    footer { text-align: center; padding: 2rem; background: var(--surface); border-top: 1px solid var(--border); margin-top: 2rem; }

    /* 🔹 RESPONSIVE */
    @media(max-width: 768px) {
      header { flex-direction: column; align-items: stretch; gap: 0.8rem; }
      nav { flex-wrap: wrap; gap: 0.5rem; justify-content: center; }
      nav a { margin: 0; }
      .user-profile { align-self: flex-end; padding: 0.3rem 0.5rem; }
      .user-info { display: none; } /* Hide name/email on mobile, keep avatar */
      .product { grid-template-columns: 1fr; }
    }
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
    <div class="user-profile">
      <div class="avatar">
        <img src="https://placehold.co/40x40/2563eb/ffffff?text=JD" alt="User Avatar">
      </div>
      <div class="user-info">
        <span class="name"><?php echo $_SESSION['user_id'];?></span>
        <span class="name"><?php echo $_SESSION['user_name'];?></span>
        <span class="email"><?php echo $_SESSION['user_email'];?></span>
      </div>
    </div>
  </header>

  <main>
    <div class="product">
      <?php   
      include_once 'dbconnect.php';
      $stmt= $conn->prepare("SELECT id, name, image, price, info, category_id FROM products WHERE id = ?");
      $stmt->bind_param('i', $_GET['id']);
      $stmt->execute();


      $result = $stmt->get_result();

      while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $name = $row['name'];
        $image = $row['image'];
        $price = $row['price'];
        $info = $row['info'];
      



      ?>
      <img src="image/<?php echo $image;?>">

<div class="details">
    <h1><?php echo $name; ?></h1>
    <div class="price">$<?php echo $price; ?></div>
    <p><?php echo $info; ?></p>

    <form method="POST">
        <input type="hidden" name="name" value="<?php echo $name; ?>">
        <input type="hidden" name="price" value="<?php echo $price; ?>">
        <input type="hidden" name="image" value="<?php echo $image; ?>">

        <label>Qty:</label>
        <input type="number" name="quantity" value="1" min="1" max="10">

        <button class="btn" type="submit" name="add">Add to Cart</button>
        <?php } ?>
    </form>
</div>

  </main>
  <footer>&copy; 2026 ShopLite. All rights reserved.</footer>
</body>
</html>