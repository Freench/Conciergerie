<?php

$servername = 'localhost';
$username = 'root';
$dbname = 'concierge';
$password = '';
//On établit la connexion
try{
    // CREATION DE LA BASE DE DONNE
    // $dbco = new PDO("mysql:host=$servername", $username, $password);
    // $sql = "CREATE DATABASE concierge";

    $dbco = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    //On définit le mode d'erreur de PDO sur Exception
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    var_dump($_GET['typeIntervention']);
    $dateInter = $_GET['dateIntervention'];
    $inter = $_GET['typeIntervention'];
    $etage = $_GET['etage'];
    if( isset($dateInter) && !empty($dateInter) && isset($etage) && !empty($etage) && isset($inter) && !empty($inter)){

        $sth = $dbco->prepare("
            INSERT INTO Interventions(TypeIntervention,DateIntervention,Etage)
            VALUES (:inter, :dateInter, :etage)");
        $sth->bindParam(':inter',$inter);
        $sth->bindParam(':dateInter',$dateInter);
        $sth->bindParam(':etage',$etage);
        $sth->execute();
    }
    //CREATION DUNE TABLE
    // $sql = "CREATE TABLE Interventions(
    //         IdIntervention INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    //         TypeIntervention VARCHAR(255) NOT NULL,
    //         DateIntervention DATE NOT NULL,
    //         Etage INT NOT NULL)"; 
    
    // $sql = "INSERT INTO Interventions(TypeIntervention,DateIntervention,Etage) 
    //         VALUES ('Changement de disjoncteur','2021-10-06',4)";
    // $dbco->exec($sql);
}
/*On capture les exceptions si une exception est lancée et on affiche
 *les informations relatives à celle-ci*/
catch(PDOException $e){
  echo "Erreur : " . $e->getMessage();
}
header('Refresh: 1; URL=http://localhost/');

?>