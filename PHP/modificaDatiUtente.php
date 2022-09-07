<?php
    require_once('funzioniPHP.php');

    if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA'])){
        if(isset($_POST['ANNULLA'])){
            header('Location: datiPersonali.php');
        }
        else{
            // Modifica dati utente 
        }
    }
    else{
        $datoDaModificare = individuaDatoDaModificare();
        die(var_dump($datoDaModificare));
        if($datoDaModificare == "null"){    //Se il controllo va a buon fine significa che non sono arrivato in questa pagina mediante datiPersonali.php
            //Controlla se l'utente è loggato o meno e fai l'header di conseguenza
        }
    }


?>




<?php
    echo '<?xml version="1.0" encoding="UTF-8?>'
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Modifica dati</title>

    <style>
        <?php include "../CSS/modificaDatiUtente.css" ?>
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" />
</head>

<body>
    <div class="containerCentrale">
    <h1>MODIFICA DATI:</h1>
        <div class="zonaDati">
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                <div class="zonaInput">
                    <input type="text" class="textInput"   placeholder="inserisci il nome" />
                </div>
                <div class="zonaBottoni">
                    <input type="submit" class="button" value="ANNULLA" name="ANNULLA" /> 
                    <input type="submit" class="button" value="CONFERMA" name="CONFERMA" />
                </div>
            </form>
        </div>
    </div>




</body>
</html>