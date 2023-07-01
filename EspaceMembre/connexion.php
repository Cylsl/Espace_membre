<?php

session_start();

if (isset($_SESSION['connect'])) {
  header('location:index.php');
  exit();
}

require('src/connexion_bdd.php');

if (!empty($_POST['email']) && !empty($_POST['password'])) {

  // variables


  $email            =  $_POST['email'];
  $password         =  $_POST['password'];
  $error            = 1;

  // cryptage password

  $password = "aq1" . sha1($password . "7382") . "91";

  echo $password;

  $req = $db->prepare('SELECT * FROM user WHERE email = ?');
  $req->execute(array($email));

  while ($user = $req->fetch()) {
    if ($password == $user['password']) {
      $error = 0;
      $_SESSION['connect'] = 1;
      $_SESSION['pseudo'] = $user['pseudo'];

      if (isset($_POST['connect'])) {
        setcookie('log', $user['secret'], time() + 365 * 24 * 3600, '/', null, false, true);
      }
      header('location:connexion.php?success=1');
      exit();
    }
  }
  if ($error == 1) {
    header('location:connexion.php?error=1');
    exit();
  }
}

?>

<!DOCTYPE html>
<html lang="fr">

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style3.css">
  <title>Connexion</title>
</head>

<body>
  <header>
    <h1>Connexion</h1>
  </header>

  <div class="container">

    <p>Pour revenir à la page d'inscription <a href="index.php"><b>cliquez-ici</b></a></p>
    <div id="form">
      <form action="connexion.php" method="post">
        <table>
          <tr>
            <td>Email</td>
            <td><input type="email" name="email" placeholder="exemple@gmail.com" required></td>
          </tr>
          <tr>
            <td>Mot de passe</td>
            <td><input type="password" name="password" required></td>
          </tr>
        </table>
        <p><label><input type="checkbox" name="connect" checked> Connexion automatique</p></label>
        <div id="button"> <button>Connexion</button>
          <?php
          if (isset($_GET['error'])) {
            echo '<p id="error"> Connexion impossible, veuillez créer un compte</p>';
          } else if (isset($_GET['success'])) {
            echo '<p id="success"> Vous êtes maintenant connecté</p>';
          }
          ?>
        </div>

      </form>
    </div>
  </div>

</body>

</html>