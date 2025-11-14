<?php
// Initialize variables
 $password = '';
 $strength = '';
 $strengthColor = '';
 $strengthWidth = '0%';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $length = isset($_POST['length']) ? (int)$_POST['length'] : 12;
    $includeLetters = isset($_POST['letters']);
    $includeNumbers = isset($_POST['numbers']);
    $includeSymbols = isset($_POST['symbols']);
    
    // Validate length
    $length = max(4, min(50, $length));
    
    // Character sets
    $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';
    
    $chars = '';
    if ($includeLetters) $chars .= $letters;
    if ($includeNumbers) $chars .= $numbers;
    if ($includeSymbols) $chars .= $symbols;
    
    // If no character types selected, default to letters
    if (empty($chars)) {
        $chars = $letters;
    }
    
    // Generate password
    $password = '';
    $charsLength = strlen($chars);
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, $charsLength - 1)];
    }
    
    // Calculate password strength
    $score = 0;
    
    // Length score
    if ($length >= 8) $score += 1;
    if ($length >= 12) $score += 1;
    if ($length >= 16) $score += 1;
    
    // Character variety score
    if ($includeLetters) $score += 1;
    if ($includeNumbers) $score += 1;
    if ($includeSymbols) $score += 1;
    
    // Determine strength level
    if ($score <= 2) {
        $strength = 'Weak';
        $strengthColor = '#e74c3c';
        $strengthWidth = '33%';
    } elseif ($score <= 4) {
        $strength = 'Medium';
        $strengthColor = '#f39c12';
        $strengthWidth = '66%';
    } else {
        $strength = 'Strong';
        $strengthColor = '#27ae60';
        $strengthWidth = '100%';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Generator</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: translateY(-5px);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 2.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .length-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        input[type="range"] {
            flex: 1;
            height: 6px;
            background: #e0e0e0;
            border-radius: 3px;
            outline: none;
            -webkit-appearance: none;
            transition: background 0.3s ease;
        }

        input[type="range"]:hover {
            background: #d0d0d0;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        input[type="range"]::-webkit-slider-thumb:hover {
            transform: scale(1.2);
        }

        input[type="range"]::-moz-range-thumb {
            width: 20px;
            height: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            cursor: pointer;
            border: none;
            transition: transform 0.2s ease;
        }

        input[type="range"]::-moz-range-thumb:hover {
            transform: scale(1.2);
        }

        .length-display {
            background: #f0f0f0;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            color: #667eea;
            min-width: 50px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .checkbox-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .checkbox-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .checkbox-item label {
            margin: 0;
            cursor: pointer;
            flex: 1;
        }

        .generate-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .generate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .generate-btn:active {
            transform: translateY(0);
        }

        .password-display {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            border: 2px solid #e9ecef;
            display: <?php echo empty($password) ? 'none' : 'block'; ?>;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .password-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .password-text {
            flex: 1;
            padding: 12px 16px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            word-break: break-all;
            color: #333;
        }

        .copy-btn {
            padding: 12px 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .copy-btn:hover {
            background: #218838;
            transform: scale(1.05);
        }

        .copy-btn.copied {
            background: #17a2b8;
        }

        .strength-meter {
            margin-top: 15px;
        }

        .strength-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: #666;
        }

        .strength-bar {
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }

        .strength-fill {
            height: 100%;
            background: <?php echo $strengthColor; ?>;
            width: <?php echo $strengthWidth; ?>;
            transition: all 0.5s ease;
            border-radius: 4px;
        }

        .toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #333;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 0.95rem;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 2rem;
            }

            .password-container {
                flex-direction: column;
            }

            .copy-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Password Generator</h1>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="length">Password Length</label>
                <div class="length-container">
                    <input type="range" id="length" name="length" min="4" max="50" value="12" oninput="updateLengthDisplay(this.value)">
                    <div class="length-display" id="lengthDisplay">12</div>
                </div>
            </div>

            <div class="form-group">
                <label>Include:</label>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" id="letters" name="letters" checked>
                        <label for="letters">Letters (A-Z, a-z)</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="numbers" name="numbers" checked>
                        <label for="numbers">Numbers (0-9)</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="symbols" name="symbols">
                        <label for="symbols">Symbols (!@#$%^&*)</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="generate-btn">Generate Password</button>
        </form>

        <?php if (!empty($password)): ?>
        <div class="password-display">
            <div class="password-container">
                <div class="password-text" id="passwordText"><?php echo htmlspecialchars($password); ?></div>
                <button class="copy-btn" onclick="copyPassword()">
                    <span>📋</span>
                    <span id="copyText">Copy</span>
                </button>
            </div>
            
            <div class="strength-meter">
                <div class="strength-label">
                    <span>Password Strength</span>
                    <span style="color: <?php echo $strengthColor; ?>; font-weight: 600;"><?php echo $strength; ?></span>
                </div>
                <div class="strength-bar">
                    <div class="strength-fill"></div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="toast" id="toast">Password copied to clipboard!</div>

    <script>
        function updateLengthDisplay(value) {
            document.getElementById('lengthDisplay').textContent = value;
        }

        function copyPassword() {
            const passwordText = document.getElementById('passwordText').textContent;
            const copyBtn = document.querySelector('.copy-btn');
            const copyText = document.getElementById('copyText');
            const toast = document.getElementById('toast');
            
            navigator.clipboard.writeText(passwordText).then(() => {
                // Update button state
                copyBtn.classList.add('copied');
                copyText.textContent = 'Copied!';
                
                // Show toast
                toast.classList.add('show');
                
                // Reset after 2 seconds
                setTimeout(() => {
                    copyBtn.classList.remove('copied');
                    copyText.textContent = 'Copy';
                    toast.classList.remove('show');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy password:', err);
            });
        }

        // Add smooth scrolling for better UX
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                setTimeout(() => {
                    const passwordDisplay = document.querySelector('.password-display');
                    if (passwordDisplay) {
                        passwordDisplay.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }, 100);
            });
        });
    </script>
</body>
</html>
