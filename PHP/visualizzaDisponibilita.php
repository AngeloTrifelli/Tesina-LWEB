<?php
    require_once("funzioniGetPHP.php");
    require_once("funzioniPHP.php");
    session_start();


    if(isset($_SESSION['accessoPermesso'])){
        if(isset($_SESSION['datePrenotazioneCamera'])){
            $temp = $_SESSION['datePrenotazioneCamera'];
            $dataArrivo = $temp['dataArrivo'];
            $dataPartenza = $temp['dataPartenza'];

            $camereDisponibili = getCamereDisponibili($dataArrivo , $dataPartenza);

            unset($_SESSION['accessoPermesso']);
            unset($_SESSION['datePrenotazioneCamera']);
        }
    }
    else{
        if(!isset($_POST['dataArrivo']) || !isset($_POST['dataPartenza'])){
            header('Location: prenotaOra.php');
        }
        else{
            $idCamera = individuaBottoneCamereDisponibili();
            $arrayDati['idCamera'] = $idCamera;
            $arrayDati['dataArrivo'] = $_POST['dataArrivo'];
            $arrayDati['dataPartenza'] = $_POST['dataPartenza'];
            $_SESSION['prenotazioneCamera'] = $arrayDati;
            if(isset($_SESSION['codFiscUtenteLoggato'])){
                header('Location: confermaPrenotazione.php');
            }
            else{
                header('Location: login.php');
            }

        }
    }



    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Sapienza Hotel: Camere disponibili</title>

    <style>
        <?php include "../CSS/visualizzaDisponibilita.css" ?>
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
</head>


<body>
    <div class="top">
        <div class="topLeft">
            <a href="./prenotaOra.php">TORNA INDIETRO</a>    
        </div>
    </div>

    
    <h3 class="titoloImportante alignCenter">CAMERE DISPONIBILI:</h3>
    <?php
        $numeroCamere = count($camereDisponibili);
        if($numeroCamere >= 1){
    ?>
            <div class="mainContainer marginBottom">
                <form action="<?php echo $_SERVER['PHP_SELF']?>"  method="post">
                <?php
                    for($i=0 ; $i < $numeroCamere ; $i++){
                        $temp = $camereDisponibili[$i];
                ?>
                        <table class="prenotazione alignCenter" align="center">
                            <tr>
                                <td>
                                    <strong>Numero Camera</strong><br />
                                    <?php 
                                        $idCamera = $temp['idCamera'];
                                        echo substr($idCamera, 1);
                                    ?>
                                </td>
                                <td>
                                    <strong>Tipo</strong><br />
                                    <?php echo $temp['tipoCamera'];?>
                                </td>
                                <td>
                                    <strong>Prezzo giornaliero</strong><br />
                                    <?php echo $temp['prezzo']?>&euro;
                                </td>
                                <td class="hide">
                                </td>
                                <td>
                                    <input type="submit" class="button" name="<?php echo $temp['idCamera'];?>" value="SELEZIONA" />
                                </td>
                            </tr>
                        </table>
                <?php
                    }
                ?>
                    <input type="hidden" name="dataArrivo" value="<?php echo $dataArrivo;?>" />
                    <input type="hidden" name="dataPartenza" value="<?php echo $dataPartenza;?>" />
                </form>
            </div>
    <?php
        }
        else{
            echo '<p class="alignCenter scrittaCentrale marginBottom">Non sono state trovate camere disponibili...</p>';
        }
    ?>
</body>


</html>