<!DOCTYPE html>
<html>

<head>
    <title>RandomAtor</title>
    <?php
    include_once("incl/header.php");
    ?>
</head>

<body>
    <?php
    include_once("incl/navbar.php");
    ?>

    <?php
    // https://api.steampowered.com
    // https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?key=MY_KEY&steamids=76561198833370877
    $infoSuccess = false;

    if (isset($_POST["submit"]))
        if (empty($_POST["steamID"])) {
            echo "Put the steam ID.";
        } else {
            $urlSteamUser = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?key=MY_KEY&steamids=" . $_POST["steamID"];
            $urlHours = "https://api.steampowered.com/IPlayerService/GetSteamLevel/v1/?access_token=MY_TOKEN&key=MY_KEY&steamid=" . $_POST["steamID"];
            $urlOwnedGames = "https://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=MY_KEY&format=json&include_appinfo=true&steamid=" . $_POST["steamID"];
            $q = getUrl($urlSteamUser);
            $q2 = getUrl($urlHours);
            $q3 = getUrl($urlOwnedGames);

            if (empty($q->response->players)) {
                echo "user not found !";
            } else {
                $infoSuccess = true;
                // ISteamUser - GetPlayerSummaries
                $steamName = $q->response->players[0]->personaname;
                $lastOnline = $q->response->players[0]->lastlogoff;
                $accountCreated = $q->response->players[0]->timecreated;
                $steamID = $q->response->players[0]->steamid;
                $profileURL = $q->response->players[0]->profileurl;
                $profileAv = $q->response->players[0]->avatarfull;
                $status = $q->response->players[0]->profilestate;

                // IPlayerService - GetSteamLevel
                $playerLevel = $q2->response->player_level;

                // IPlayerService - GetOwnedGames
                $gamesCount = $q3->response->game_count;


                if ($gamesCount > 0) {
                    for ($i = 0; $i < $gamesCount; $i++) {
                        $gamesONames[] = $q3->response->games[$i]->name;
                    }
                    $gamesOwnedNames = json_encode($gamesONames);
                } else {
                    $gamesOwnedNames = "-";
                }


                if (empty($q->response->players[0]->loccountrycode)) {
                    $country = "🪐 Mars 🪐";
                } else {
                    $country = $q->response->players[0]->loccountrycode;
                }

                switch ($status) {
                    case 0:
                        $status = "Offline";
                        break;
                    case 1:
                        $status = "Online";
                        break;
                    case 2:
                        $status = "Busy";
                        break;
                    case 3:
                        $status = "Away";
                        break;
                    case 4:
                        $status = "Snooze";
                        break;
                    case 5:
                        $status = "looking to trade";
                        break;
                    case 6:
                        $status = "looking to play";
                        break;
                }
            }
        }

    function getUrl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);
        return $data;
    }
    ?>

    <?php if ($infoSuccess) : ?>
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 steamInfo container">
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover dashboard-task-infos table-dark">
                        <tbody>
                            <tr>
                                <td><?php echo $steamName; ?></td>
                            </tr>
                            <tr>
                                <td>Level : <?php echo $playerLevel ?></td>
                            </tr>
                            <tr>
                                <td>Status : <?php echo $status ?></td>
                            </tr>
                            <tr>
                                <td>Last Online : <?php echo gmdate("Y-m-d / H:i:s", $lastOnline); ?></td>
                            </tr>
                            <tr>
                                <td>Created in : <?php echo gmdate("Y-m-d", $accountCreated); ?></td>
                            </tr>
                            <tr>
                                <td>From : <?php echo $country; ?></td>
                            </tr>
                            <tr>
                                <td>Steam ID : <?php echo $steamID; ?></td>
                            </tr>
                            <tr>
                                <td>Games Count : <?php echo $gamesCount . "  "; ?> <a class="showGames" style="cursor: pointer;float:right;">Show Games</a>
                                </td>
                            </tr>
                            <tr class="ownedGames" style="display:none;">
                                <td>Owned Games : <?php echo $gamesOwnedNames; ?></td>
                            </tr>
                            <tr>
                                <td><a href="<?php echo $profileURL; ?>" target="_blank">Profile Link</a></td>
                            </tr>
                            <tr>
                                <td><img src="<?php echo $profileAv; ?>"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <div class="container">
        <div class="landing">
            <form method="POST" action="steam.php">
                <div class="mb-3">
                    <label class="form-label fs-5 fw-bold">Enter Steam ID</label>
                    <input type="text" class="form-control" name="steamID">
                    <div class="form-text">Example : 76561198833370877</div>
                </div>
                <button type="submit" class="btn btn-outline-light" name="submit">Submit</button>
            </form>
        </div>
    </div>
    <!-- <script src="jquery-3.6.0.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $(".showGames").click(function() {
                $(".ownedGames").toggle();
                if ($(this).text() == "Show Games") {
                    $(this).text("Hide Games");
                } else {
                    $(this).text("Show Games");
                }
            });
            // $(".showGames").click(function() {
            //     $(".ownedGames").css("display", "block");
            //     $(".showGames").text("Hide Games").addClass("hideGames").removeClass("showGames");
            // });

            // $(".hideGames").click(function() {
            //     $(".ownedGames").css("display", "none");
            //     $(".hideGames").text("Show Games").addClass("showGames").removeClass("hideGames");
            // });
        });
    </script>


    <?php
    include_once("incl/footer.php");
    ?>
</body>

</html>