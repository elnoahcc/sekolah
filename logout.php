<?php
session_start();
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
?>
