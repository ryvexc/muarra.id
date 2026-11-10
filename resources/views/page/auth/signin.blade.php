<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  
  <!-- Asset bawaan Laravel -->
  <link rel="stylesheet" href="{{ asset('assets/css/muarra-dark.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/core.css') }}">
  
  <!-- Font Awesome & Google Fonts -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <title>Sign In</title>

  <style>
    :root {
      --bg: #0f1115;
      --surface: #171a21;
      --surface-2: #1c2028;
      --surface-hover: #232834;
      --surface-hover-2: #2d3443;
      --row-hover: #1c212b;

      --primary: #3b82f6;
      --primary-hover: #2563eb;
      --primary-dim: rgba(59, 130, 246, 0.12);

      --text-primary: #fff;
      --text-secondary: #8b95a5;
      --text-tertiary: #5b6577;

      --border: rgba(255, 255, 255, 0.06);

      --success: #4ade80;
      --success-bg: rgba(34, 197, 94, 0.1);

      --warning: #fbbf24;
      --warning-bg: rgba(245, 158, 11, 0.1);

      --danger: #f87171;
      --danger-bg: rgba(239, 68, 68, 0.1);

      --purple: #c084fc;
      --purple-bg: rgba(168, 85, 247, 0.1);

      --journal: #11141a;
      --mono: "IBM Plex Mono", monospace;
    }

    body {
      background-color: var(--bg);
      color: var(--text-primary);
      font-family: 'Inter', sans-serif;
    }

    .font-mono-custom {
      font-family: var(--mono);
    }

    .custom-card {
      background-color: var(--surface);
      border: 1px solid var(--border);
      box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
    }

    .input-custom {
      background-color: var(--surface-2);
      border: 1px solid var(--border);
      color: var(--text-primary);
      transition: all 0.2s ease-in-out;
    }

    .input-custom::placeholder {
      color: var(--text-tertiary);
    }

    .input-custom:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--primary-dim);
      background-color: var(--surface-hover);
    }

    .btn-primary {
      background-color: var(--primary);
      color: #ffffff;
      transition: all 0.2s ease-in-out;
    }

    .btn-primary:hover {
      background-color: var(--primary-hover);
      box-shadow: 0 4px 14px 0 var(--primary-dim);
    }

    .btn-social {
      background-color: var(--surface-2);
      border: 1px solid var(--border);
      color: var(--text-primary);
      transition: all 0.2s ease-in-out;
    }

    .btn-social:hover {
      background-color: var(--surface-hover);
      border-color: rgba(255, 255, 255, 0.12);
    }

    /* Custom Checkbox */
    .custom-checkbox {
      appearance: none;
      background-color: var(--surface-2);
      border: 1px solid var(--border);
      width: 1.125rem;
      height: 1.125rem;
      border-radius: 0.375rem;
      cursor: pointer;
      display: grid;
      place-content: center;
      transition: all 0.2s ease;
    }

    .custom-checkbox::before {
      content: "";
      width: 0.65em;
      height: 0.65em;
      transform: scale(0);
      transition: 120ms transform ease-in-out;
      box-shadow: inset 1em 1em var(--text-primary);
      transform-origin: center;
      clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
    }

    .custom-checkbox:checked {
      background-color: var(--primary);
      border-color: var(--primary);
    }

    .custom-checkbox:checked::before {
      transform: scale(1);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden">
  <!-- Sign In Card Container -->
  <div class="w-full max-w-md custom-card rounded-2xl p-8 relative z-10 my-8">
    
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-2xl font-bold tracking-tight mb-2" style="color: var(--text-primary);">Welcome Back</h1>
      <p class="text-sm" style="color: var(--text-secondary);">Please enter your details to sign in</p>
    </div>

    <!-- Form -->
    <form action="{{ route('login') }}" method="POST" class="space-y-5">
      @csrf

      <!-- Email Field -->
      <div>
        <label for="email" class="block text-xs font-medium uppercase tracking-wider mb-2" style="color: var(--text-secondary);">
          Email Address
        </label>
        <div class="relative">
          <input 
            type="email" 
            name="email" 
            id="email" 
            required 
            placeholder="name@example.com"
            class="w-full px-4 py-3 rounded-xl input-custom text-sm pl-11"
          >
          <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" style="color: var(--text-tertiary);">
            <i class="fa-regular fa-envelope"></i>
          </div>
        </div>
      </div>

      <!-- Password Field -->
      <div>
        <div class="flex items-center justify-between mb-2">
          <label for="password" class="block text-xs font-medium uppercase tracking-wider" style="color: var(--text-secondary);">
            Password
          </label>
          <a href="#" class="text-xs font-medium hover:underline transition-colors" style="color: var(--primary);">
            Forgot password?
          </a>
        </div>
        <div class="relative">
          <input 
            type="password" 
            name="password" 
            id="password" 
            required 
            placeholder="••••••••"
            class="w-full px-4 py-3 rounded-xl input-custom text-sm pl-11 pr-11"
          >
          <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" style="color: var(--text-tertiary);">
            <i class="fa-regular fa-lock"></i>
          </div>
          <!-- Toggle Password Visibility -->
          <button 
            type="button" 
            id="togglePassword"
            class="absolute right-4 top-1/2 -translate-y-1/2 text-sm focus:outline-none transition-colors"
            style="color: var(--text-tertiary);"
            title="Toggle password visibility"
          >
            <i class="fa-regular fa-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>

      <!-- Remember Me & Checkbox -->
      <div class="flex items-center">
        <label class="flex items-center gap-3 cursor-pointer select-none">
          <input type="checkbox" name="remember" class="custom-checkbox">
          <span class="text-xs font-medium" style="color: var(--text-secondary);">Remember me for 30 days</span>
        </label>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="w-full py-3.5 px-4 rounded-xl font-semibold text-sm btn-primary flex items-center justify-center gap-2">
        <span>Sign In</span>
        <i class="fa-solid fa-arrow-right text-xs"></i>
      </button>

      <!-- Divider -->
      <div class="relative my-6 text-center">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full" style="border-top: 1px solid var(--border);"></div>
        </div>
        <span class="relative px-4 text-xs font-mono-custom uppercase tracking-wider" style="background-color: var(--surface); color: var(--text-tertiary);">
          OR CONTINUE WITH
        </span>
      </div>

      <!-- Social Logins -->
      <div class="grid grid-cols-2 gap-3">
        <button type="button" class="py-2.5 px-4 rounded-xl text-xs font-medium btn-social flex items-center justify-center gap-2">
          <i class="fa-brands fa-google text-sm"></i>
          <span>Google</span>
        </button>
        <button type="button" class="py-2.5 px-4 rounded-xl text-xs font-medium btn-social flex items-center justify-center gap-2">
          <i class="fa-brands fa-github text-sm"></i>
          <span>GitHub</span>
        </button>
      </div>

    </form>

    <!-- Footer link -->
    <div class="mt-8 text-center text-xs" style="color: var(--text-secondary);">
      Don't have an account? 
      <a href="#" class="font-semibold hover:underline" style="color: var(--primary);">Sign up now</a>
    </div>

  </div>

  <!-- JavaScript Toggle Password -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toggleBtn = document.getElementById('togglePassword');
      const passwordInput = document.getElementById('password');
      const eyeIcon = document.getElementById('eyeIcon');

      if (toggleBtn && passwordInput && eyeIcon) {
        toggleBtn.addEventListener('click', function() {
          const isPassword = passwordInput.getAttribute('type') === 'password';
          passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
          
          eyeIcon.classList.toggle('fa-eye', !isPassword);
          eyeIcon.classList.toggle('fa-eye-slash', isPassword);
        });
      }
    });
  </script>
</body>
</html>