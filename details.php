<?php

    include('storage.php');
    //include('index.php');

    $id = $_GET['id'];    
    //$io = new JsonIO('pokemon.json');
    $pokemons = new Storage(new JsonIO('data/pokemon.json'));
    $pokemon = $pokemons->findById($id);
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | <?= $pokemon['name'] ?></title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
</head>

<body>
    <header class="clr-<?= $pokemon['type'] ?>">
        <h1><a href="index.php">IKémon</a> > <?= $pokemon['name'] ?></h1>
    </header>
    <div id="content">
        <div id="details">
            <div class="image clr-<?= $pokemon['type'] ?>">
                <img src="<?= $pokemon['image'] ?>" alt="">
            </div>
            <div class="info">
                <div class="description"><?= $pokemon['description'] ?></div>
                <span class="card-type"><span class="icon">🏷</span> Type: <?= $pokemon['type'] ?></span>
                <div class="attributes">
                    <div class="card-hp"><span class="icon">❤</span> Health: <?= $pokemon['hp'] ?></div>
                    <div class="card-attack"><span class="icon">⚔</span> Attack: <?= $pokemon['attack'] ?></div>
                    <div class="card-defense"><span class="icon">🛡</span> Defense: <?= $pokemon['defense'] ?></div>
                </div>
            </div>
        </div>
    <p id="findout">Find out more <a href="https://www.pokemon.com/us/pokedex/<?= $pokemon['name'] ?>" target="new">here!</a></p>
    </div>
    <footer class="clr-<?= $pokemon['type'] ?>">
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>