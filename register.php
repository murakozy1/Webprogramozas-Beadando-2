<?php
session_start();
include('storage.php');

$users = new Storage(new JsonIO('data/users.json'));

function validate($input, &$data, &$errors, &$emails, &$passwords, $users){
  if (!isset($input["name"])) {
    $errors['name'] = "Name is required!";
  }
  else if (trim($input["name"]) === "") {
    $errors['name'] = "Name is required!";
  }
  else{
    foreach($users->findAll() as $user){
        if ($user['name'] === $input['name']){
            $errors['name'] = "This name is already taken!";
        }
    }
    if (!isset($errors['name'])){
      $data["name"] = $input["name"];
    }
  }

  if (!isset($input["emails"])) {
    $errors['email'] = "Email address is required!";
  }
  else {
    $notemptyemails = array_filter($input["emails"], function($e) {
      return trim($e) !== "";
    });
    if (count($notemptyemails) === 0) {
      $errors['email'] = "Email address is required!";
    }
    $validemails = array_filter($notemptyemails, function($e) {
      return filter_var($e, FILTER_VALIDATE_EMAIL) !== false;
    });
    if (count($notemptyemails) !== count($validemails)) {
      $errors['email'] = "Email address has invalid format!";
    } else {
      $emails = $validemails;
    }
    }

    if (!isset($input['passwords'])) {
        $errors['password'] = "Password is required!";
    }
    else{
        $passwords = $input['passwords'];
    }

    if(trim($passwords[0]) === ""){
        $errors['password'] = "Password is required!";
    }
    else if(str_contains($passwords[0], ' ')){
        $errors['password'] = "Password can not contain spaces!";
    }
    else{
        if ($passwords[0] !== $passwords[1]){
            $errors['passwordConf'] = "Passwords do not match!";
        }
        else{
            $data['password'] = $passwords[0];
            $passwords[1] = $passwords[0];
        }
    }
    
    

  //print_r($data);
  //print_r($errors);
  return count($errors) === 0;
}

$data = [];
$errors = [];
$emails = [];
$passwords = [];
if (count($_POST) > 0) {
  if (validate($_POST, $data, $errors, $emails, $passwords, $users)) {
    if (sizeof($errors) === 0){
        $data['email'] = $emails[0];
        $data['money'] = 1500; 
        $data['cards'] = 0;       
        $users->add($data);
        //$logindata = $data;
        //$logindata['id'] = $data['id'];
        //$users->update('Logindata', $logindata);
        foreach($users->findAll() as $id => $user){
          if ($user['name'] === $data['name']){
            $_SESSION['login_id'] = $id;
          }
        }
      $data = [];
      header("Location: index.php");
      die;
    }
    // $cs = new ContactStorage();
    // $cs->add($data);
    // header("Location: index.php");
    // exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IKémon | Register</title>
  <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Register </h1>
    </header>
        <h1 id="title"> Create a new account! </h1>
        <form class="dataform" method="post" novalidate>
          Userame: <br>
          <input type="text" name="name" required
          value="<?php if (isset($data['name'])): echo($data['name']); endif ?>">
          <?php if (isset($errors['name'])): ?>
                <?= $errors['name'] ?>
          <?php endif ?>
          <br>
          Email: <br>
            <input type="email" name="emails[]" required
            value="<?php if (isset($emails[0])): echo($emails[0]); endif ?>">
            <?php if (isset($errors['email'])): ?>
                <?= $errors['email'] ?>
            <?php endif ?>
            <br>
            Password: <br>
            <input type="password" name="passwords[]" required
            value="<?php if (isset($passwords[0])): echo($passwords[0]); endif ?>">
            <?php if (isset($errors['password'])): ?>
                <?= $errors['password'] ?>
            <?php endif ?>
            <br>
            Confirm password: <br>
            <input type="password" name="passwords[]" required
            value="<?php if (isset($passwords[1])): echo($passwords[1]); endif ?>">
            <?php if (isset($errors['passwordConf'])): ?>
                <?= $errors['passwordConf'] ?>
            <?php endif ?>
            <br>
            <br>
          <button>Create account</button>
        </form>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>