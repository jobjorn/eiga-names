<?php


include("core.php");

$active['stats'] = 'class="active"';


$page_title = "Stats";
include("header.php");

/*
SELECT position, grade, COUNT(*) FROM `eiga_grades` WHERE user_id = 1 GROUP BY position, grade ORDER BY position ASC, grade DESC

SELECT grade, COUNT(*) FROM `eiga_grades` WHERE user_id = 1 GROUP BY grade ORDER BY grade DESC
*/

?>
<div class="container">
    <div class="col-md-12">
        <h1>Stats</h1>
        <?php
        $sql = "SELECT grade, COUNT(*) AS count FROM eiga_grades WHERE user_id = :user_id GROUP BY grade ORDER BY grade DESC ";
        $statement = $dbh->prepare($sql);
        $statement->bindParam(":user_id", $logged_in_user->id);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_OBJ);
        $i = 0;
        $grades = array();
        foreach ($result as $row) {
            $i++;
            $grades[$i] = $row->count;
        }

        $sql = "SELECT position, COUNT(*) AS count FROM eiga_grades WHERE user_id = :user_id GROUP BY position ORDER BY position ASC ";
        $statement = $dbh->prepare($sql);
        $statement->bindParam(":user_id", $logged_in_user->id);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_OBJ);
        $i = 0;
        $positions = array();
        foreach ($result as $row) {
            $i++;
            $positions[$i] = $row->count;
        }

        $count = max(count($grades), count($positions));
        $sum = array_sum($positions);

        $distributions = array();
        for ($i = 1; $i <= 5; $i++) {
            $distributions[$i] = distribution_count($i, $sum, 5);
        }

        $i = 0;
        for ($i = 1; $i <= $count; $i++) {
            if ($i == 1) {
                echo "<table class='table-striped barchart' id='list'>";
                echo "<tr><th></th><th><h2>Imported</h2></th><th><h2>Current</h2></th><th><h2>Target</h2></th></tr>";
            }
            echo "<tr>";
            echo "<th><h2>" . $i . "</h2></th>";
            if (isset($grades[$i])) {
                echo "<td><div style='width: " . $grades[$i] / max($grades) * 100 . "%;'>&nbsp;&nbsp;&nbsp;" . $grades[$i] . "</div></td>";
            } else {
                echo "<td>-</td>";
            }
            if (isset($positions[$i])) {
                echo "<td><div style='width: " . $positions[$i] / max($positions) * 100 . "%;'>&nbsp;&nbsp;&nbsp;" . $positions[$i] . "</div></td>";
            } else {
                echo "<td>-</td>";
            }
            if (isset($distributions[$i])) {
                echo "<td><div style='width: " . $distributions[$i] / max($distributions) * 100 . "%;'>&nbsp;&nbsp;&nbsp;" . $distributions[$i] . "</div></td>";
            } else {
                echo "<td>-</td>";
            }
            echo "</tr>";
        }
        if ($i > 0) {
            echo "</td></tr></table>";
        }

        $positions_new = array();
        $overshoot = array();
        $both = array();
        $position_i = 1;
        foreach ($distributions as $key => $dist) {
            $positions_new[$key] = $positions[$position_i];
            $both[$key] = $positions[$position_i];
            $positions_sum = $positions[$position_i];
            while ($positions_sum < $dist) {
                $position_i++;
                $positions_sum += $positions[$position_i];
                $both[$key] += $positions[$position_i];
                if (isset($overshoot[$key])) {
                    $overshoot[$key] += $positions[$position_i];
                } else {
                    $overshoot[$key] = $positions[$position_i];
                }
            }
            $position_i++;
        }



        $count = max(count($grades), count($positions_new));


        for ($i = 1; $i <= $count; $i++) {
            if ($i == 1) {
                echo "<table class='table-striped barchart barchart-alt' id='list'>";
                echo "<tr><th></th><th><h2>Imported</h2></th><th><h2>Current</h2></th><th><h2>Target</h2></th></tr>";
            }
            echo "<tr>";
            echo "<th><h2>" . $i . "</h2></th>";
            if (isset($grades[$i])) {
                echo "<td><div style='width: " . $grades[$i] / max($grades) * 100 . "%;'>&nbsp;&nbsp;&nbsp;" . $grades[$i] . "</div></td>";
            } else {
                echo "<td>-</td>";
            }
            if (isset($positions_new[$i])) {
                echo "<td><div style='width: " . $positions_new[$i] / max($both) * 100 . "%;'>&nbsp;&nbsp;&nbsp;" . $positions_new[$i] . "</div>";
                if (isset($overshoot[$i])) {
                    echo "<div class='alt' style='width: " . $overshoot[$i] / max($both) * 100 . "%;'>&nbsp;&nbsp;&nbsp;" . $overshoot[$i] . "</div>";
                }
                echo "</td>";
            } else {
                echo "<td>-</td>";
            }
            if (isset($distributions[$i])) {
                echo "<td><div style='width: " . $distributions[$i] / max($distributions) * 100 . "%;'>&nbsp;&nbsp;&nbsp;" . $distributions[$i] . "</div></td>";
            } else {
                echo "<td>-</td>";
            }
            echo "</tr>";

            $position_i++;
        }
        if ($i > 0) {
            echo "</td></tr></table>";
        }

        ?>
    </div>
</div>



<?php

include("footer.php");
