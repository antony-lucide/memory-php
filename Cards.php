<?php
function Mix($list){
    $array = array_merge($list, $list);

    shuffle($array);

    foreach($array as $index => $card){
        $array[$index]['id'] = uniqid();
    }

    return $array;
}


$table = [
    ["src" => "./Image/hachim.png"],
    ["src" => "./Image/Rayan.png"],
    ["src" => "./Image/Romain.png"]
];

if(isset($_POST['shake'])){
    $mixCards = Mix($table); 
} else {
    $mixCards = $table; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeux de Carte</title>
</head>
<body>
    <div class="Jeu">
        <h1>Jeux de Carte</h1>
        <form method="post">
            <input type="submit" name="shake" value="Shuffle Cards" />
        </form>
        <?php foreach ($mixCards as $card): ?>
            <div class="carte" key="<?php echo $card['id']; ?>">
                <div>
                    <img class="front" src="<?php echo htmlspecialchars($card['src']); ?>" alt="Front-card"/>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
