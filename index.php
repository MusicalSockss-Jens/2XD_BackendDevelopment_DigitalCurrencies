<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>XD Wallet Preview</title>
    <style>
      body {
        background-color: #0f172a;
        color: #f8fafc;
        font-family:
          system-ui,
          -apple-system,
          sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
      }
      .card {
        background-color: #1e293b;
        border: 1px solid #334155;
        padding: 2rem;
        border-radius: 12px;
        width: 320px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      }
      h1 {
        font-size: 1.25rem;
        color: #38bdf8;
        margin-top: 0;
      }
      .balance {
        font-size: 2.5rem;
        font-weight: bold;
        margin: 1rem 0;
      }
      .token {
        color: #38bdf8;
        font-size: 1rem;
      }
    </style>
  </head>
  <body>
    <div class="card">
      <h1>XD Wallet</h1>
      <p style="color: #94a3b8; margin: 0">
        Welkom, <?= htmlspecialchars($user['username']) ?>
      </p>
      <p style="color: #94a3b8; margin-top: 0.5rem; margin-bottom: 0">
        Current Balance
      </p>
      <div class="balance">
        <?= htmlspecialchars($user['balance']) ?>
        <span class="token">XD</span>
      </div>
      <p style="color: #4ade80; font-size: 0.875rem; margin: 0">
        ✓ Account Active
      </p>
    </div>
  </body>
</html>
