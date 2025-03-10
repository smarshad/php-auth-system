<?php

require __DIR__.'/config/DbConnection.php';

try{
    $db   = DbConnection::getInstance();
    $con = $db->getConnection();
    $stmt = $con->prepare("SELECT * FROM users LIMIT 4");
    $stmt->execute();
    $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $exception){
    $db->errorLog($exception);
    die("Connection Error : Please Check Error Log File.");
}
print_r($vendors);