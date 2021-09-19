<?php
    $activityArray = array();
    $activityNameErrorMsg = "";
    $activityTimeErrorMsg = "";
    $error = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $activityName = $_POST["activityName"];
        $activityTime = $_POST["activityTime"];

        if ($activityName == "") {
            $error = true;
            $activityNameErrorMsg = "<span style='color:#FF0000'>* The field 'Activity Name' must be filled out.</span>";
        } else {
            if (array_key_exists($activityName, $activityArray)) {
                if ($activityTime != "") { // Update activity time.
                    $activityArray[$activityName] = $activityTime;
                } else { // Delete activity.
                    unset($activityArray[$activityName]);
                }
            } else { // Activity does not exists.
                if ($activityTime != "") { // Add new activity.
                    $activityArray[$activityName] = $activityTime;
                } else {
                    $error = true;
                    $activityTimeErrorMsg = "<span style='color:#FF0000'>* The field 'Activity Time' must be filled out.</span>";
                }
            }
        }
    }
?>

<html land="en">
    <head>
        <title>DWES Tarea Evaluativa 03</title>
    </head>

    <body>
        <header>
            <h1>DWES Tarea Evaluativa 03</h1>
        </header>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST">
            <label for="activityName">
                Activity name: <input type="text" name="activityName">
            </label>

            <?php // Show Activity Name error message.
                echo "$activityNameErrorMsg<br>"; // If validation is passed error message will be "".
            ?>

            <label for="activityTime">
                Activity time: <input type="time" name="activityTime">
            </label>

            <?php // Show Activity Time error message.
                echo "$activityTimeErrorMsg<br><br>"; // If validation is passed error message will be "".
            ?>

            <input type="submit">
        </form>

        <h2>Registered Activities:</h2>
        <?php
            $arrayObject = new ArrayObject($activityArray);
            $it = $arrayObject->getIterator();
    
            while ($it->valid()) {
                echo $it->key() . ": " . $it->current() . "<br>";
                $it->next();
            }
        ?>
    </body>
</html>