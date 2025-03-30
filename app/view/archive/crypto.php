<?php
function decryptAES_ECB($encryptedData, $key) {
    // Convert hex-encoded data to raw binary
    $ciphertext = hex2bin($encryptedData);

    // Perform AES-ECB decryption
    $decrypted = openssl_decrypt($ciphertext, "AES-256-ECB", $key, OPENSSL_RAW_DATA);

    return $decrypted;
}
?>
