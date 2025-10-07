<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Welcome - Employee Management System</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
  :root{
    --brand: #696cff;
    --brand-hover:#575bff;
    --text:#0b1533;
    --panel-bg: rgba(255,255,255,.92);
    --panel-border: rgba(255,255,255,.7);
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

  .welcome-container{
    max-width: 600px;
    width: 100%;
    text-align: center;
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

  h1{
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 16px;
    text-shadow: 0 2px 12px rgba(0,0,0,.1);
  }

  .subtitle{
    font-size: 1.15rem;
    color: var(--text);
    opacity: 0.85;
    margin-bottom: 12px;
    font-weight: 500;
  }

  .description{
    font-size: 0.95rem;
    color: var(--text);
    opacity: 0.7;
    margin-bottom: 40px;
    line-height: 1.6;
  }

  .panel{
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 24px;
    padding: 48px 40px;
    box-shadow: 0 24px 48px rgba(0,0,0,.18);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
  }

  .btn-enter{
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 18px 48px;
    font-size: 1.1rem;
    font-weight: 700;
    text-decoration: none;
    border-radius: 14px;
    border: none;
    background: var(--brand);
    color: #fff;
    box-shadow: 0 12px 30px rgba(105,108,255,.45);
    transition: all 0.3s ease;
    cursor: pointer;
  }

  .btn-enter:hover{
    background: var(--brand-hover);
    transform: translateY(-2px);
    box-shadow: 0 16px 36px rgba(105,108,255,.55);
  }

  .btn-enter:active{
    transform: translateY(0);
  }

  .arrow{
    font-size: 1.3rem;
    transition: transform 0.3s ease;
  }

  .btn-enter:hover .arrow{
    transform: translateX(4px);
  }

  .features{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 16px;
    margin-top: 32px;
    padding-top: 32px;
    border-top: 1px solid rgba(105,108,255,.2);
  }

  .feature{
    text-align: center;
  }

  .feature-icon{
    font-size: 1.8rem;
    color: var(--brand);
    margin-bottom: 8px;
  }

  .feature-text{
    font-size: 0.85rem;
    color: var(--text);
    opacity: 0.75;
    font-weight: 500;
  }

  @media (max-width: 640px){
    h1{ font-size: 2rem; }
    .panel{ padding: 36px 2px; }
    .btn-enter{ padding: 16px 36px; font-size: 1rem; }
    .features{ grid-template-columns: 1fr 1fr; }
  }
</style>
</head>

<body>
  <div class="welcome-container">
    <div class="panel">
      <div class="logo">📊</div>
      
      <h1>Welcome to the <br/>Employee Management System</h1>
      <p class="description">
        LBYCPG2 | EQ3  <br/> Lascano, Quiazon, Manganaan</p>
        
      <a href="menu.php" class="btn-enter" role="button">
        <span>Enter Dashboard</span>
        <span class="arrow">→</span>
      </a>

    </div>
  </div>
</body>
</html>