<?php
class GuessTheNumber
{
    private $database;
    public array $errors;
    public array $success;

    // Prepare database for use
    public function __construct()
    {
        $this->database = new Database;
    }

    public function startGamePlayer($link, $player)
    {
        if (empty($this->errors)) {
            // Create and execute query
            $query = "SELECT * FROM guessTheNumber WHERE link=:link";
            $this->database->prepare($query);
            $this->database->bind(":link", $link);
            $row = $this->database->getRow();

            if ($this->database->rowCount() == 0) {
                // First enter no link exist , so we enter Link and Player 1 name.
                $query = "INSERT INTO guessTheNumber (link, player1)";
                $query .= "VALUES (:link,:player1)";

                $this->database->prepare($query);
                $this->database->bind(":link", $link);
                $this->database->bind(":player1", $player);

                $this->database->execute();
                return true;
            } elseif ($row->player1 === $player) {
                // In case the user refresh the page so it doesn't put the same user against himself in the db.
                return true;
            } elseif (empty($row->player2)) {
                // Second enter link already exists , so we check if player 2 never entered first then enter Player 2 name.
                $query = "UPDATE guessTheNumber SET player2 = :player2 WHERE link = :link";
                $this->database->prepare($query);
                $this->database->bind(":player2", $player);
                $this->database->bind(":link", $link);
                $this->database->execute();
                return "player2Ready";
            } else {
                if (!empty($row->player1) && !empty($row->player)) {
                    // If both players 1 and 2 were already filled in , means the game already started and that link can't be used.
                    $this->errors[] = "Link already exist, Try making new game";
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function showGameDetails($link)
    {
        if (empty($this->errors)) {
            // Create and execute query
            $query = "SELECT * FROM guessTheNumber WHERE link=:link";
            $this->database->prepare($query);
            $this->database->bind(":link", $link);
            $row = $this->database->getRow();

            if ($this->database->rowCount() > 0) {
                return $row;
            }
        }
    }

    // Display Error and Success messages
    public function displayError()
    {
        if (count($this->errors) > 0) {
            $counter = count($this->errors);

            if ($counter == 0) {
                $result = "no errors";
            } else {
                $result = "<ul>";
                for ($i = 0; $i < $counter; $i++) {

                    $result .= "<div class=\"alertBoxBig\"><div class=\"alert alert-danger alertBox text-center\" role=\"alert\"><li>" . $this->errors[$i] . "</li></div></div>";
                }
                $result .= "</ul>";
            }
            return $result;
        }
    }
    public function displaySuccess()
    {
        if (count($this->success) > 0) {
            $counter = count($this->success);

            if ($counter == 0) {
                $result = "no success";
            } else {
                $result = "<ul>";
                for ($i = 0; $i < $counter; $i++) {
                    $result .= "<div class=\"alertBoxBig\"><div class=\"alert alert-success alertBox text-center\" role=\"alert\"><li>" . $this->success[$i] . "</li></div></div>";
                }
                $result .= "</ul>";
            }
            return $result;
        } else {
            return;
        }
    }
}
