<?php
session_start();

//print_r($_POST);
include('storage.php');
//////ALLAPOTTARTAS////////
$users = new Storage(new JsonIO('data/users.json'));

function validate($input, &$data, &$errors, $users, &$validpassword) {
  if (!isset($input["name"])) {
    $errors['name'] = "Name is required!";
  }
  else if (trim($input["name"]) === "") {
    $errors['name'] = "Name is required!";
  }
  else{
    $namefound = false;
    foreach($users->findAll() as $id => $user){
        if ($user['name'] === $input['name']){
            $namefound = true;
            $data['name'] = $user['name'];
            $validpassword = $user['password'];
            $data['id'] = $id;
        }
    }

    if (!$namefound){
      $errors['name'] = "Name is invalid!";
    }
    else{
      if (!isset($input['password'])) {
        $errors['password'] = "Password is required!";
      }
      else{
        if($input['password'] !== $validpassword){
          $errors['password'] = "Password is invalid!";
        }
      }
    }
  }
  return count($errors) === 0;
}

$data = [];
$errors = [];
$validpassword;
//$emails = [];
if (count($_POST) > 0) {
  if (validate($_POST, $data, $errors, $users, $validpassword)) {
    if (sizeof($errors) === 0){
        //$data['email'] = $emails[0];
        //$data['money'] = 1500;        
        //$users->add($data);
        /*
        $logindata = $data;
        $users->update('Logindata', $logindata);
        */
        $_SESSION['login_id'] = $data['id'];
        header("Location: index.php");
        die;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IKémon | Sign in</title>
  <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Sign in </h1>
    </header>
        <h1 id="title"> Sign into your account! </h1>
        <form class="dataform" method="post" novalidate>
          Userame: <br>
          <input type="text" name="name" required
          value="<?php if (isset($data['name'])): echo($data['name']); endif?>">
          <?php if (isset($errors['name'])): ?>
                <?= $errors['name'] ?>
          <?php endif ?>
          <br>
            Password: <br>
            <input type="password" name="password" required
            value="">
            <?php if (isset($errors['password'])): ?>
                <?= $errors['password'] ?>
            <?php endif ?>
            <br>
            <br>
          <button>Sign in</button>
        </form>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>