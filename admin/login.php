<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Admin Login - Employee Management System</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
  :root{
    --brand: #696cff;
    --brand-hover:#575bff;
    --text:#0b1533;
    --panel-bg: rgba(255,255,255,.92);
    --panel-border: rgba(255,255,255,.7);
    --input-bg: rgba(255,255,255,.6);
    --input-border: rgba(105,108,255,.3);
  }

  body::before{
    content:"";
    position: fixed; inset: 0; z-index: -2; pointer-events: none;
    background-image: url("https://media0.giphy.com/media/v1.Y2lkPTc5MGI3NjExOTFuams3b3M0dWZuejFlNnY3Y2ltenhpcnJzYmh3ZHQ4cWhkcHF2OSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/dAWZiSMbMvObDWP3aA/giphy.gif");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    filter: brightness(.95) contrast(.98) saturate(1.05);
  }
  
  body::after{
    content:"";
    position: fixed; inset: 0; z-index: -1; pointer-events: none;
    background: linear-gradient(to bottom, rgba(105,108,255,.14), rgba(105,108,255,.22));
  }
  
  @media (prefers-reduced-motion: reduce){
    body::before{ background-image: none; background: #e9ecff; filter:none; }
  }

  *{ box-sizing:border-box; margin:0; padding:0; }
  
  body{
    font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
    color: var(--text);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  .login-container{
    max-width: 480px;
    width: 100%;
  }

  .logo{
    width: 80px;
    height: 80px;
    margin: 0 auto 24px;
    background: var(--brand);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    box-shadow: 0 12px 32px rgba(105,108,255,.35);
    animation: float 3s ease-in-out infinite;
  }

  @keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
  }

  .panel{
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 24px;
    padding: 48px 40px;
    box-shadow: 0 24px 48px rgba(0,0,0,.18);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    text-align: center;
  }

  h1{
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 12px;
    text-shadow: 0 2px 12px rgba(0,0,0,.1);
  }

  .subtitle{
    font-size: 0.95rem;
    color: var(--text);
    opacity: 0.7;
    margin-bottom: 36px;
    line-height: 1.6;
  }

  .form-group{
    margin-bottom: 24px;
    text-align: left;
  }

  label{
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 8px;
    opacity: 0.85;
  }

  input[type="text"],
  input[type="password"]{
    width: 100%;
    padding: 14px 16px;
    font-size: 1rem;
    font-family: inherit;
    border: 2px solid var(--input-border);
    border-radius: 12px;
    background: var(--input-bg);
    color: var(--text);
    transition: all 0.3s ease;
    backdrop-filter: blur(8px);
  }

  input[type="text"]:focus,
  input[type="password"]:focus{
    outline: none;
    border-color: var(--brand);
    background: rgba(255,255,255,.8);
    box-shadow: 0 4px 16px rgba(105,108,255,.2);
  }

  input[type="text"]::placeholder,
  input[type="password"]::placeholder{
    color: var(--text);
    opacity: 0.5;
  }

  .btn-login{
    width: 100%;
    padding: 16px;
    font-size: 1.05rem;
    font-weight: 700;
    text-decoration: none;
    border-radius: 12px;
    border: none;
    background: var(--brand);
    color: #fff;
    box-shadow: 0 12px 30px rgba(105,108,255,.45);
    transition: all 0.3s ease;
    cursor: pointer;
    margin-top: 8px;
  }

  .btn-login:hover{
    background: var(--brand-hover);
    transform: translateY(-2px);
    box-shadow: 0 16px 36px rgba(105,108,255,.55);
  }

  .btn-login:active{
    transform: translateY(0);
  }

  .back-link{
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid rgba(105,108,255,.2);
    font-size: 0.9rem;
    color: var(--brand);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .back-link:hover{
    gap: 10px;
    opacity: 0.8;
  }

  .arrow-left{
    font-size: 1.1rem;
    transition: transform 0.3s ease;
  }

  .back-link:hover .arrow-left{
    transform: translateX(-4px);
  }

  .error-message{
    background: rgba(255,59,48,.1);
    border: 1px solid rgba(255,59,48,.3);
    color: #d32f2f;
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 0.9rem;
    margin-bottom: 20px;
    display: none;
  }

  .error-message.show{
    display: block;
  }

  @media (max-width: 640px){
    h1{ font-size: 1.75rem; }
    .panel{ padding: 36px 28px; }
    .btn-login{ padding: 14px; font-size: 1rem; }
  }
</style>
</head>

<body>
  <div class="login-container">
    <div class="panel">
      <div class="logo">📊</div>
      
      <h1>Admin Login</h1>
      <p class="subtitle">
        Enter your credentials to access the system
      </p>

      <div class="error-message" id="errorMessage">
        Invalid username or password
      </div>

      <form id="loginForm" action="menu.php" method="POST">
        <div class="form-group">
          <label for="username">Username</label>
          <input 
            type="text" 
            id="username" 
            name="username" 
            placeholder="Enter your username"
            required
            autocomplete="username"
          />
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            placeholder="Enter your password"
            required
            autocomplete="current-password"
          />
        </div>

        <button type="submit" class="btn-login">
          Login
        </button>
      </form>

      <a href="starting.php" class="back-link">
        <span class="arrow-left">←</span>
        <span>Back to Welcome</span>
      </a>
    </div>
  </div>

  <script>
    const form = document.getElementById('loginForm');
    const errorMessage = document.getElementById('errorMessage');

    form.addEventListener('submit', function(e) {
      // Remove the preventDefault for actual form submission
      // e.preventDefault();
      
      // You can add client-side validation here if needed
      const username = document.getElementById('username').value;
      const password = document.getElementById('password').value;

      if (!username || !password) {
        e.preventDefault();
        errorMessage.textContent = 'Please fill in all fields';
        errorMessage.classList.add('show');
      }
    });

    // Hide error message when user starts typing
    document.getElementById('username').addEventListener('input', function() {
      errorMessage.classList.remove('show');
    });

    document.getElementById('password').addEventListener('input', function() {
      errorMessage.classList.remove('show');
    });
  </script>
</body>
</html>