<?php 
    session_start();
    include ('storage.php');
    

    $pokemons = new Storage(new JsonIO('data/pokemon.json'));
    $users = new Storage(new JsonIO('data/users.json'));
    $name;
    $money;
    $loggedin = false;
    $userid;

    if(isset($_SESSION['login_id'])){
        if ($_SESSION['login_id'] !== ""){
            $loggedin = true;
            $name = $users->findById($_SESSION['login_id'])['name'];
            $money = $users->findById($_SESSION['login_id'])['money'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Home 
        <?php if (!$loggedin): ?>
            <br>
            <a href="login.php" class="login-reg"> Sign in </a> 
            <br>
            <a href="register.php" class="login-reg"> Register new account </a></h1>
        <?php else: ?>
            <h1 id="welcomeText"> Welcome back, <a href="profile.php"> <?= $name ?>! </a></h1>
            <h4>Your balance: <?= $money ?> <span class="icon">💰</span></h4>
            <span>Click on your username to see profile details!</span>
        <?php endif ?>
    </header>
    <div class="introduction">
        <h1>Welcome to IKémon!</h1> 
        <h2>Discover and collect one-of-a-kind Pokemon card NFTs in our exclusive marketplace!</h2>
    </div>
    <div id="content">
        <div id="card-list">
            <?php foreach ($pokemons->findAll() as $id => $pokemon): ?>
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
                </div>
                <?php if($loggedin && $name !== 'admin' && $pokemon['owner'] === 'admin'): ?>
                    <div class="buy">
                        <a class="buysell" href="buy.php?id=<?= $id ?>">
                            Buy for: <span class="card-price"><span class="icon">💰</span><?= $pokemon['price'] ?></span>
                        </a>    
                    </div>
                <?php else: ?>
                    <div class="buy">
                            Price: <span class="card-price"><span class="icon">💰</span><?= $pokemon['price'] ?></span>
                            
                    </div>    
                <?php endif ?>
            </div>
            <?php endforeach ?>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>