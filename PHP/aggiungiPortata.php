<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');
    session_start();
    if(!isset($_SESSION['codFiscUtenteLoggato'])){
        if(!isset($_SESSION['loginType'])){
            header('Location: intro.php');
        }
    }
    else{
        header('Location: areaUtente.php');
    }
    $error="False";
    $nomePortataGiaEsistente="False";
    if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA'])){
        if(isset($_POST['ANNULLA'])){
            header('Location: visualizzaMenu.php');
            exit();
        }else{
            if(isset($_POST['tipoPortata']) && $_POST['nomePortata']!="" && $_POST['prezzo']!=""){
                $nomePortataGiaEsistente=checkNomePortata($_POST['nomePortata']);
                if($nomePortataGiaEsistente=="False"){
                aggiungiPortataAlMenu($_POST['tipoPortata'],$_POST['nomePortata'],$_POST['prezzo']);
                header('Location: visualizzaMenu.php');
                exit();
                }
            }else{
                $error="True";
            }
        }
    }

    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Aggiungi portata</title>

        <style type="text/css">
            <?php include "../CSS/aggiungiPortata.css" ?>
        </style>

        <link href="https://code.jquery.com/ui/1.13.2/themes/blitzer/jquery-ui.css" rel="stylesheet"/>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css" />

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&amp;display=swap" />
    </head>

    <body>

        <div class="containerCentrale">
            <h1>AGGIUNGI PORTATA:</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
            
            <div class="mainArea">
                    <div class="zonaSuperiore">
                        <div class="zonaSx">

                            <span class="item">Scegli il tipo di portata:</span>

                            <select id="selectInput" name="tipoPortata">
                                <option disabled selected value="Scegli">-- Scegli -- </option>
                                <option value="antipasto">Antipasto</option>
                                <option value="primo piatto">Primo piatto</option>
                                <option value="secondo piatto">Secondo piatto</option>
                                <option value="dolce">Dolce</option>
                            </select>

                            <span class="item">Inserisci il nome della portata:</span>
                            <input type="text" class="textInput" name="nomePortata" placeholder="Inserisci il nome" />

                        </div>

                        <div class="zonaDx">

                            <h2>Prezzo:</h2>
                            <input type="number" class="textInput" name="prezzo" />

                        </div>
                    </div>

                    <div class="zonaBottoni">
                        <input type="submit" class="button" name="ANNULLA" value="ANNULLA" />
                        <input type="submit" class="button" name="CONFERMA" value="CONFERMA" />

                    </div>

            </div>
            </form>
            <?php
        if ($error=="True"){
            echo "
                <div class\"riga\">
                <p class=\"errorLabel\">Dati mancanti!</p>
                </div>
            ";
            }
            if ($nomePortataGiaEsistente=="True"){
                echo "
                    <div class\"riga\">
                    <p class=\"errorLabel\">Nome della portata già presente nel menu!</p>
                    </div>
                ";
                }
    ?>
        </div>
    </body>
</html>
