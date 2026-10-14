<!DOCTYPE html>
<html>
<head>
    <title>Caesar Cipher</title>
</head>
<body>

<h2>Caesar Cipher Encryption and Decryption</h2>

<form method="post">
    <label>Enter Text:</label><br>
    <input type="text" name="text" required><br><br>

    <label>Enter Shift:</label><br>
    <input type="number" name="shift" value="3" required><br><br>

    <input type="submit" name="encrypt" value="Encrypt">
    <input type="submit" name="decrypt" value="Decrypt">
</form>

<?php
function caesarCipher($text, $shift)
{
    $result = "";
    // Normalise the shift to 0-25 so large or negative values work correctly
    $shift = (($shift % 26) + 26) % 26;

    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];

        if (ctype_upper($char)) {
            $result .= chr((ord($char) - ord('A') + $shift) % 26 + ord('A'));
        }
        elseif (ctype_lower($char)) {
            $result .= chr((ord($char) - ord('a') + $shift) % 26 + ord('a'));
        }
        else {
            $result .= $char;
        }
    }
    return $result;
}

if (isset($_POST['encrypt'])) {
    $text = $_POST['text'];
    $shift = intval($_POST['shift']);

    $encrypted = caesarCipher($text, $shift);

    echo "<h3>Encrypted Text:</h3>";
    echo "<p>" . htmlspecialchars($encrypted) . "</p>";
}

if (isset($_POST['decrypt'])) {
    $text = $_POST['text'];
    $shift = intval($_POST['shift']);

    $decrypted = caesarCipher($text, -$shift);

    echo "<h3>Decrypted Text:</h3>";
    echo "<p>" . htmlspecialchars($decrypted) . "</p>";
}
?>

</body>
</html>