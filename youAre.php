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
    if (isset($_GET["submit"])) {
        if (empty($_GET["name"])) {
            echo "Fill up the input.";
        } else {
            $urlGender = "https://api.genderize.io/?name=" . $_GET["name"];
            $urlAge = "https://api.agify.io/?name=" . $_GET["name"];

            $gender = getUrl($urlGender);
            $age = getUrl($urlAge);

            if ($gender === NULL || $age === NULL) {
                echo "Please type valid name.";
            } else {
                $predictedGender = $gender;
                $predictedAge = $age;
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

        if ($data == NULL) {
            return NULL;
        } else if (strpos($url, 'genderize') !== false) {
            return $data->gender;
        } else if (strpos($url, 'agify') !== false) {
            return $data->age;
        }
    }
    ?>

    <div class="" style="border: 2px solid black; text-align:center;background-color:aquamarine;">
        <?php
        if (isset($predictedGender) && isset($predictedAge)) {
            echo "<strong>" .  $_GET["name"] . " is a " . $predictedGender . " And " . $predictedAge . " years old.</strong>";
        }
        ?>
    </div>

    <div class="container">
        <div class="landing">
            <form method="GET" action="youAre.php">
                <div class="mb-3">
                    <label class="form-label fs-5 fw-bold">Enter your name</label>
                    <input type="text" class="form-control" name="name">
                </div>
                <button type="submit" class="btn btn-outline-light" name="submit">Submit</button>
            </form>
        </div>
    </div>


    <?php
    include_once("incl/footer.php");
    ?>

</body>

</html>