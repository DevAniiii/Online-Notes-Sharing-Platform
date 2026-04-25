<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PasteNotes - Modern Code Sharing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/notes-platform/assets/css/styles.css">
    <style>
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(34, 211, 238, 0.5); }
            50% { box-shadow: 0 0 30px rgba(34, 211, 238, 0.8); }
        }
        
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        .glassmorphism {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(148, 163, 184, 0.2);
        }
        
        .btn-modern {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .btn-modern::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-modern:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(34, 211, 238, 0.2);
        }
        
        input:focus, textarea:focus, select:focus {
            outline: none;
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.5);
            border-color: #06b6d4;
        }
        
        .navbar {
            animation: slideInDown 0.6s ease;
        }
        
        .glow-text {
            color: #06b6d4;
            text-shadow: 0 0 20px rgba(6, 182, 212, 0.5);
        }
        
        .success-toast {
            animation: slideInUp 0.4s ease;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
</head>

<body class="text-white min-h-screen flex flex-col">

<!-- Modern Navbar -->
<nav class="navbar sticky top-0 glassmorphism border-b border-cyan-500/20 py-4 px-6 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="text-2xl font-bold">
                <span class="glow-text">
                    <i class="fas fa-code"></i> PasteNotes
                </span>
            </div>
        </div>
        
        <div class="flex items-center gap-6">
            <a href="/notes-platform/index.php" class="btn-modern group relative px-4 py-2 rounded-lg hover:bg-cyan-500/10 flex items-center gap-2">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="/notes-platform/pages/create.php" class="btn-modern group relative px-4 py-2 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 font-semibold flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Create</span>
            </a>
        </div>
    </div>
</nav>

<div class="flex-grow">&nbsp;