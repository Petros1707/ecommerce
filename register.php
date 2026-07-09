
<?php  
session_start();
include_once 'dbconnect.php';

$error ="";
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $password1 = trim($_POST['password1']);

  $hashespwd = password_hash($password, PASSWORD_DEFAULT);

  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //echo "valid email";
  }else{
    echo $error = "invalid email";
  }


  if ($password != $password1) {
    echo $error = "passwords must match";
  }else{

    $stmt = $conn->prepare("SELECT * FROM  users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();


    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      echo $msg ="user already exist";
      header("refresh: 3; url=register.php");
      exit();
    }else{
      $stmt = $conn->prepare("INSERT INTO users(name, email, username, hashedpwd) VALUES(?,?,?,?)");
      $stmt->bind_param("ssss", $name, $email, $username, $hashespwd);
     


      if ($stmt->execute()) {
        echo $msg = "user registered";
        header("Refresh: 3; url=login.php");
        exit();
      }else{
        echo "error: ";
      }

      //close
      $stmt->close();
      $conn->close();
    }
  }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShopLite - Register</title>
  <style>
    :root{--primary:#2563eb;--bg:#f8fafc;--surface:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--radius:8px;--shadow:0 2px 8px rgba(0,0,0,0.05)}
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);line-height:1.6}
    header{background:var(--surface);border-bottom:1px solid var(--border);padding:1rem 5%;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:100}
    nav a{margin-left:1.5rem;text-decoration:none;color:var(--text);font-weight:500}
    nav a:hover{color:var(--primary)}
    main{max-width:400px;margin:3rem auto;padding:0 5%;min-height:60vh}
    .btn{display:inline-block;background:var(--primary);color:#fff;padding:0.6rem 1.2rem;border-radius:var(--radius);text-decoration:none;border:none;cursor:pointer;font-weight:500;width:100%;text-align:center}
    .btn:hover{background:#1d4ed8}
    .form-group{margin-bottom:1rem}
    .form-group label{display:block;margin-bottom:0.3rem;font-weight:500}
    .form-group input{width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:var(--radius)}
    .card{background:var(--surface);padding:2rem;border-radius:var(--radius);box-shadow:var(--shadow)}
    .link{display:block;text-align:center;margin-top:1rem;color:var(--primary);text-decoration:none}
    footer{text-align:center;padding:2rem;background:var(--surface);border-top:1px solid var(--border);margin-top:2rem}
    @media(max-width:768px){header{flex-direction:column;gap:1rem}nav{display:flex;flex-wrap:wrap;gap:0.5rem}nav a{margin:0}}
  </style>
</head>
<body>
  <header>
    <h2>🛍️ ShopLite</h2>
    <nav>
      <a href="index.php">Home</a>
      <a href="products.php">Products</a>
      <a href="cart.php">Cart</a>
      <a href="login.php">Login</a>
    </nav>
  </header>
  <main>
    <div class="card">
      <h2 style="text-align:center;margin-bottom:1.5rem">Create Account</h2>
      <form method="POST" action=<?php echo $_SERVER['PHP_SELF']; ?>>
        <div class="form-group"><label>Full Name</label><input type="text"  name = "name" required></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
        <div class="form-group"><label>User Name</label><input type="text"  name = "username" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
        <div class="form-group"><label>Confirm Password</label><input type="password" name="password1" required></div>
        <button type="submit" name="Register" class="btn">Register</button>
      </form>
      <a href="login.php" class="link">Already have an account? Login</a>
    </div>
  </main>
  <footer>&copy; 2026 ShopLite. All rights reserved.</footer>
</body>
</html>

