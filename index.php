<?php
function generate_password($length = 12, $use_letters = true, $use_numbers = true, $use_symbols = true) {
    $chars = '';

    if ($use_letters) $chars .= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($use_numbers) $chars .= '0123456789';
    if ($use_symbols) $chars .= '!@#$%^&*()-_=+[]{}|;:,.<>?';

    if ($chars === '') return 'Error: Please select at least one option.';

    $password = '';
    $max_index = strlen($chars) - 1;

    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, $max_index)];
    }

    return $password;
}

$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $length = (int)$_POST['length'];
    $use_letters = isset($_POST['letters']);
    $use_numbers = isset($_POST['numbers']);
    $use_symbols = isset($_POST['symbols']);

    $password = generate_password($length, $use_letters, $use_numbers, $use_symbols);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 420px;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            margin-bottom: 15px;
        }

        input[type="number"], input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 8px;
            margin-bottom: 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .options label {
            display: block;
            margin-top: 8px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: #2563eb;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 12px;
            font-size: 16px;
        }

        .copy-btn {
            display: block;
            margin: 10px auto 0 auto; /* center */
            padding: 5px 12px;
            background: #10b981;
            color: white;
            font-size: 13px; /* gamay */
            border-radius: 5px;
            cursor: pointer;
            border: none;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Password Generator</h2>

    <form method="POST">
        <label>Password Length:</label>
        <input type="number" name="length" min="4" max="50" value="12" required>

        <div class="options">
            <label><input type="checkbox" name="letters" checked> Letters (A-Z a-z)</label>
            <label><input type="checkbox" name="numbers" checked> Numbers (0-9)</label>
            <label><input type="checkbox" name="symbols" checked> Symbols (!@#$...)</label>
        </div>

        <button class="btn" type="submit">Generate Password</button>
    </form>

    <?php if ($password): ?>
        <label>Your Password:</label>
        <input type="text" id="passwordOutput" value="<?php echo htmlspecialchars($password); ?>" readonly>

        <button class="copy-btn" onclick="copyPassword()">Copy</button>
    <?php endif; ?>
</div>

<script>
function copyPassword() {
    const password = document.getElementById("passwordOutput").value;
    navigator.clipboard.writeText(password);
}
</script>

</body>
</html>
