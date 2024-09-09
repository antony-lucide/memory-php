<?php
session_start();

class MemoryGame {
    private $table = [
        ["src" => "./Image/Rayan.png"],
        ["src" => "./Image/Romain.png"]
    ];

    public function __construct() {
        $this->initializeGame();
        $this->handlePostRequests();
    }

    private function initializeGame() {
        if (!isset($_SESSION['memory_game'])) {
            $_SESSION['memory_game'] = [
                'images' => $this->mix($this->table),
                'flipped' => [],
                'found_pairs' => [],
                'attempts' => 0
            ];
        }
    }

    private function mix($list) {
        $array = array_merge($list, $list); // Double the list for pairs
        shuffle($array);

        // Add unique ID for each card
        foreach ($array as $index => $card) {
            $array[$index]['id'] = uniqid();
        }

        return $array;
    }

    private function handlePostRequests() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['index'])) {
                $this->handleCardFlip((int)$_POST['index']);
            } elseif (isset($_POST['shuffle'])) {
                $_SESSION['memory_game'] = [
                    'images' => $this->mix($this->table),
                    'flipped' => [],
                    'found_pairs' => [],
                    'attempts' => 0
                ];
            }
        }
    }

    private function handleCardFlip($cardIndex) {
        if (!in_array($cardIndex, $_SESSION['memory_game']['flipped'])) {
            $_SESSION['memory_game']['flipped'][] = $cardIndex;

            if (count($_SESSION['memory_game']['flipped']) == 2) {
                $_SESSION['memory_game']['attempts']++;
                
                $firstCardIndex = $_SESSION['memory_game']['flipped'][0];
                $secondCardIndex = $_SESSION['memory_game']['flipped'][1];

                if ($_SESSION['memory_game']['images'][$firstCardIndex]['src'] === $_SESSION['memory_game']['images'][$secondCardIndex]['src']) {
                    // Cards match
                    $_SESSION['memory_game']['found_pairs'][] = $firstCardIndex;
                    $_SESSION['memory_game']['found_pairs'][] = $secondCardIndex;
                }

                // Reset flipped cards after checking
                $_SESSION['memory_game']['flipped'] = [];
            }
        }
    }

    public function getCards() {
        return isset($_SESSION['memory_game']['images']) ? $_SESSION['memory_game']['images'] : [];
    }
}

$game = new MemoryGame();
$mixCards = $game->getCards();
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
        <input type="submit" name="shuffle" value="Shuffle Cards" />
    </form>
    <div class="Jeux">
        <?php if (!empty($mixCards)): ?>
            <?php foreach ($mixCards as $index => $card): ?>
                <div class="carte">
                    <input type="checkbox" id="card-<?php echo htmlspecialchars($card['id']); ?>" class="card-toggle"/>
                    <label for="card-<?php echo htmlspecialchars($card['id']); ?>" class="card">
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
        <?php else: ?>
            <p>No cards to display.</p>
        <?php endif; ?>
    </div>
</body>
</html>
