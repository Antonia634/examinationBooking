<?php
    // Använder filen och stoppar körning av koden om filen inte hittas
    require_once "includes/dbh.inc.php";

    // Förbereder en SQL fråga
    $query = "SELECT * FROM scheduling";

    // Lägger in värderna till SQL frågan via prepared statement för att skydda mot SQL injektioner
    $stmt = $pdo->prepare($query);
    // Kör prepared statement
    $stmt->execute();
    // Sparar datan från databasen i en associative array så att namnen kan hämtas via tidslotten som är kopplad till denna
    $allNames = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Bokningssystem</title>
    <!-- import av bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="text-bg-dark">
    <h1 class="text-center my-3 text-light">Bokning för muntlig redovisning</h1>
    <!-- Tabell som visar tiderna, hämtar informationen från databasen sparat i $allNames arrayen -->
    <table class="container table table-bordered border-black align-middle rounded-4 overflow-hidden">
        <thead>
        <tr class="fs-5">
                <th>Tid</th>
                <th>Person inbokad</th>
                <th>Boka/avboka</th>
            </tr> 
        </thead>
        <tbody class="table-group-divider border-black">
            <!-- Fyller i tabellen med datan från $allNames arrayen,
            använder substr för att hämta de två sista siffrorna som representerar tiden för slotten i databasen.
            https://www.php.net/manual/en/function.substr.php -->
            <?php 
                foreach ($allNames as $slots) {
                    echo '<tr><td class="fw-bold">' . substr($slots["timeSlot"], -2) . '.00</td>';
                    /* 
                    * Kontrollerar om det är något namn skrivet på tidsslotten.
                    * Är det namn skrivet så skrivs namnet ut och valet att avboka tiden via ett formulär (post, säkrare än get).
                    * Är inget namn skrivet så visas tidsslotten som ledig och möjligheten att boka tiden via ett formulär visas,
                    * Här skriver användaren in sitt namn för att boka.
                    * Ska tiden avbokas så används samma php fil men värdet för studentens namn skickas som tomt.
                    */
                    if ($slots["studentName"] != "") {
                        // Omvandlar specialtecken för att säkerställa att inga kodinjektioner sker från datan från databasen
                        echo '<td class="fw-bold table-danger">Bokad av: ' . htmlspecialchars($slots["studentName"]) . '</td>';
                        // Skapar ett formulär som sänder vidare värdet timeSlot (tiden för bokningen) till formuläret vid ett knapptryck.
                        echo '<td><form action="includes/formhandler.inc.php" method="post"><input type="hidden" value="' . $slots["timeSlot"] . '" name="timeSlot"/><input type="hidden" value="" name="nameInput"/><button class="btn btn-danger my-2" type="submit">Avboka tid</button></form></tr>';
                    } else {
                        echo '<td class="table-success fw-bold">Ledig tid</td>';
                        echo '<td><form class="form-floating" action="includes/formhandler.inc.php" method="post"><input type="hidden" value="' . $slots["timeSlot"] . '" name="timeSlot"/><input type="text" class="form-control" id="nameInput" placeholder="Ange ditt namn" name="name"><label for="nameInput">Ange ditt namn</label><button class="btn btn-primary my-2" type="submit">Boka tid</button></form></tr>';
                    }     
                }
            ?>
        </tbody>
    </table>    
</body>
</html>