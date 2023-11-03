<?php
if (!isset($_POST['tk']) or !isset($_POST['num'])) {
    header('Location: index.html');
    exit();
}

$tk = $_POST["tk"];

if ($tk == "btk" and !is_numeric($_POST['num'])) {
    $err = "Hibás bemenet! [BTK csak szám alapján kereshető]";
} elseif ($tk == "btk") {
    $num = intval($_POST["num"]);
}

if ($tk == "btk" and @$num > 465) {
    $err = "A pont nem található! [túl magas index]";
}

if ($_POST["num"] == "") {
    header('Location: index.html');
    exit();
}

if ($tk == "ptk" and intval(substr($_POST['num'], 0, strpos($_POST['num'], ':'))) > 8) {
    $err = "A pont nem található! [túl magas index]";
} else {
    $num = $_POST["num"];
}

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= strtoupper($tk) . " " . $num . ". §" ?>
    </title>
    <link rel="stylesheet" href="style.css">
</head>

<a class="back" href="index.html">←</a>

<?php

if (isset($err)) {
    echo "<h1>" . $err . "</h1>";
    exit();
}

if ($tk == "btk") {
    $tkarr = file_get_contents("btk.json");
} elseif ($tk == "ptk") {
    $tkarr = file_get_contents("ptk.json");
}

$tkarr = json_decode($tkarr, true);

foreach ($tkarr['children'] as $par) {
    if ($par['identifier'] == $num and $par['__type__'] == "Article") {
        if (isset($par['children'])) {
            $pont = $par['children'];
        }
        ?>
        <h1>
            <?= strtoupper($tk) . " " . $num . ". §" ?>
        </h1>
        <?php
        foreach ($pont as $valpont) {
            if (isset($valpont['intro'])) {
                echo "<p>" . $valpont['intro'] . "</p>";
            } elseif (isset($valpont['text'])) {
                echo "<p>" . $valpont['text'] . "</p>";
            }
            if (isset($valpont['children'])) {
                echo "<ol type='a'>";
                foreach ($valpont['children'] as $child) {
                    if (isset($child['text'])) {
                        echo "<li>" . $child['text'] . "</li>";
                    }
                    if (isset($child["intro"])) {
                        echo "<li>" . $child['intro'] . "</li>";
                    }
                    if (isset($child["children"])) {
                        echo "<ol type='a'>";
                        foreach ($child["children"] as $child2) {
                            if (isset($child2['text'])) {
                                echo "<li>" . $child2['text'] . "</li>";
                            }
                            if (isset($child2["intro"])) {
                                echo "<li>" . $child2['intro'] . "</li>";
                            }
                        }
                        echo "</ol>";
                    }

                }
                echo "</ol>";
            }
            if (isset($valpont['wrap_up'])) {
                echo "<p>" . $valpont['wrap_up'] . "</p>";
            }
        }
        exit();
    }
}

?>