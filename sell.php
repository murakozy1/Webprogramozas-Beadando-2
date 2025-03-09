<?php
    session_start();

    include('storage.php');
    //include('index.php');
    
    $id = $_GET['id'];
    $pokemons = new Storage(new JsonIO('data/pokemon.json'));
    $pokemon = $pokemons->findById($id);
    $users = new Storage(new JsonIO('data/users.json'));
    $user = $users->findById($_SESSION['login_id']);
    $admin;
    $adminid = "";
    foreach($users->findAll() as $uid => $userobj){
        if ($userobj['name'] === 'admin'){
            $admin = $userobj;
            $adminid = $uid;    
        }
    }

    /*
    $error = "";
    if (isset($_POST['confirm'])){
        if ($user['money'] < $pokemon['price']){
            $error = "You do not have enough balance!";
        }
        else if($user['cards'] > 3){
            $error = "You can only have a maximum of 5 cards!";
        }
    

        if($error === ""){
            $user['money'] -= $pokemon['price'];
            $user['cards'] += 1;
            $pokemon['owner'] = $user['name'];
            $admin['cards'] -= 1;
            $users->update($_SESSION['login_id'], $user);
            $users->update($adminid, $admin);
            $pokemons->update($id, $pokemon);
            header("Location: index.php");
            exit();
        }
        
    }
    */
    if (isset($_POST['confirm'])){
        $pokemon['owner'] = 'admin';
        $admin['cards'] += 1;
        $user['money'] += $pokemon['price'] * 0.9;
        $user['cards'] -= 1;
        $users->update($_SESSION['login_id'], $user);
        $users->update($adminid, $admin);
        $pokemons->update($id, $pokemon);
        header("Location: profile.php");
        die;
    }
    else if (isset($_POST['cancel'])){
        header("Location: profile.php");
        die;
    }
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | <?= 'Sell' ?></title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>


<body>
    <header>
        <h1><a href="index.php">IKémon</a> > <a href="profile.php">Profile</a> > <?= 'Sell' ?></h1>
    </header>
        <div id="content">
            <form class="buysellForm" action="" method="post">
                <h2>Your balance: <?= $user['money'] ?><span class="icon">💰</span></h2>
                <h2>Sell your <?= $pokemon['name'] ?> for <?= $pokemon['price'] * 0.9 ?> <span class="icon">💰</span>?</h2>
                <div id="card-list">
                    <div class="pokemon-card" id="buysell-pokemoncard">
                        <div class="image clr-<?= $pokemon['type'] ?>">
                            <img src="<?= $pokemon['image'] ?>" alt="">
                        </div>
                        <div class="details">
                            <h2><?= $pokemon['name'] ?></h2>
                            <span class="card-type"><span class="icon">🏷</span><?= $pokemon['type'] ?></span>
                            <span class="attributes">
                                <span class="card-hp"><span class="icon">❤</span><?= $pokemon['hp'] ?></span>
                                <span class="card-attack"><span class="icon">⚔</span><?= $pokemon['attack'] ?></span>
                                <span class="card-defense"><span class="icon">🛡</span><?= $pokemon['defense'] ?></span>
                            </span>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <button name="confirm" class="logoutButton">Confirm</button>
                <button name="cancel" class="logoutButton">Cancel</button>
                
            </form>
        </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>