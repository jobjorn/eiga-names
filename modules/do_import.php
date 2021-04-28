<?php


require_once("core.php");
require_once("connection.php");

if ($logged_in) {
    $imported_rows = str_getcsv($_POST['names'], "\n");
    foreach ($imported_rows as $row) {
        $name = str_getcsv($row);

        $name = $name[0];




        $sql = "SELECT * FROM eiga_names WHERE name = :name";
        $statement = $dbh->prepare($sql);
        $statement->bindParam(":name", $name);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        if (count($result) == 0) {
            $sql = "INSERT INTO eiga_names (name) VALUES (:name)";
            $statement = $dbh->prepare($sql);
            $statement->bindParam(":name", $name);
            $statement->execute();
            $name_id = $dbh->lastInsertId();
        } elseif (count($result) == 1) {
            $name_id = $result[0]->id;
        } else {
            die("Det är flera likadana namn i databasen");
        }

        $sql = "SELECT * FROM eiga_grades WHERE user_id = :user_id AND name_id = :name_id";
        $statement = $dbh->prepare($sql);
        $statement->bindParam(":user_id", $logged_in_user->id);
        $statement->bindParam(":name_id", $name_id);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        if (count($result) == 0) {
            $sql = "INSERT INTO eiga_grades (user_id, name_id) VALUES (:user_id, :name_id)";
            $statement = $dbh->prepare($sql);
            $statement->bindParam(":user_id", $logged_in_user->id);
            $statement->bindParam(":name_id", $name_id);
            $statement->execute();
        } elseif (count($result) == 1) {
            continue;
        } else {
            die("Samma namn är med flera gånger i eiga_grades i databasen");
        }
    }
}

header("Location: " . $root_uri);
