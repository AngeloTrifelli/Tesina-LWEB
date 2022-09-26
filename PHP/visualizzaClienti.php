<?php   
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');

    session_start();
    if(isset($_SESSION['loginType'])){
        if($_SESSION['loginType']=="Admin"){
            
            $numClienti=getNumClientiTotali();
            $numClientiConSoggiorno=getNumClientiConSoggiorno();
            $numClientiPresenti=$numClientiConSoggiorno['presenti'];
            $numClientiNonPresenti=$numClientiConSoggiorno['nonPresenti'];
        }else{
            header('Location: areaUtente.php');
        }
        
    }else{
        header('Location: intro.php');
    }

    if(isset($_POST['utenteSelezionato'])){
        $codFiscUtenteSelezionato=individuaBottoneCodFiscUtenteSelezionato();
        $_SESSION['codFiscUtenteDaModificare']=$codFiscUtenteSelezionato;
        header('Location: datiPersonali.php');
        }





    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Lista clienti</title>

        <style>
            <?php include "../CSS/visualizzaClienti.css" ?>
        </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    </head>

    <body>
        <div class="top">
            <div class="topLeft">
                <a href="./areaUtente.php">TORNA INDIETRO</a>    
            </div>
            <h1 class="alignCenter">LISTA CLIENTI</h1>
            <div style="width: 18.5%;"></div>
        </div>

        <?php
             if($numClientiPresenti>0){
                echo "<h3 class=\"titoloImportante alignCenter\">CLIENTI PRESENTI IN STRUTTURA:</h3>";
            }else{
                echo "<h3 class=\"titoloImportante alignCenter\">NESSUN CLIENTE PRESENTE IN STRUTTURA AL MOMENTO</h3>";
                echo "<span class=\"trePuntini\">...</span>";
                }
            for($i=0;$i<$numClienti;$i++){
                $cliente=restituisciClienteIEsimo($i);
                $clientePresenteInstruttura=presenzaClienteInStruttura($cliente['codFisc']);
            if($clientePresenteInstruttura=="True"){
        ?>

        <div class="mainContainer">
    
                    <table class="cliente alignCenter" align="center">
                            <tr>
                                <td>
                                    <strong>Nome:</strong><br />
                                    <?php echo $cliente['nome'];?>
                                </td>
                                <td>
                                    <strong>Cognome:</strong><br />
                                    <?php echo $cliente['cognome'];?>
                                </td>
                                <td>
                                    <strong>Codice Fiscale:</strong><br />
                                    <?php echo $cliente['codFisc'];?>
                                            
                                </td>
                                <td>
                                <form action="<?php echo $_SERVER['PHP_SELF']?>"  method="post">

                                    <button type="submit" name="<?php echo $cliente['codFisc'];?>">
                                    <input type="hidden" name="utenteSelezionato"/>
                                    <img class="immagine" src="../img/edit.png" />
                                </form>
                                </td>
                            </tr>
                            
                        </table>
        </div>
                    <?php
                        }
            }
            if($numClientiNonPresenti>0){
                echo "<h3 class=\"titoloImportante alignCenter\">CLIENTI NON PRESENTI IN STRUTTURA:</h3>";
            }
            for($j=0;$j<$numClienti;$j++){
                $cliente=restituisciClienteIEsimo($j);
                $clientePresenteInstruttura=presenzaClienteInStruttura($cliente['codFisc']);
                if($clientePresenteInstruttura=="False"){
                    ?>

        <div class="mainContainer">
    
                    <table class="cliente alignCenter" align="center">
                            <tr>
                                <td>
                                    <strong>Nome:</strong><br />
                                    <?php echo $cliente['nome'];?>
                                </td>
                                <td>
                                    <strong>Cognome:</strong><br />
                                    <?php echo $cliente['cognome'];?>
                                </td>
                                <td>
                                    <strong>Codice Fiscale:</strong><br />
                                    <?php echo $cliente['codFisc'];?>
                                            
                                </td>
                                <td>
                                <form action="<?php echo $_SERVER['PHP_SELF']?>"  method="post">
                                    <button type="submit" name="<?php echo $cliente['codFisc'];?>">
                                    <input type="hidden" name="utenteSelezionato"/>
                                    <img class="immagine" src="../img/edit.png" />
                                </form>
                                </td>
                            </tr>
                            
                        </table>            
        
        </div>

                        <?php
                        }
            }
            if($numClientiPresenti=0 && $numClientiNonPresenti=0){
                echo "<h3 class=\"titoloImportante alignCenter\">NESSUN CLIENTE RISULTA AVERE UN SOGGIORNO PRENOTATO</h3>";
            }
                    ?>

    </body>

</html>