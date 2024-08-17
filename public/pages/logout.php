<?php

// Session destroy
session_destroy();

// Delete the "Remember Me" cookies
setcookie('rm_token', '', time() - 3600);
setcookie('rm_username', '', time() - 3600);

// Redirect to Login page
header("Location: ".HOME_PAGE."?pg=login");
exit;

?>
