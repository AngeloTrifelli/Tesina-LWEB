<?php
    session_start();
    if(isset($_SESSION['codFiscUtenteLoggato'])){
        $temp = $_SESSION['soggiornoAttivo'];
        if($temp != "null"){
            if($temp['statoSoggiorno'] != "Approvato"){
                header('Location: areaUtente.php');
            }
        }
        else{
            header('Location: prenotaOra.php');
        }
    }
    else{
        header('Location: login.php');
    }

    if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA'])){
        if(isset($_POST['ANNULLA'])){
            header('Location: homeRistorante.php');
        }
    }




    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Prenota un tavolo</title>

        <style type="text/css">
            <?php include "../CSS/prenotaTavolo.css" ?>
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
        <h1>PRENOTA UN TAVOLO:</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >

                <div class="mainArea">
                    <div class="zonaSuperiore">
                        
                        <div class="zonaSx">
                            <span class="item">Inserisci la data di prenotazione:</span>
                            <input type="text" name="dataPrenotazione" class="dateInput dataPrenotazione"/>
                            <span class="item marginTop">Inserisci l'orario di prenotazione:</span>
                            <input name="oraPrenotazione" class="textInput oraPrenotazione" />
                        </div>

                        <div class="zonaDx">
                            <h2>Locazione:</h2>
                            <span>
                                <input type="radio" id="Interna" name="type" value="Interna" <?php if(isset($_POST['type'])){if($_POST['type']=="Interna"){echo 'checked';}} ?> />
                                <label for="Interna">Interna</label><br /> 
                            </span>
                            <span>
                                <input type="radio" id="Esterna" name="type" value="Esterna"  <?php if(isset($_POST['type'])){if($_POST['type']=="Esterna"){echo 'checked';}} ?> />
                                <label for="Esterna">Esterna</label>
                            </span>                        
                        </div>    

                    </div>

                    <div class="zonaBottoni">
                        <input type="submit" class="button" name="ANNULLA" value="ANNULLA" />
                        <input type="submit" class="button" name="CONFERMA" value="CONFERMA" />
                    </div>
                </div>
            </form>
        </div>

        <script>
            $(".dataPrenotazione").datepicker({
                dateFormat: 'dd-mm-yy',         

            });

            $('.oraPrenotazione').timepicker({
                timeFormat: 'HH:mm',
                dynamic: true,
                dropdown: true,
                scrollbar: true,
                interval: 15
            });

        </script>
    </body>
</html>