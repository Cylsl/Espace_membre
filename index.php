<?php

session_start();

require('src/connexion_bdd.php');

if (!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])) {

  // variables

  $pseudo           =  $_POST['pseudo'];
  $email            =  $_POST['email'];
  $password         =  $_POST['password'];
  $password_confirm =  $_POST['password_confirm'];

  // test password

  if ($password != $password_confirm) {
    header('location:index.php?error=1&pass=1');
    exit();
  }

  // test mail
  $req = $db->prepare("SELECT count(*) as numberEmail FROM user WHERE email = ?");
  $req->execute(array($email));

  while ($email_verification = $req->fetch()) {
    if ($email_verification['numberEmail'] != 0) {
      header('location:index.php?error=1&email=1');
      exit();
    }
  }

  // sécurité email 

  $secret = sha1($email) . time();
  $secret = sha1($secret) . time() . time();

  // cryptage du password

  $password = "aq1" . sha1($password . "7382") . "91";

  // envoi de la requête dans la bdd
  $req = $db->prepare("INSERT INTO user(pseudo, email, password, secret) VALUES(?,?,?,?)");
  $req->execute(array($pseudo, $email, $password, $secret));

  header('location:index.php?success=1');
  exit();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style3.css">
  <title>Espace membre</title>
</head>

<body>
  <header>
    <h1>Inscription</h1>
  </header>

  <div class="container">

    <?php

    if (!isset($_SESSION['connect'])) { ?>

      <p> Bienvenue sur mon site, pour en savoir plus, inscrivez-vous.</p>

      <p>Si vous possédez déjà un compte <a href="connexion.php"><b>cliquez ici</b></a> pour vous connecter</p>


      <div id="form">
        <form action="index.php" method="post">
          <table>
            <tr>
              <td>Pseudo</td>
              <td><input type="text" name="pseudo" placeholder="Ex: Cyril" required></td>
            </tr>
            <tr>
              <td>Email</td>
              <td><input type="email" name="email" placeholder="exemple@gmail.com" required></td>
            </tr>
            <tr>
              <td>Mot de passe</td>
              <td><input type="password" name="password" required></td>
            </tr>
            <tr>
              <td>Confirmez le mot de passe</td>
              <td><input type="password" name="password_confirm" required></td>
            </tr>
          </table>
          <div id=button>
            <button>Inscription</button>
            <?php
            if (isset($_GET['error'])) {
              if (isset($_GET['pass'])) {
                echo '<p id="error"> Les mots de passe ne sont pas identiques. Veuillez réessayer</p>';
              } else if (isset($_GET['email'])) {
                echo '<p id="error"> Cette adresse email est déjà utilisée. Veuillez réessayer</p>';
              }
            } else if (isset($_GET['success'])) {
              echo '<p id="success"> Merci. Votre inscription est confirmée</p>';
            }
            ?>
          </div>
        </form>
      </div>
    <?php } else { ?>
      <p> Bonjour <?= $_SESSION['pseudo'] ?><br>
        <a href="deconnexion.php">Déconnexion</a>
      </p>

    <?php }  ?>
  </div>


</body>

</html>