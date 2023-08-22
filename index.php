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
$user = new Users();
if (isset($_SESSION["id"])) {
    $logged = true;
    $id = $_SESSION["id"];
    $username = $user->getUsername($id);
}

if (isset($_GET["logout"])) {
    if (!isset($_SESSION["id"])) {
        echo "Why you trying to loggout while you not logged in ?!?!?";
    } else {
        session_unset();
        session_destroy();
        header("Location: index.php");
    }
}
?>

<body>
    <?php
    include_once("incl/navbar.php");
    ?>

    <div class="container">
        <div class="landing">
            <h1 style="font-weight:bold;">Welcome <b><span class="unique"><?php if ($logged) echo $username; ?></span></b> to <span class="unique">RandomAtor</span></h1>
        </div>
    </div>


    <?php
    include_once("incl/footer.php");
    ?>
</body>

</html>