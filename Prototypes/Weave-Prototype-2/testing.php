<?php
    require_once "model/dbInteract.php";

    //invalid SQL syntax
    DBConnection::create("INSERT INTO VALUES VALUES (id, username) VALUES (:id, :username)", ["testingID", "testingUsername"], [":id", ":username"]);
    //read the new record
    //print_r(DBConnection::read("SELECT id, username FROM account_data WHERE id = :testID", ["testingID"], [":testID"]));
    //update the information
    //DBConnection::update("  UPDATE account_data SET id = :newID, username = :newUsername WHERE id = :oldID", 
                            // ["testingID2", "testingUsername2", "testingID"], 
                            // [":newID", "newUsername", ":oldID"]);
    //delete two records
    //DBConnection::delete("DELETE FROM account_data WHERE username = 'duplicateUsername'");
