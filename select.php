<?php

$servername = 'localhost';
$username = 'francisp';
$dbname = 'francisp_';
$password = 'tk3p/odV3HLAig==';
//On établit la connexion
try{
    $dbco = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    //On définit le mode d'erreur de PDO sur Exception
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sth = $dbco->prepare("SELECT * FROM Interventions");
    $sth->execute();
    $resultat = $sth->fetchAll(PDO::FETCH_ASSOC);
                /*print_r permet un affichage lisible des résultats,
                 *<pre> rend le tout un peu plus lisible*/
                echo '<pre>';
                print_r($resultat);
                echo $resultat[0];
} 
catch(PDOException $e){
echo "Erreur : " . $e->getMessage();
}
header('Refresh: 5; URL=http://localhost/?resultat='.$resultat[0]['IdIntervention']);
?>