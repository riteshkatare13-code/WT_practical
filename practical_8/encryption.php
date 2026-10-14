<?php
// encryption.php
// AES-256-GCM encryption and decryption helpers.
//
// IMPORTANT: this key is for classroom demonstration only.
// A real application must use a securely generated key stored outside the
// source code. If the key is lost, existing records cannot be decrypted.

define("CIPHER_METHOD", "aes-256-gcm");
define("IV_LENGTH", 12);    // recommended IV size for GCM
define("TAG_LENGTH", 16);   // authentication tag size in bytes

// 256-bit (32-byte) secret key derived from a passphrase
define("SECRET_KEY", hash("sha256", "StudentCrudDemoKey-ChangeMe", true));

/**
 * Encrypts plain text.
 * Returns base64( IV + authentication tag + ciphertext ), or false on failure.
 */
function encryptData($plainText)
{
    $iv  = random_bytes(IV_LENGTH);
    $tag = "";

    $cipherText = openssl_encrypt(
        $plainText,
        CIPHER_METHOD,
        SECRET_KEY,
        OPENSSL_RAW_DATA,
        $iv,
        $tag,
        "",
        TAG_LENGTH
    );

    if ($cipherText === false) {
        return false;
    }

    return base64_encode($iv . $tag . $cipherText);
}

/**
 * Verifies and decrypts a value produced by encryptData().
 * Returns the plain text, or false if the data is invalid, altered,
 * or the key is wrong.
 */
function decryptData($encryptedText)
{
    $data = base64_decode($encryptedText, true);

    if ($data === false || strlen($data) < IV_LENGTH + TAG_LENGTH) {
        return false;
    }

    $iv         = substr($data, 0, IV_LENGTH);
    $tag        = substr($data, IV_LENGTH, TAG_LENGTH);
    $cipherText = substr($data, IV_LENGTH + TAG_LENGTH);

    return openssl_decrypt(
        $cipherText,
        CIPHER_METHOD,
        SECRET_KEY,
        OPENSSL_RAW_DATA,
        $iv,
        $tag
    );
}