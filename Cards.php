<?php
function Mix($list) {
    $array = array_merge($list, $list); 
    shuffle($array);

    foreach($array as $index => $card) {
        $array[$index]['id'] = uniqid();
    }

    return $array;
}

$table = [
    ["src" => "./Image/Rayan.png"],
    ["src" => "./Image/Romain.png"]
];

$mixCards = Mix($table);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeux de Carte</title>
</head>
<body>
    <h1>Jeux de Carte</h1>
    <form method="post">
        <input type="submit" name="shake" value="Shuffle Cards" />
    </form>
    <div class="Jeux">
        <?php foreach ($mixCards as $card): ?>
            <div class="carte">
                <input type="checkbox" id="card-<?php echo $card['id']; ?>" class="card-toggle"/>
                <label for="card-<?php echo $card['id']; ?>" class="card">
                    <div class="card-inner">
                        <div class="card-front">
                            <img src="./Image/dos.png" alt="Front-card"/>
                        </div>
                        <div class="card-back">
                            <img src="<?php echo htmlspecialchars($card['src']); ?>" alt="Back-card"/>
                        </div>
                    </div>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
