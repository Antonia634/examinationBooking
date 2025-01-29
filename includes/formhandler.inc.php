<!-- Hanterar sändning av data via formulär och skickar informationen till databasen
 Källa: https://www.youtube.com/watch?v=bOqTCDfc7Tk, https://www.youtube.com/watch?v=IagGGcC95Ig&t=6s -->
<?php

// Kontrollerar så att användaren får åtkomst till sidan genom att skicka in formuläret
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // använder htmlspecialchars för att omvandla specialtecken till entiteter för att skydda mot kodinjektioner
    $timeSlot = $_POST["timeSlot"];
    $name = htmlspecialchars($_POST["name"]);

    // Går något fel med uppkopplingen till databasen så skickas ett felmeddelande
    try {
        // Använder filen och stoppar körning av koden om filen inte hittas
        require_once "dbh.inc.php";

        /* 
        * Förbereder en SQL fråga som lägger till eller tar bort tidsslott beroende på om studentens namn är tomt eller ej.
        * Skulle användaren försöka boka en tid utan att skriva något så händer inget för användaren heller,
        * värdet på studentName skulle helt enkelt skrivas över med samma värde igen.
        */
        $query = "UPDATE scheduling SET studentName = :studentName WHERE timeSlot = :timeSlot;";

        // Lägger in värderna till SQL frågan via prepared statement för att skydda mot SQL injektioner
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":timeSlot", $timeSlot);
        $stmt->bindParam(":studentName", $name);

        // Kör prepared statement
        $stmt->execute();

        // Ser till att PDO objektet och prepared statement är tomma, skickar användaren till startsidan och stänger uppkopplingen genom att stänga ner koden
        $pdo = null;
        $stmt = null;
        header("Location: ../index.php");
        die();
    } catch (PDOException $e) {
        die("Query failed: " . $e->GetMessage());
    }

    // Skickar användaren tillbaka till startsidan
    header("Location: ../index.php");
} else {
    // Skickar användaren till startsidan om användaren fick åtkomst till sidan på ett inkorrekt sätt
    header("Location: ../index.php");
}