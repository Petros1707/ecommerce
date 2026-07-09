<!-- 
<?php  
session_start();
include_once 'dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
//cart count
include_once 'cart_count.php';



 ?>

 --><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShopLite - Home</title>
  <style>
    :root{--primary:#2563eb;--bg:#f8fafc;--surface:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--radius:8px;--shadow:0 2px 8px rgba(0,0,0,0.05)}
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);line-height:1.6}
    header{background:var(--surface);border-bottom:1px solid var(--border);padding:0.8rem 5%;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:100;gap:1rem;flex-wrap:wrap}
    nav a{margin-left:1.5rem;text-decoration:none;color:var(--text);font-weight:500}
    nav a:hover{color:var(--primary)}
    main{max-width:1200px;margin:2rem auto;padding:0 5%;min-height:60vh}
    .btn{display:inline-block;background:var(--primary);color:#fff;padding:0.6rem 1.2rem;border-radius:var(--radius);text-decoration:none;border:none;cursor:pointer;font-weight:500}
    .btn:hover{background:#1d4ed8}
    footer{text-align:center;padding:2rem;background:var(--surface);border-top:1px solid var(--border);margin-top:2rem}
    .hero{background:linear-gradient(135deg,#1e293b,#0f172a);color:#fff;padding:4rem 5%;border-radius:var(--radius);text-align:center;margin-bottom:2rem}
    .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5rem}
    .card{background:var(--surface);padding:1.5rem;border-radius:var(--radius);box-shadow:var(--shadow);text-align:center}
    .card img{max-width:100%;border-radius:var(--radius);margin-bottom:1rem}
    .price{font-weight:700;color:var(--primary);margin:0.5rem 0}

    /* 👤 USER PROFILE STYLES */
    .auth-section { display: flex; align-items: center; gap: 1rem; }
    .user-profile { display: flex; align-items: center; gap: 0.8rem; background: var(--bg); padding: 0.4rem 0.8rem; border-radius: 50px; cursor: pointer; transition: 0.2s; border: 1px solid var(--border); }
    .user-profile:hover { box-shadow: var(--shadow); }
    .avatar { width: 40px; height: 40px; border-radius: 50%; overflow: hidden; flex-shrink: 0; }
    .avatar img { width: 100%; height: 100%; object-fit: cover; }
    .user-info { display: flex; flex-direction: column; line-height: 1.2; }
    .user-info .name { font-weight: 600; font-size: 0.85rem; color: var(--text); }
    .user-info .email { font-size: 0.7rem; color: var(--muted); }
    .logout-btn { background: none; border: none; color: #ef4444; font-size: 0.75rem; cursor: pointer; text-decoration: underline; margin-left: 0.5rem; padding: 0; }
    .logout-btn:hover { color: #dc2626; }
    .login-btn { background: var(--primary); color: #fff; padding: 0.5rem 1rem; border-radius: var(--radius); text-decoration: none; font-weight: 500; font-size: 0.9rem; }
    .login-btn:hover { background: #1d4ed8; }
    .hidden { display: none !important; }

    @media(max-width:768px){
      header{flex-direction:column;align-items:stretch;gap:0.8rem}
      nav{display:flex;flex-wrap:wrap;gap:0.5rem}
      nav a{margin:0}
      .auth-section{justify-content:flex-end}
      .user-info{display:none} /* Hide name/email on mobile, keep avatar */
      .user-profile{padding:0.3rem 0.5rem}
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
   <div class="auth-section">
  <?php if (isset($_SESSION['user_id'])): ?>
    <!-- ✅ Logged In: Show Profile -->
    <div class="user-profile">
      <div class="avatar">
        <img src="https://placehold.co/40x40/2563eb/ffffff?" alt="User Avatar">
      </div>
      <div class="user-info">
        <span class="name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        <span class="email"><?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
      </div>
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
  <?php else: ?>
    <!-- 🔑 Logged Out: Show Login Button -->
    <a href="login.php" class="login-btn">Login</a>
  <?php endif; ?>
</div>
  </header>

  <main>
    <section class="hero">
      <h1>Discover What You'll Love</h1>
      <p style="margin:1rem auto 1.5rem;max-width:600px;opacity:0.9">Quality products, fast delivery, and secure checkout. Start shopping today.</p>
      <a href="products.php" class="btn">Browse Catalog</a>
    </section>
    <h2 style="margin-bottom:1rem">Featured Products</h2>
    <div class="grid">
      <?php  
      include_once 'dbconnect.php';

      $stmt=$conn->prepare("SELECT id, name, image, price FROM products LIMIT 5");
      $stmt->execute();

      $result= $stmt->get_result();
       
       while ($row= $result->fetch_assoc()) {
         $id = $row['id'];
         $name = $row['name'];
         $image = $row['image'];
         $price = $row['price'];
       
      
      
      ?>
      <div class="card">
        <img src="image/<?php echo $image; ?>">
        <h3><?php echo $name;?></h3>
        <p class="price">$<?php echo $price;?></p>
        <a href="product.php?id=<?php echo $id;?>" class="btn">View</a>
      </div>
       <?php } ?>
    </div>
  </main>
  <footer>&copy; 2026 ShopLite. All rights reserved.</footer>

  <script>
    // 🔁 Change this to `true` to show logged-in state, `false` for login button
    let isLoggedIn = true;

    function toggleAuth(state) {
      isLoggedIn = state;
      document.getElementById('loggedInState').classList.toggle('hidden', !isLoggedIn);
      document.getElementById('loggedOutState').classList.toggle('hidden', isLoggedIn);
      if (!isLoggedIn) alert('Logged out! (Demo)');
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => toggleAuth(isLoggedIn));
  </script>
</body>
</html>