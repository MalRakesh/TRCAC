<?php
session_start();
session_destroy(); // Sab session data delete
header("Location: ../index.html");
exit;
?>