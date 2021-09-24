<?php
    $activityArray = array();
    $activityNameErrorMsg = "";
    $activityTimeErrorMsg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        validateInputData();
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

        <section>
            <h2>Agenda:</h2>
            <?php showRegisteredActivities();?>
        </section>

        <h2>New Activity:</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <label for="activityName">
                Activity name: <input type="text" name="activityName"/>
            </label>

            <?php echo getActivityNameErrorMsg();?>

            <label for="activityTime">
                Activity time: <input type="time" name="activityTime"/>
            </label>

            <?php echo getActivityTimeErrorMsg();?>

            <!-- Add hidden input containing a JSON string with the registered activities. -->
            <!-- JSON encoded string uses double quotes so value attribute must use simple quotes. -->
            <input type="hidden" name="activitiesJSon" value='<?php echo json_encode($activityArray);?>'/>

            <input type="submit"/>
        </form>

        <?php
            function validateInputData() {
                global $activityArray;
                global $activityNameErrorMsg;
                global $activityTimeErrorMsg;

                // Decode json string containing the registered activities.
                if (isset($_POST["activitiesJSon"])) {
                    $activityArray = json_decode($_POST["activitiesJSon"], true);
                }

                $activityName = (isset($_POST["activityName"])) ? $_POST["activityName"] : "";
                $activityTime = (isset($_POST["activityTime"])) ? $_POST["activityTime"] : "";
                $isActivityTimeEmpty = ($activityTime == "");
        
                if ($activityName == "") {
                    $activityNameErrorMsg = "<span style='color:#FF0000'>* The field 'Activity Name' must be filled out.</span>";
                } else {
                    if (array_key_exists($activityName, $activityArray)) {
                        if ($isActivityTimeEmpty) { // Delete activity.
                            unset($activityArray[$activityName]);
                        } else { // Update activity time.
                            $activityArray[$activityName] = $activityTime;
                        }
                    } else { // Activity does not exists.
                        if ($isActivityTimeEmpty) { 
                            $activityTimeErrorMsg = "<span style='color:#FF0000'>* The field 'Activity Time' must be filled out.</span>";
                        } else { // Add new activity.
                            $activityArray[$activityName] = $activityTime;
                        }
                    }
                }
            }

            function getActivityNameErrorMsg() {
                global $activityNameErrorMsg;
                // If there is no error the message will be "".
                return $activityNameErrorMsg."<br>";
            }

            function getActivityTimeErrorMsg() {
                global $activityTimeErrorMsg;
                // If there is no error the message will be "".
                return $activityTimeErrorMsg."<br><br>";
            }

            function showRegisteredActivities() {
                global $activityArray;
                global $activityArrayJSon;

                $arrayObject = new ArrayObject($activityArray);
                $it = $arrayObject->getIterator();
        
                // Show a list with all registered activities.
                echo "<ul>";
                while ($it->valid()) {
                    echo "<li>" . $it->key() . ": " . $it->current() . "</li>";
                    $it->next();
                }
                echo "</ul>";
            }
        ?>
    </body>
</html>