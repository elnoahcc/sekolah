<?php
session_start();

// If confirmed logout is requested
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    // Destroy all session data
    $_SESSION = [];
    session_destroy();
    
    // Prevent back button access by setting no-cache headers
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.
    
    // Redirect to index.php after logout
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Logout</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated background particles */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float 15s infinite linear;
        }

        .particle:nth-child(1) { left: 10%; animation-delay: 0s; top: -10px; }
        .particle:nth-child(2) { left: 20%; animation-delay: 2s; top: -10px; }
        .particle:nth-child(3) { left: 30%; animation-delay: 4s; top: -10px; }
        .particle:nth-child(4) { left: 40%; animation-delay: 6s; top: -10px; }
        .particle:nth-child(5) { left: 50%; animation-delay: 8s; top: -10px; }
        .particle:nth-child(6) { left: 60%; animation-delay: 10s; top: -10px; }
        .particle:nth-child(7) { left: 70%; animation-delay: 12s; top: -10px; }
        .particle:nth-child(8) { left: 80%; animation-delay: 14s; top: -10px; }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.3;
            }
            90% {
                opacity: 0.3;
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Modal overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Modal dialog */
        .modal-dialog {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 40px;
            max-width: 480px;
            width: 90%;
            text-align: center;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }

        .modal-dialog::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
            background-size: 200% 100%;
            animation: shimmer 2s ease-in-out infinite;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(60px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes shimmer {
            0%, 100% {
                background-position: 0% 0%;
            }
            50% {
                background-position: 200% 0%;
            }
        }

        /* Sad emoji animation */
        .emoji {
            font-size: 80px;
            margin-bottom: 20px;
            display: inline-block;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            10% {
                transform: translateY(-10px) rotate(-5deg);
            }
            30% {
                transform: translateY(-15px) rotate(5deg);
            }
            40% {
                transform: translateY(-10px) rotate(-3deg);
            }
            60% {
                transform: translateY(-5px) rotate(2deg);
            }
        }

        .modal-title {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 15px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .modal-message {
            font-size: 18px;
            color: #4a5568;
            margin-bottom: 35px;
            line-height: 1.6;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 32px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            min-width: 140px;
            justify-content: center;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-cancel {
            background: linear-gradient(45deg, #4299e1, #3182ce);
            color: white;
            box-shadow: 0 8px 25px rgba(66, 153, 225, 0.3);
        }

        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(66, 153, 225, 0.4);
        }

        .btn-confirm {
            background: linear-gradient(45deg, #f56565, #e53e3e);
            color: white;
            box-shadow: 0 8px 25px rgba(245, 101, 101, 0.3);
        }

        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(245, 101, 101, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .modal-dialog {
                padding: 30px 25px;
                margin: 20px;
            }

            .emoji {
                font-size: 60px;
            }

            .modal-title {
                font-size: 24px;
            }

            .modal-message {
                font-size: 16px;
            }

            .button-group {
                flex-direction: column;
                gap: 12px;
            }

            .btn {
                width: 100%;
                padding: 16px 24px;
            }
        }

        /* Heart broken animation for extra sadness */
        .heart {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            color: #f56565;
            animation: heartbreak 3s ease-in-out infinite;
        }

        @keyframes heartbreak {
            0%, 100% {
                transform: scale(1);
                opacity: 0.7;
            }
            50% {
                transform: scale(1.2);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Background particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Modal Dialog -->
    <div class="modal-overlay">
        <div class="modal-dialog">
            <div class="heart">💔</div>
            <div class="emoji">😢</div>
            <h2 class="modal-title">Tunggu Dulu!</h2>
            <p class="modal-message">
                Apakah kamu yakin ingin keluar?<br>
                <strong>Admin sedih loh... 😭</strong>
            </p>
            <div class="button-group">
                <a href="javascript:history.back()" class="btn btn-cancel">
                    <span>❤️</span>
                    Tidak, Aku Tetap Di Sini
                </a>
                <a href="?confirm=yes" class="btn btn-confirm">
                    <span>👋</span>
                    Ya, Sampai Jumpa
                </a>
            </div>
        </div>
    </div>

    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Play subtle sound effect if available
            const playEmotionalMusic = () => {
                // You can add audio here if needed
                console.log('🎵 Playing emotional goodbye music...');
            };

            // Add extra particle on hover
            const modal = document.querySelector('.modal-dialog');
            modal.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.02)';
            });
            
            modal.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });

            // Animate buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px) scale(1.05)';
                });
                
                btn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });

        // Prevent accidental close
        window.addEventListener('beforeunload', function(e) {
            return; // Let the confirmation dialog handle it
        });
    </script>
</body>
</html>