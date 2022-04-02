<?php
// Try on the header to avoid re-submission of the form when refreshing the page but doesn't work
// header($_SERVER["SERVER_PROTOCOL"].' 205 Reset Content');
require_once 'connec.php';

$pdo = new \PDO(DSN, USER, PASS);

//
$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll();

// $query = "INSERT INTO friend (firstname, lastname) VALUES ('Chandler', 'Bing')";
// $statement = $pdo->exec($query);

//Non-sécurisé
// $firstname = trim($_POST['firstname']);
// $lastname = trim($_POST['lastname']);
// $query = "INSERT INTO friend (firstname, lastname) VALUES ('$firstname', '$lastname')";
// $pdo->exec($query);

//Sécurisé via une requête préparée
// get the data from a form
$error = '';

if (isset($_POST['firstname']) && isset($_POST['lastname'])) {
    if (empty($_POST['firstname']) || empty($_POST['lastname'])) {
        $error = 'Tous les champs doivent être complétés';
    } elseif (strlen($_POST['firstname']) > 45 || strlen($_POST['lastname']) > 45) {
        $error = 'Les champs ne peuvent comporter que 45 caractères maximum';
    } else {
        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);

        $query = 'INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)';
        $statement = $pdo->prepare($query);

        $statement->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $lastname, \PDO::PARAM_STR);

        $statement->execute();
    }
}


// $friends = $statement->fetchAll();
//
// $friends = $statement->fetchAll(PDO::FETCH_BOTH); // same as $statement->fetchAll()
// var_dump($friends);
//
// $friends = $statement->fetchAll(PDO::FETCH_ASSOC);

// $friends = $statement->fetchAll(PDO::FETCH_OBJ);

// foreach ($friends as $friend) {
//     echo $friend->firstname . ' ' . $friend->lastname;
// }

?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Friends</title>
    <meta name="description" content="description"/>
    <meta name="author" content="author" />
    <meta name="keywords" content="keywords" />
    <!-- <link rel="stylesheet" href="./stylesheet.css" type="text/css" /> -->

  </head>
  <body>

<h1>Friends</h1>

<div class="friends-list">
  <ul>
    <?php foreach ($friends as $friend) { ?>
      <?= '<li>' . $friend['firstname'] . ' ' . $friend['lastname'] . '</li>'; ?>
    <?php } ?>
  </ul>
</div>

<section>

  <form class="friend-form" action="index.php" method="post">

    <div class="form-part">
      <label for="firstname">Firstname : </label>
      <input type="text" name="firstname" value="" placeholder="firstname">
    </div>

    <div class="form-part">
      <label for="lastname">Lastname : </label>
      <input type="text" name="lastname" value="" placeholder="lastname">
    </div>

    <input type="submit" name="add-friend" value="Add a new friend">

<p><?= !empty($error) ? $error : '' ?></p>


  </form>
</section>
  </body>
</html>
