<!-- Kopplar upp användaren till databasen
 Källa: https://www.youtube.com/watch?v=tHKsZdS8Oug -->
 <?php

// Information om uppkoppling till databasen
 $serverInfo = "mysql:host=localhost;dbname=username";
 $dbusername = "username";
 $dbpassword = "password";

 // Försöker att skapa ett PDO objekt utifrån informationen om databasen, går något fel så skrivs ett felmeddelande ut
 try {
    $pdo = new PDO($serverInfo, $dbusername, $dbpassword);
    // Ger en exception om det blir något fel när pdo's attribut sätts
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 } catch (PDOException $e) {
    // Hämtar felmeddelandet om något gick fel och skriver ut detta
    echo "Connection failed: " . $e->getMessage();
 }