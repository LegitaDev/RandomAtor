<!DOCTYPE html>
<html>

<head>
    <title>RandomAtor</title>
    <?php
    include_once("incl/header.php");
    ?>
</head>
<?php
$logged = false;
$guestDiv = false;
$user = new Users();
$guessGame = new GuessTheNumber();

if (isset($_SESSION["id"])) {
    $logged = true;
    $id = $_SESSION["id"];
    $username = $user->getUsername($id);
}
?>


<body>
    <?php
    include_once("incl/navbar.php");
    ?>

    <?php
    function randomLink()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    if (isset($_POST["nameSubmit"])) {
        $player = $_POST["name"];
        $link = $_GET["link"];
        if (empty($player)) {
            $user->errors[] = "Fill up your name";
        } elseif (!isset($player)) {
            $user->errors[] = "Please try again later";
        }


        if (empty($user->errors)) {
            $guestPlayer = "Guest-" . $player;
            $_SESSION["guestName"] = $guestPlayer; // to check who is using the link.
            $sgpGuest = $guessGame->startGamePlayer($link, $guestPlayer);
            if ($sgpGuest === false) {
                echo $guessGame->displayError();
            }
            if (empty($user->errors)) {
                $guestDiv = true;
            }
        }
    }
    ?>

    <div class="container landing">
        <div>
            <!-- Main Page -->
            <?php if (!isset($_GET["link"]) && empty($_GET["link"])) : ?>
                <?php $randomLink = randomLink(); ?>
                <div class="row">
                    <div class="col">
                        <h2><b>Welcome to <span class="unique">Guess the number </span> game <br>The game has 2 players one of them enter a number and the other should guess it <br></b></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <a href="guessNumber.php?link=<?php echo $randomLink ?>"><button type="button" class="btn btn-outline-info">Create a new game !</button></a>
                    </div>
                </div>

                <!-- Entering Player Name (Guest) -->
            <?php elseif ($logged == false && $guestDiv == false) : ?>
                <div class="row">
                    <div class="col">
                        <form method="POST" action="guessNumber.php?link=<?php echo $_GET["link"]; ?>">
                            <label><b>Player Name</b></label><input type="text" class="form-control" style="width: 100px;" name="name">
                            <button type="submit" class="btn btn-outline-info" name="nameSubmit">Submit</button>
                        </form>
                    </div>
                </div>

                <!-- If Guest Name entered  -->
            <?php elseif ($guestDiv == true) : ?>
                <div class="row">
                    <div class="col">
                        <h1><b>Welcome <span class="unique"><?php echo $player; ?></span></b></h1>
                        <h2>Waiting for your friend to join <div class="lds-ripple">
                                <div></div>
                                <div></div>
                            </div>
                        </h2>
                    </div>
                </div>

                <!-- If the user is already logged in  -->
            <?php else : ?>
                <div class="row">
                    <div class="col">
                        <h1><b>Welcome <span class="unique"><?php echo $username; ?></span></b></h1>
                        <h2>Waiting for your friend to join <div class="lds-ripple">
                                <div></div>
                                <div></div>
                            </div>
                        </h2>
                    </div>
                </div>
                <?php
                $link = $_GET["link"];
                $sgp = $guessGame->startGamePlayer($link, $username);
                if ($sgp === false) {
                    echo $guessGame->displayError();
                } elseif ($sgp === "player2Ready") {
                    echo "hi";
                }
                ?>
            <?php endif; ?>

            <!-- Fixed div , to copy the link of the room -->
            <div class="row" style="margin-top:150px;">
                <div class="col">
                    <h2><b>Invite your friend to play the game !</b></h2>
                    <table border="1" align="center">
                        <tr>
                            <td> <input type="button" id="display" value="Display All Data" /> </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" style="width: 500px;" value="https://boe.tyroni.com/workspace/lego/RandomAtor/guessNumber.php?link=<?php echo $_GET["link"]; ?>" id="inviteLink">
                    <button type="button" class="btn btn-outline-info" onclick="copyLink()">Copy link</button>
                </div>
            </div>
        </div>
    </div>



    <!-- <script src="jquery-3.6.0.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script>
        function copyLink() {
            /* Get the text field */
            var copyText = document.getElementById("inviteLink");

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            /* Copy the text inside the text field */
            navigator.clipboard.writeText(copyText.value);
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {

            $("#display").click(function() {

                $.ajax({ //create an ajax request to display.php
                    type: "GET",
                    url: "display.php",
                    dataType: "html", //expect html to be returned                
                    success: function(response) {
                        $("#responsecontainer").html(response);
                        //alert(response);
                    }

                });
            });
        });
    </script>

    <?php
    include_once("incl/footer.php");
    ?>
</body>

</html>