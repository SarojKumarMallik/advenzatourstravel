<?php
require_once '../config/db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - advenzatourstravel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Outfit', sans-serif; }

        /* Subtle animated background */
        body {
            background: linear-gradient(135deg, #f0f4f8 0%, #e8f0fe 50%, #f0f4f8 100%);
            background-size: 400% 400%;
            animation: bgShift 12s ease infinite;
        }
        @keyframes bgShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Form input - clean with highlighted focus */
        .form-input {
            width: 100%;
            padding: 14px 48px 14px 48px;
            border-radius: 12px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            font-size: 0.9rem;
            font-weight: 500;
            color: #1e293b;
            transition: all 0.25s ease;
            outline: none;
        }
        .form-input::placeholder { color: #94a3b8; font-weight: 400; }
        .form-input:hover { border-color: #cbd5e1; background: #fff; }
        .form-input:focus {
            background: #fff;
            border-color: #1171b9;
            box-shadow: 0 0 0 4px rgba(17, 113, 185, 0.12), 0 2px 8px rgba(17, 113, 185, 0.08);
        }

        /* Input wrapper for icon */
        .input-wrapper { position: relative; }
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            transition: color 0.25s;
            pointer-events: none;
        }
        .input-wrapper:focus-within .input-icon { color: #1171b9; }

        .input-right {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }

        /* Sign in button */
        .btn-signin {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #1171b9 0%, #0e5d9a 100%);
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(17, 113, 185, 0.35);
        }
        .btn-signin:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(17, 113, 185, 0.45);
        }
        .btn-signin:active { transform: translateY(0); }
        .btn-signin::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s ease;
        }
        .btn-signin:hover::before { left: 100%; }

        /* Card */
        .login-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.9);
            border-radius: 28px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1), 0 4px 20px rgba(17,113,185,0.08);
        }

        /* Fade in */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in   { animation: fadeInUp 0.5s ease both; }
        .delay-1   { animation-delay: 0.1s; }
        .delay-2   { animation-delay: 0.2s; }
        .delay-3   { animation-delay: 0.3s; }

        /* Error */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60%  { transform: translateX(-5px); }
            40%, 80%  { transform: translateX(5px); }
        }
        .shake { animation: shake 0.4s ease; }

        /* Label */
        .form-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 8px;
        }

        /* Badge */
        .secure-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1171b9;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 999px;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md fade-in">

        <!-- Logo + Heading -->
        <div class="text-center mb-8 fade-in">
            <img src="../img/logo.webp" alt="Logo" class="h-16 object-contain mx-auto mb-5 drop-shadow-md">
            <h1 class="font-display font-extrabold text-gray-900 text-3xl tracking-tight">Admin Panel</h1>
            <p class="text-gray-500 mt-1.5 text-sm font-medium">Sign in to manage your platform</p>
         
        </div>

        <!-- Card -->
        <div class="login-card px-8 pt-8 pb-8 fade-in delay-1">

            <!-- Error Alert -->
            <?php if ($error): ?>
            <div class="shake flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3.5 rounded-xl text-sm font-semibold mb-6">
                <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-5">

                <!-- Email -->
                <div class="fade-in delay-2">
                    <label class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </span>
                        <input
                            type="email"
                            name="email"
                            required
                            placeholder="admin@iqh.com"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            class="form-input"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="fade-in delay-2">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input
                            type="password"
                            name="password"
                            id="password-input"
                            required
                            placeholder="••••••••••"
                            class="form-input"
                            style="padding-right: 48px;"
                        >
                        <button type="button" onclick="togglePassword()" class="input-right text-gray-400 hover:text-[#1171b9] transition-colors">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-2 fade-in delay-3">
                    <button type="submit" class="btn-signin">Sign In</button>
                </div>
            </form>

            <!-- Divider -->
            <!-- <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-gray-100"></div>
                <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Demo</span>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div> -->

            <!-- Demo Credentials -->
            <!-- <div class="bg-gray-50 rounded-xl px-5 py-3.5 border border-gray-100 fade-in delay-3">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Demo Credentials</p>
                </div>
                <p class="text-xs font-mono text-gray-500">Email: <span class="text-gray-700 font-semibold">admin@travelbee.com</span></p>
                <p class="text-xs font-mono text-gray-500 mt-1">Pass: <span class="text-gray-700 font-semibold">password</span></p>
            </div> -->
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-gray-400 mt-6 fade-in delay-3">
            &copy; <?php echo date('Y'); ?> Shree Manyata Hospital &mdash; All rights reserved.
        </p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password-input');
            const icon  = document.getElementById('eye-icon');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.innerHTML = isHidden
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    </script>
</body>
</html>
