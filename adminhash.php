<?php

$USER_password = "1"; // Assuming the password is "1" (string)
$hashed_password = sha1($USER_password); // Compute the SHA-1 hash of the password

echo 'HASH : ' . $hashed_password; // Display the hash

?>
