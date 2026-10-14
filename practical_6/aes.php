<?php
// AES Encryption and Decryption using PHP

$encryptedText = "";
$decryptedText = "";
$message = "";
$error = "";

// Generate a 256-bit AES key
$key = hash('sha256', 'MySecretKey123', true);

// Generate a random Initialization Vector
$iv = openssl_random_pseudo_bytes(
    openssl_cipher_iv_length('AES-256-CBC')
);

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $message = $_POST["message"] ?? "";

    if (empty($message)) {
        $error = "Please enter a message.";
    } else {

        // AES Encryption
        $encrypted = openssl_encrypt(
            $message,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        // Convert encrypted data to Base64
        $encryptedText = base64_encode($encrypted);

        // Convert Base64 back to binary before decryption
        $encryptedData = base64_decode($encryptedText);

        // AES Decryption
        $decrypted = openssl_decrypt(
            $encryptedData,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        if ($decrypted !== false) {
            $decryptedText = $decrypted;
        } else {
            $error = "Decryption failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AES Encryption and Decryption</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 40px;
        }

        .container {
            width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #aaa;
        }

        h1 {
            text-align: center;
        }

        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            padding: 10px 25px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #eee;
            border-radius: 5px;
        }

        .error {
            color: red;
        }

        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>AES Encryption & Decryption</h1>

    <form method="post">
        <label>
            <strong>Enter Message:</strong>
        </label>

        <textarea
            name="message"
            placeholder="Enter message to encrypt..."
            required
        ></textarea>

        <input
            type="submit"
            value="Encrypt & Decrypt"
        >
    </form>

    <?php if (!empty($error)): ?>
        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($encryptedText)): ?>

        <div class="result">

            <h3>Original Message</h3>
            <p>
                <?php echo htmlspecialchars($message); ?>
            </p>

            <h3>AES Algorithm</h3>
            <p>
                AES-256-CBC
            </p>

            <h3>Secret Key</h3>
            <pre><?php echo htmlspecialchars(base64_encode($key)); ?></pre>

            <h3>Initialization Vector (IV)</h3>
            <pre><?php echo htmlspecialchars(base64_encode($iv)); ?></pre>

            <h3>Encrypted Message</h3>
            <pre><?php echo htmlspecialchars($encryptedText); ?></pre>

            <h3>Decrypted Message</h3>
            <p>
                <?php echo htmlspecialchars($decryptedText); ?>
            </p>

        </div>

    <?php endif; ?>

</div>

</body>
</html>