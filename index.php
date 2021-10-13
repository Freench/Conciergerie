<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Le concierge</title>
</head>
<body>
    <h1>Gestionnaire de la conciergerie</h1>
    <form method="get" action="/">
    <table class="table table-striped table-dark">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Intervention</th>
                <th scope="col">Date</th>
                <th scope="col">Etage</th>
                <th scope="col">Supprimer</th>
                <th scope="col">Modifier</th>
            </tr>
        </thead>
        <tbody>
        <?php
            if(isset($_GET['delete']) && $_GET['delete']){
                delete($_GET['delete']);
            }
            if(isset($_GET['type']) && $_GET['type']=="insert"){
                $dateInter = $_GET['dateIntervention'];
                $inter = $_GET['typeIntervention'];
                $etage = $_GET['etage'];
                if( isset($dateInter) && !empty($dateInter) && isset($etage) && !empty($etage) && isset($inter) && !empty($inter)){
                    insert($inter, $dateInter, $etage);
                }
            }
            if(isset($_GET['type']) && $_GET['type']=='valider'){
                $id = $_GET['id'];
                $dateInter = $_GET['dateIntervention'];
                $inter = $_GET['typeIntervention'];
                $etage = $_GET['etage'];
                update($inter, $dateInter, $etage, $id);
            }
            if(isset($_GET['type']) && $_GET['type']=="select"){
                displaySelection(select($_GET['typeIntervention'], $_GET['dateIntervention'], $_GET['etage']));
            }else{
                displaySelection(select("","",""));
            }


        ?>
        </tbody>
    </table>
    </form>
    <form id="form-modify" method="get" action="/">
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Intervention</th>
                    <th scope="col">Date</th>
                    <th scope="col">Etage</th>
                    <th scope="col">Supprimer</th>
                    <th scope="col">Modifier</th>
                    <th scope="col">Sélectionner</th>
                </tr>
            </thead>
            <tbody>
                <tr class="last-line">
                <td><input type='hidden' name="id" value = "<?= modifier('IdIntervention')?>" /> <?= modifier('IdIntervention')?></td>
                <td><textarea name="typeIntervention"><?= modifier('TypeIntervention')?></textarea></td>
                <td><input type="date" name="dateIntervention" value="<?=  modifier('DateIntervention')?>" /></td>
                <td><input type="integer" name="etage" value="<?=  modifier('Etage')?>" /></td>
                <td><input type="submit" name="type" value="insert"/></td>
                <td><input type="submit" name="type" value="valider"/></td>
                <td><input type="submit" name="type" value="select" /></td>
                </tr>
            </tbody>
        </table>
    </form>

    <?php
    function connect(){
        $servername = 'localhost';
        $username = 'francisp';
        $dbname = 'francisp_';
        $password = 'tk3p/odV3HLAig==';
        //On établit la connexion
        try
        {
            $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            //On définit le mode d'erreur de PDO sur Exception
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           return $bdd;
        }
        catch(PDOException $e){
            echo "Erreur : " . $e->getMessage();
            /*On capture les exceptions si une exception est lancée et on affiche
             *les informations relatives à celle-ci*/
        }
    }

    function modifier($what){
        if(!isset($_GET['update'])){
            return "";
        }else{
            $id = $_GET['update'];
            return selectThingWithId($what, $id);
        }
    }

    function selectThingWithId($thing, $id){
        $requete = 'SELECT * FROM interventions WHERE IdIntervention = ?';
        $sth = connect()->prepare($requete);
        $sth->execute([$id]);
        $resultat = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $resultat[0][$thing];
    }

    function select($inter, $date, $etage){
        $requete = 'SELECT * FROM interventions WHERE 1=1 ';
        $parameters = [];
        if(!empty($inter)){
            $requete .= '&& TypeIntervention = ? ';
            array_push($parameters, $inter);
        }
        if(!empty($date)){
            $requete .= '&& DateIntervention = ? ';
            array_push($parameters, $date);
        }
        if(!empty($etage)){
            $requete .= '&& Etage = ? ';
            array_push($parameters, $etage);

        }
        $sth = connect()->prepare($requete);
        $sth->execute($parameters);
        $resultat = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $resultat;
    }
    function insert($inter, $dateInter, $etage){
        if(!empty($dateInter) && !empty($etage) && !empty($inter)){
            $sth = connect()->prepare("
                INSERT INTO interventions(TypeIntervention,DateIntervention,Etage)
                VALUES (:inter, :dateInter, :etage)");
            $sth->bindParam(':inter',$inter);
            $sth->bindParam(':dateInter',$dateInter);
            $sth->bindParam(':etage',$etage);
            $sth->execute();
        }
    }
    function delete($id){
        if(!empty($id)){
            $requete = 'DELETE FROM interventions WHERE idIntervention = :id';
            $sth = connect()->prepare($requete);
            $sth->bindParam(':id', $id);
            $sth->execute();
        }
        return "La suppression a échoué";
    }
    function update($inter, $date, $etage, $id){
        $requete = 'UPDATE interventions SET ';
        $parameters = [];
        $first = true;
        if(!empty($inter)){
            $comma = addComma($first);
            $first = $comma[1];
            $requete .= $comma[0];
            $requete .= 'TypeIntervention = ? ';
            array_push($parameters, $inter);
        }
        if(!empty($date)){
            $comma = addComma($first);
            $first = $comma[1];
            $requete .= $comma[0];
            $requete .= 'DateIntervention = ? ';
            array_push($parameters, $date);
        }
        if(!empty($etage)){
            $comma = addComma($first);
            $first = $comma[1];
            $requete .= $comma[0];
            $requete .= 'Etage = ? ';
            array_push($parameters, $etage);
        }
        $requete .= 'WHERE IdIntervention = '.$id;
        $sth = connect()->prepare($requete);
        $sth->execute($parameters);

    }
    function addComma($first){
        if(!$first){
            return ([', ', false]);
        }
        else{return (['', false]);}
    }
    function displaySelection($table){
        foreach($table as $entree){
            echo '<tr> <th scope="row">'.$entree['IdIntervention'].'</th>
            <td>'.$entree['TypeIntervention'].'</td>
            <td>'.$entree['DateIntervention'].'</td>
            <td>'.$entree['Etage'].'</td>
            <td> <form method="get"><input type="hidden" name="delete" value='.$entree['IdIntervention'].'> <input type="submit"  value="Supprimer"></form></td>
            <td> <form method="get"><input type="hidden" name="update" value='.$entree['IdIntervention'].'> <input type="submit"  value="Modifier"> </form></td>
            </tr>';
        }
    }

    ?>
</body>
</html>