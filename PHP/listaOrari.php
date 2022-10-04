<?php
require_once("funzioniGetPHP.php");
require_once('funzioniPHP.php');

    session_start();
    if(!isset($_SESSION['codFiscUtenteLoggato'])){
        if(!isset($_SESSION['loginType'])){
            header('Location: intro.php');
            exit();
        }
    }
    else{
        header('Location: areaUtente.php');
        exit();
    }

    $modificaOrariUpdateRistorante="False";
    $orariUpdateRistorante=array();
    if($_SESSION["loginType"]=="Admin"){
        $modificaOrariUpdateRistorante="True";
        $orariUpdateRistorante=getOrariUpdateRistorante();
        $oraInizioUpdate=substr($orariUpdateRistorante['oraInizioUpdate'],0,5);
        $oraFineUpdate=substr($orariUpdateRistorante['oraFineUpdate'],0,5);
    }

    $arrayAttivita=getAttivita();
    $ristorante=getOrariRistorante();
    $oraAperturaPranzo=substr($ristorante['aperturaPranzo'],0,5);
    $oraChiusuraPranzo=substr($ristorante['chiusuraPranzo'],0,5);
    $oraAperturaCena=substr($ristorante['aperturaCena'],0,5);
    $oraChiusuraCena=substr($ristorante['chiusuraCena'],0,5);

    if(isset($_POST['bottonePremuto'])){
        $idAttivita=individuaBottoneidAttivita();
        $_SESSION['idAttivita']=$idAttivita;
        header('Location: modificaOrari.php');
        exit();
    }
   

    
    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Lista orari</title>

        <style>
            <?php include "../CSS/listaOrari.css" ?>
        </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    </head>


    <body>
        <div class="top">

            <div class="topLeft">               
                <a href="./areaUtente.php">TORNA NELL'AREA UTENTE</a>  
            </div>

            <h1 class="alignCenter">LISTA ORARI</h1>
           
            <div style="width: 18.5%;"></div>
               
        </div>

        <?php
            if($modificaOrariUpdateRistorante=="True"){
        ?>

        <h3 class="titoloImportante alignCenter">ORARI UPDATE RISTORANTE:</h3>

        <div class="mainContainer">
            
                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Ora inizio update:</strong><br /><?php echo $oraInizioUpdate;?></td>
                        
                        <td><strong>Ora fine update:</strong><br /><?php echo $oraFineUpdate;?></td>
                                  
                        <form action="./modificaOrari.php"  method="post">
                        <td><input type="submit" class="button" name="updateRistorante" value="MODIFICA" />  </td>
                        </form> 
                    </tr>
                </table>
        
        </div>

        <?php
            }
        ?>

        <h3 class="titoloImportante alignCenter">RISTORANTE:</h3>

        <div class="mainContainer">
            
                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Apertura pranzo:</strong><br /><?php echo $oraAperturaPranzo;?></td>
                        
                        <td><strong>Chiusura pranzo:</strong><br /><?php echo $oraChiusuraPranzo;?></td>
                        
                        <td><strong>Apertura cena:</strong><br /><?php echo $oraAperturaCena;?></td>
                        
                        <td><strong>Chiusura cena:</strong><br /><?php echo $oraChiusuraCena;?></td>
                  
                        <form action="./modificaOrari.php"  method="post">
                        <td><input type="submit" class="button" name="ristorante" value="MODIFICA" />  </td>
                        </form> 
                    </tr>
                </table>
         
        </div>

        <h3 class="titoloImportante alignCenter">ATTIVIT&Agrave;:</h3>
        <div class="mainContainer">

        <?php
            for($i=0;$i<count($arrayAttivita);$i++){
                    $attivita=$arrayAttivita[$i];
                    $oraAperturaAttivita=substr($attivita['oraApertura'],0,5);
                    $oraChiusuraAttivita=substr($attivita['oraChiusura'],0,5);
                ?>
            
                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Nome:</strong><br /><?php echo $attivita['nome'];?></td>

                        <td><strong>Apertura:</strong><br /><?php echo $oraAperturaAttivita?></td>

                        <td><strong>Chiusura:</strong><br /><?php echo $oraChiusuraAttivita;?></td>

                        <form action="<?php echo $_SERVER['PHP_SELF']?>"  method="post">
                        <td>
                            <input type="submit" class="button" name="<?php echo $attivita['id'];?>" value="MODIFICA" />
                            <input type="hidden" name="bottonePremuto"/>
                        </td>
                        </form> 
                    </tr>
                </table>
                <?php
                }
                ?>
            
        </div>
       
       
    </body>
</html>