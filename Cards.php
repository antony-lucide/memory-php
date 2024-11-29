<?php
session_start();

// Memory Game class
class MemoryGame {
    private $table = [
        ["src" => "./Image/Rayan.png"],
        ["src" => "./Image/Romain.png"],
        ["src" => "./Image/davon.png"],
        ["src" => "./Image/hachim.png"]
    ];

    public function __construct() {
        if (!isset($_SESSION['memory_game'])) {
            $this->resetGame();
        }
    }

    private function resetGame() {
        $cards = $this->shuffleCards($this->table);
        $_SESSION['memory_game'] = [
            'cards' => $cards,
            'flipped' => [],
            'found_pairs' => [],
            'attempts' => 0,
            'message' => '' // Initialize with an empty string
        ];
    }

    private function shuffleCards($list) {
        $cards = array_merge($list, $list); // Create pairs
        shuffle($cards); // Shuffle the cards
        foreach ($cards as $index => $card) {
            $cards[$index]['id'] = $index; 
        }
        return $cards;
    }

    public function handleFlip($index) {
        if (!isset($_SESSION['memory_game']['flipped'])) {
            $_SESSION['memory_game']['flipped'] = [];
        }
        if (!isset($_SESSION['memory_game']['found_pairs'])) {
            $_SESSION['memory_game']['found_pairs'] = [];
        }

        if (!in_array($index, $_SESSION['memory_game']['flipped']) &&
            !in_array($index, $_SESSION['memory_game']['found_pairs'])) {

            $_SESSION['memory_game']['flipped'][] = $index;

            // Check if two cards are flipped
            if (count($_SESSION['memory_game']['flipped']) === 2) {
                $this->checkMatch();
            }
        }
    }

    private function checkMatch() {
        $flipped = $_SESSION['memory_game']['flipped'];
        $cards = $_SESSION['memory_game']['cards'];

        if ($cards[$flipped[0]]['src'] === $cards[$flipped[1]]['src']) {
            // Cards match
            $_SESSION['memory_game']['found_pairs'] = array_merge($_SESSION['memory_game']['found_pairs'], $flipped);
            $_SESSION['memory_game']['message'] = "You found a pair!";
        } else {
            // No match
            $_SESSION['memory_game']['message'] = "No match, try again!";
        }

        // Increase attempts
        $_SESSION['memory_game']['attempts']++;

        // Reset flipped cards
        $_SESSION['memory_game']['flipped'] = [];
    }

    public function reset() {
        $this->resetGame();
    }

    public function getGameState() {
        return $_SESSION['memory_game'];
    }
}

// Instantiate the game
$game = new MemoryGame();

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset'])) {
        $game->reset();
    } elseif (isset($_POST['index'])) {
        $game->handleFlip((int)$_POST['index']);
    }
}

// Get the current game state
$state = $game->getGameState();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game</title>
</head>
<body>
    <h1>Memory Game</h1>

    <!-- Display message -->
    <?php if (isset($state['message']) && $state['message']): ?>
        <div class="message"><?php echo htmlspecialchars($state['message']); ?></div>
    <?php endif; ?>

    <!-- Game board -->
    <div class="Jeux">
        <?php 
        if (isset($state['cards']) && is_array($state['cards'])):
            foreach ($state['cards'] as $index => $card): 
        ?>
            <div class="carte">
                <?php if (in_array($index, $state['found_pairs'])): ?>
                    <!-- Card already found -->
                    <div class="card">
                        <div class="card-inner">
                            <div class="card-back">
                                <img src="<?php echo htmlspecialchars($card['src']); ?>" alt="Card">
                            </div>
                        </div>
                    </div>
                <?php elseif (in_array($index, $state['flipped'])): ?>
                    <!-- Card currently flipped -->
                    <div class="card">
                        <div class="card-inner">
                            <div class="card-back">
                                <img src="<?php echo htmlspecialchars($card['src']); ?>" alt="Card">
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Card not flipped -->
                    <form method="post">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <button class="card">
                            <div class="card-inner">
                                <div class="card-front">
                                    <img src="./Image/dos.png" alt="Back of Card">
                                </div>
                            </div>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php 
            endforeach;
        else:
            echo "Error: Game data is missing or corrupted. Please reset the game.";
        endif;
        ?>
    </div>

    <!-- Reset button -->
    <form method="post">
        <button type="submit" name="reset">Reset Game</button>
    </form>
</body>
</html>
