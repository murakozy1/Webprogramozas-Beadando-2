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

    $error = "";
    if (isset($_POST['confirm'])){
        if ($user['money'] < $pokemon['price']){
            $error = "You do not have enough balance!";
        }
        else if($user['cards'] > 5){
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
            die;
        }
        
    }
    else if (isset($_POST['cancel'])){
        header("Location: index.php");
        die;
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | <?= 'Buy' ?></title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> > <?= 'Buy' ?></h1>
    </header>
        <div id="content">
        <?php if($error !== ""): ?>
                <div style="text-align: center;" id="buyerror">
                    <h4><?= $error ?></h4>
                </div>
            <?php endif ?>
            <form class="buysellForm" action="" method="post">
                <h2>Your balance: <?= $user['money'] ?><span class="icon">💰</span></h2>
                <h2>Buy <?= $pokemon['name'] ?> for <?= $pokemon['price'] ?> <span class="icon">💰</span>?</h2>
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
            
        </div>
        
        
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>