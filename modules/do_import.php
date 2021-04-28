<?php


require_once("core.php");
require_once("connection.php");

if ($logged_in) {
    $imported_rows = str_getcsv($_POST['ratings'], "\n");
    foreach ($imported_rows as $row) {
        $movie = str_getcsv($row);

        $date = $movie[0];
        $title = $movie[1];
        $year = $movie[2];
        if (strlen($year) < 4) {
            $year = 0;
        }
        $letterboxd_uri = $movie[3];
        $grade = $movie[4];

        if ($date == "Date") {
            continue;
        } else {
            $sql = "SELECT * FROM eiga_movies WHERE letterboxd_uri = :letterboxd_uri";
            $statement = $dbh->prepare($sql);
            $statement->bindParam(":letterboxd_uri", $letterboxd_uri);
            $statement->execute();

            $result = $statement->fetchAll(PDO::FETCH_OBJ);

            if (count($result) == 0) {
                $sql = "INSERT INTO eiga_movies (letterboxd_uri, title, year) VALUES (:letterboxd_uri, :title, :year)";
                $statement = $dbh->prepare($sql);
                $statement->bindParam(":letterboxd_uri", $letterboxd_uri);
                $statement->bindParam(":title", $title);
                $statement->bindParam(":year", $year);
                $statement->execute();
                $movie_id = $dbh->lastInsertId();
            } elseif (count($result) == 1) {
                $movie_id = $result[0]->id;
            } else {
                die("Det är flera filmer med samma letterboxd_uri i databasen");
            }

            $sql = "SELECT * FROM eiga_grades WHERE user_id = :user_id AND movie_id = :movie_id";
            $statement = $dbh->prepare($sql);
            $statement->bindParam(":user_id", $logged_in_user->id);
            $statement->bindParam(":movie_id", $movie_id);
            $statement->execute();

            $result = $statement->fetchAll(PDO::FETCH_OBJ);

            if (count($result) == 0) {
                $sql = "INSERT INTO eiga_grades (user_id, movie_id, grade) VALUES (:user_id, :movie_id, :grade)";
                $statement = $dbh->prepare($sql);
                $statement->bindParam(":user_id", $logged_in_user->id);
                $statement->bindParam(":movie_id", $movie_id);
                $statement->bindParam(":grade", $grade);
                $statement->execute();
            } elseif (count($result) == 1) {
                $sql = "UPDATE eiga_grades SET grade = :grade WHERE id = :id";
                $statement = $dbh->prepare($sql);
                $statement->bindParam(":grade", $grade);
                $statement->bindParam(":id", $result[0]->id);
                $statement->execute();
            } else {
                die("Det är flera betygsättningar av samma film i databasen");
            }
        }
    }
}

header("Location: " . $root_uri);
