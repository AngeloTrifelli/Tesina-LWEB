<?php
    require_once("funzioniGetPHP.php");
    require_once("funzioniPHP.php");
    session_start();


    $error = "";
    if(isset($_POST['annulla']) || isset($_POST['cambia'])){
        if(isset($_POST['annulla'])){
            unset($_SESSION['idAttivitaDaModificare']);
            header('Location: listaAttivita.php');
            exit();
        }
        else{            
            if($_POST['nomeAttivita']!="" || $_POST['linkImmagine']!="" || $_POST['descrizione']!="" || $_POST['prezzoOrario']!=""){
                modificaAttivita($_POST['idAttivita'],$_POST['nomeAttivita'],$_POST['linkImmagine'],$_POST['descrizione'],$_POST['prezzoOrario']);
                unset($_SESSION['idAttivitaDaModificare']);
                header('Location: listaAttivita.php');
                exit();
            }
            else{                
                $error="True";
            }
        
        }
    }


    

    if(isset($_SESSION['idAttivitaDaModificare']) &&  isset($_SESSION['loginType']) &&  $_SESSION['loginType'] != "Cliente"){
        $idAttivita = $_SESSION['idAttivitaDaModificare'];
    }   
    else{
        unset($_SESSION['idAttivitaDaModificare']);
        header('Location: areaUtente.php');
        exit();
    }

    

    echo '<?xml version="1.0" encoding="UTF-8?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Modifica attivita</title>

        <style>
            <?php include "../CSS/modificaAttivita.css" ?>
        </style>

            <link href="https://code.jquery.com/ui/1.13.2/themes/blitzer/jquery-ui.css" rel="stylesheet"/>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
            <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
            <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>



        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&display=swap" />
    </head>

    <body>
        <div class="containerCentrale">

            <h1>MODIFICA DATI ATTIVITA:</h1>

            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

            <div class="riga">

                    <div class="containerColumn">

                    <p><strong>Inserisci il nuovo nome dell'attivit&agrave;:</strong></p>
                    <input type="text" class="textInput" name="nomeAttivita">

                    </div>

            </div>

            <div class="riga">

                <div class="containerColumn">

                    <p><strong>Inserisci il nuovo link per l'immagine:</strong></p>
                    <input type="text" class="textInput" name="linkImmagine">

                </div>

            </div>

            <div class="riga">

                <div class="containerColumn">

                    <p><strong>Inserisci una nuova descrizione:</strong></p>
                    <textarea type="text" class="textInput descrizione" name="descrizione"></textarea>

                </div>

            </div>

            <div class="riga">

                    <div class="containerColumn">
                        <p><strong>Inserisci il nuovo prezzo orario:</strong></p>
                        <input id="inputNumber" type="number" name="prezzoOrario" />
                    </div>
            </div>


        
            <div class="spaceBetween">
                <input type="submit" class="buttonSx" value="Annulla" name="annulla" />
                <input type="submit" class="buttonDx" value="Cambia" name="cambia" />                
            </div>

            <input type="hidden" name="idAttivita" value="<?php echo $idAttivita?>">
            </form>

            <?php
                if($error=="True"){
                    echo "
                    <div class\"riga\">
                        <p class=\"errorLabel\">Cambiare almeno un dato!</p>
                    </div>";
                }
            ?>

            <div id="modificaOrari">
                <a  href="./listaOrari.php">Volevi modificare gli orari dell'attività? Clicca qui!</a>
            </div>

            <p><strong>NB: I campi lasciati vuoti non modificheranno i dati salvati</strong></p>
            

        </div>
        
        <br />

        <script>
            $("#inputNumber").on("change" ,  function(e){
                if(e.target.value < 0){
                    e.target.value = "";
                }
            })
        </script>

    </body>

</html>