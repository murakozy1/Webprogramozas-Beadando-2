<?php
    session_start();
    include ('storage.php');
    $users = new Storage(new JsonIO('data/users.json'));
    $pokemons = new Storage(new JsonIO('data/pokemon.json'));
    $admin_id = $_SESSION['login_id'];
    $admin = $users->findById($admin_id);



    function validate($input, &$data, &$errors, $pokemons){
        if (!isset($input["name"])) {
            $errors['name'] = "Name is required!";
        }
        else if (trim($input["name"]) === "") {
            $errors['name'] = "Name is required!";
        }
        else{
          foreach($pokemons->findAll() as $pokemon){
                if ($pokemon['name'] === $input['name']){
                    $errors['name'] = "This Pokémon can already be found in the shop!";
                }
            }
            if (!isset($errors['name'])){
                $data["name"] = $input["name"];
            }
        }
        ///////type
        if (!isset($input['type'])){
            $errors['type'] = "Type is required!";
        }
        else{
            $data['type'] = $input['type'];
        }

        ///////hp
        if (!isset($input['hp'])){
            $errors['hp'] = 'Hp value is required!';
        }
        else if(trim($input['hp']) === ''){
            $errors['hp'] = 'Hp value is required!';
        }
        else{
            $data['hp'] = $input['hp'];
        }

        ///////attack
        if (!isset($input['attack'])){
            $errors['attack'] = 'Attack value is required!';
        }
        else if(trim($input['attack']) === ''){
            $errors['attack'] = 'Attack value is required!';
        }
        else{
            $data['attack'] = $input['attack'];
        }

        ///////defense
        if (!isset($input['defense'])){
            $errors['defense'] = 'Defense value is required!';
        }
        else if(trim($input['defense']) === ''){
            $errors['defense'] = 'Defense value is required!';
        }
        else{
            $data['defense'] = $input['defense'];
        }

        ///////price
        if (!isset($input['price'])){
            $errors['price'] = 'Price is required!';
        }
        else if(trim($input['price']) === ''){
            $errors['price'] = 'Price is required!';
        }
        else if($input['price'] > 1500){
            $errors['price'] = 'Price is too high!';
        }
        else{
            $data['price'] = $input['price'];
        }

        ///////description
        if (!isset($input['description'])){
            $errors['despriction'] = 'Description is required!';
        }
        else if(trim($input['description']) === ''){
            $errors['description'] = 'Description is required!';
        }
        else{
            $data['description'] = $input['description'];
        }

        if (!isset($input['imgurl'])){
            $errors['imgurl'] = 'Image url is required!';
        }
        else if(trim($input['imgurl']) === ''){
            $errors['imgurl'] = 'Image url is required!';
        }
        else{
            if(!(preg_match('/\.(jpeg|jpg|png)$/i', $input['imgurl']) === 1)){
                $errors['imgurl'] = 'Invalid image format! Valid formats: png, jpg, jpeg.';
            }
            else{
                $data['image'] = $input['imgurl'];
            }
        }
        
        return(count($errors) === 0);
    }


    $data = [];
    $errors = [];
    if (count($_POST) > 0){
        if (validate($_POST, $data, $errors, $pokemons)){
            if (sizeof($errors) === 0){
                $data['owner'] = 'admin';
                $pokemons->add($data);
                $data = [];

                $admin['cards'] = $admin['cards'] + 1;
                $users->update($admin_id, $admin);

                header("Location: profile.php");
                die;
            }
        }
    }

?>

<?php if($users->findById($_SESSION['login_id'])['name'] === 'admin'): ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/login.css">
    </head>
    <body>
        <header>
            <h1><a href="index.php">IKémon</a> > <a href="profile.php">Profile</a> > Add new card </h1>
        </header>
            <h1 id="title"> Add a new Pokémon card to the shop! </h1>
            <form class="dataform" action="" method="post" novalidate>
                Name: 
                <br>
                <input type="text" name="name" required
                value="<?php if (isset($data['name'])): echo($data['name']); endif ?>">
                <?php if (isset($errors['name'])): ?>
                    <?= $errors['name'] ?>
                <?php endif ?>
                <br>
                Type: 
                <br>
                <select id="type" name="type" required>
                    <option value="normal" selected>Normal</option>
                    <option value="fire">Fire</option>
                    <option value="water">Water</option>
                    <option value="electric">Electric</option>
                    <option value="grass">Grass</option>
                    <option value="ice">Ice</option>
                    <option value="fighting">Fighting</option>
                    <option value="poison">Poison</option>
                    <option value="ground">Ground</option>
                    <option value="psychic">Psychic</option>
                    <option value="bug">Bug</option>
                    <option value="rock">Rock</option>
                    <option value="ghost">Ghost</option>
                    <option value="dark">Dark</option>
                    <option value="steel">Steel</option>
                </select>
                <br>
                Hp:
                <br>
                <input type="number" id="hp" name="hp" required
                value="<?php if (isset($data['hp'])): echo($data['hp']); endif ?>">
                <?php if (isset($errors['hp'])): ?>
                    <?= $errors['hp'] ?>
                <?php endif ?>
                <br>
                Attack:
                <br>
                <input type="number" id="attack" name="attack" required
                value="<?php if (isset($data['attack'])): echo($data['attack']); endif ?>">
                <?php if (isset($errors['attack'])): ?>
                    <?= $errors['attack'] ?>
                <?php endif ?>
                <br>
                Defense:
                <br>
                <input type="number" id="defense" name="defense" required
                value="<?php if (isset($data['defense'])): echo($data['defense']); endif ?>">
                <?php if (isset($errors['defense'])): ?>
                    <?= $errors['defense'] ?>
                <?php endif ?>
                <br>
                Price:
                <br>
                <input type="number" id="price" name="price" required
                value="<?php if (isset($data['price'])): echo($data['price']); endif ?>">
                <?php if (isset($errors['price'])): ?>
                    <?= $errors['price'] ?>
                <?php endif ?>
                <br>
                Description:
                <br>
                <input type="textarea" id="description" name="description" required
                value="<?php if (isset($data['description'])): echo($data['description']); endif ?>">
                <?php if (isset($errors['description'])): ?>
                    <?= $errors['description'] ?>
                <?php endif ?>
                <br>
                Image:
                <br>
                <input type="text" id="imgurl" name="imgurl" required
                value="<?php if (isset($data['image'])): echo($data['image']); endif ?>">
                <?php if (isset($errors['imgurl'])): ?>
                    <?= $errors['imgurl'] ?>
                <?php endif ?>
                <br>
                <br>
                <button>Create new card</button>
            </form>


        <footer>
            <p>IKémon | ELTE IK Webprogramozás</p>
        </footer>
    </body>
    </html>
<?php else: ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <h1>Sorry! You do not have access to this page! Click <a href="index.php">here</a> to go back to the site!</h1>
    </body>
    </html>
<?php endif ?>