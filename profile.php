<?php
    session_start();
    include('storage.php');

    $users = new Storage(new JsonIO('data/users.json'));
    $pokemons = new Storage(new JsonIO('data/pokemon.json'));
    $name = $users->findById($_SESSION['login_id'])['name'];
    $cardNum = 0;
    foreach($pokemons->findAll() as $id => $pokemon){
        if ($pokemon['owner'] === $users->findById($_SESSION['login_id'])['name']){
            $cardNum++;
        }
    }

    if(isset($_POST['logout'])){
        $_SESSION['login_id'] = "";
        header("Location: index.php");
        die;
    }

    if(isset($_POST['newcard'])){
        header("Location: newcard.php");
        die;
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Profile</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>
<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Profile </h1>
        <form class="logoutForm" method="post">
            <button name="logout" class="logoutButton">Sign out</button>
            <?php if($name === 'admin'): ?>
                <button name="newcard" class="logoutButton">Add new card</button>
            <?php endif ?>
        </form>
    </header>
    <div id="content">
        <div id="details">
            <h3>Username: <?= $users->findById($_SESSION['login_id'])['name'] ?></h3>
            <h3>Email address: <?= $users->findById($_SESSION['login_id'])['email'] ?></h3>
            <h3>Money: <?= $users->findById($_SESSION['login_id'])['money'] ?><span class="icon">💰</span></h3>
            <h3>Your cards: <?= $cardNum ?></h3>
        </div>
        <div id="card-list">
            <?php foreach ($pokemons->findAll() as $id => $pokemon): ?>
                <?php if ($pokemon['owner'] === $users->findById($_SESSION['login_id'])['name']): ?>
                    <div class="pokemon-card">
                        <div class="image clr-<?= $pokemon['type'] ?>">
                            <img src="<?= $pokemon['image'] ?>" alt="">
                        </div>
                        <div class="details">
                            <h2><a href="details.php?id=<?= $id ?>"><?= $pokemon['name'] ?></a></h2>
                            <span class="card-type"><span class="icon">🏷</span><?= $pokemon['type'] ?></span>
                            <span class="attributes">
                                <span class="card-hp"><span class="icon">❤</span><?= $pokemon['hp'] ?></span>
                                <span class="card-attack"><span class="icon">⚔</span><?= $pokemon['attack'] ?></span>
                                <span class="card-defense"><span class="icon">🛡</span><?= $pokemon['defense'] ?></span>
                            </span>
                            <?php if ($name !== 'admin'): ?>
                                <div class="buy">
                                    <a class="buysell" href="sell.php?id=<?= $id ?>">
                                        Sell for: <span class="card-price"><span class="icon">💰</span><?= $pokemon['price'] * 0.9 ?></span>
                                    </a>  
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>