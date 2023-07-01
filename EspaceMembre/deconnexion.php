<?php
session_start();  /* Initialise la session */
session_unset(); /* Désactiver la session */
session_destroy();  /* Détruit la session */
setcookie('log', '', time() - 3444, '/', null, false, true);

header('location:index.php');
exit();
