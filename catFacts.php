<!DOCTYPE html>
<html>

<head>
    <title>RandomAtor</title>
    <?php
    include_once("incl/header.php");
    ?>
    <link rel="stylesheet" href="assests/css/catFactsStyle.css?v=1.0">
    <link href="https://fonts.googleapis.com/css?family=Archivo+Black&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Archivo:700&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    include_once("incl/navbar.php");
    ?>

    <div class="container">
        <div class="landing">

            <?php

            $url = "https://catfact.ninja/fact";
            // $json = file_get_contents($url);
            // $data = json_decode($json);

            function getUrl($url)
            {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $response = curl_exec($ch);
                curl_close($ch);
                $data = json_decode($response);
                return $data->fact;
            }
            ?>

            <div class="card">
                <p>
                    Cat Fact !
                </p>
                <h2>
                    <?php echo getUrl($url); ?>
                </h2>

            </div>
            <img src="https://cataas.com/cat" style="border: 2px solid black;">

        </div>
    </div>


    <?php
    include_once("incl/footer.php");
    ?>

</body>

</html>