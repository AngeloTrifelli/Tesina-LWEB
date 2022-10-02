<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');
    require_once('funzioniDeletePHP.php');

    session_start();

    if(isset($_POST['bottonePremuto'])){
        $id = individuaBottonePrenotazioniRistorante();              
        if(strpos($id , "PT") != false){ 
            if($_POST[$id] == "ANNULLA"){           
                if(isset($_COOKIE['Cancella'])){
                    unset($_COOKIE['Cancella']);
                    setcookie('Cancella', '', time() - 3600, '/');                    
                    rimuoviPrenotazioneTavolo($id);
                }            
            }
            else{
                $arrayDati['idPrenotazione'] = $id;
                $_SESSION['prenotazioneDaModificare'] = $arrayDati;
                header('Location: modificaPrenotazioneRistorante.php');
                exit();
            }
        }
        else{
            $_SESSION['dettagliSC'] = $id;                              
            header('Location: dettagliPrenotazioneSC.php');
            exit();
        }            
    }
    
    if(!isset($_SESSION['codFiscUtenteLoggato'])){
        if(!isset($_SESSION['loginType'])){
            header('Location: intro.php');
            exit();
        }
        else{
            $prenotazioniClienti = getPrenotazioniRistorante("null");
            $prenotazioniAttive = $prenotazioniClienti[0];
            $prenotazioniPassate = $prenotazioniClienti[1];
        }
    }
    else{
        header('Location: areaUtente.php');
        exit();
    }

    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Prenotazioni ristorante</title>

        <style>
            <?php include "../CSS/prenotazioniRistorante.css" ?>
        </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    </head>


    <body>
        <div class="top">

            <div class="topLeft">               
                <a href="./prenotazioniClienti.php">TORNA ALLA PAGINA<br /> DELLE PRENOTAZIONI</a>  
            </div>

            <h1 class="alignCenter">LISTA PRENOTAZIONI RISTORANTE</h1>
           
            <div style="width: 18.5%;"></div>
               
        </div>

        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
            <h3 class="titoloImportante alignCenter">PRENOTAZIONI ATTIVE:</h3>
            <?php
                $numPrenotazioniAttive = count($prenotazioniAttive);
                if($numPrenotazioniAttive >= 1){
            ?>
                    <div class="mainContainer marginBottom">
                    <?php
                        for($i=0 ; $i < $numPrenotazioniAttive ; $i++){
                            $temp = $prenotazioniAttive[$i];
                    ?>
                            <div class="areaDati">
                                <table class="alignCenter tabella"  align="center">
                                    <tr>
                                        <td><strong>Codice fiscale cliente</strong></td>
                                        <td><strong>Tipo</strong></td>
                                    <?php
                                        if($temp['tipoPrenotazione'] == "Servizio al tavolo"){
                                    ?>
                                            <td><strong>Numero tavolo</strong></td>
                                            <td><strong>Locazione</strong></td>
                                    <?php
                                        }
                                    ?>                                        
                                        <td><strong>Data</strong></td>
                                        <td><strong>Ora</strong></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $temp['codFiscCliente'];?></td>
                                        <td><?php echo $temp['tipoPrenotazione'];?></td>
                                    <?php
                                        if($temp['tipoPrenotazione'] == "Servizio al tavolo"){
                                    ?>  
                                            <td><?php echo substr($temp['numeroTavolo'], 1);?></td>
                                            <td><?php echo $temp['locazioneTavolo'];?></td>
                                    <?php
                                        }
                                    ?>                                        
                                        <td>
                                        <?php
                                            $stringaData = $temp['dataPrenotazione'];
                                            $giorno = substr($stringaData, 8,2);       
                                            $mese = substr($stringaData,5,2 );
                                            $anno = substr($stringaData,0,4 );
                                            echo $giorno."-".$mese."-".$anno;
                                        ?>
                                        </td>
                                        <td><?php echo substr($temp['oraPrenotazione'], 0 , 5);?></td>
                                    <?php
                                        if($temp['tipoPrenotazione'] == "Servizio al tavolo"){    
                                            echo "<td><input type=\"submit\" class=\"button\" name=\"{$temp['idPrenotazione']}\" value=\"MODIFICA\" /></td>";                                
                                            echo "<td><input type=\"submit\" class=\"button\" name=\"{$temp['idPrenotazione']}\" onClick=\"myEvent()\" value=\"ANNULLA\" /></td>";                                    
                                        }
                                        else{                                    
                                            echo "<td><input type=\"submit\" class=\"button\" name=\"{$temp['idPrenotazione']}\" value=\"DETTAGLI\" /></td>";                                                                    
                                        }
                                    ?>                                                                                                                    
                                    </tr>
                                </table>
                            </div>
                    <?php
                        }
                    ?>                        
                </div>
        <?php
            }
            else{
                echo '<p class="alignCenter scrittaCentrale">Non sono state trovate prenotazioni attive...</p>';
            }
        ?>

            <h3 class="titoloImportante alignCenter">PRENOTAZIONI PASSATE:</h3>
            <?php
                $numPrenotazioniPassate = count($prenotazioniPassate);
                if($numPrenotazioniPassate >= 1){
            ?>
                    <div class="mainContainer marginBottom">
                    <?php
                        for($i=0 ; $i < $numPrenotazioniPassate ; $i++){
                            $temp = $prenotazioniPassate[$i];
                    ?>
                            <div class="areaDati">
                                <table class="alignCenter tabella"  align="center">
                                    <tr>
                                        <td><strong>Codice fiscale cliente</strong></td>
                                        <td><strong>Tipo</strong></td>
                                    <?php
                                        if($temp['tipoPrenotazione'] == "Servizio al tavolo"){
                                    ?>
                                            <td><strong>Numero tavolo</strong></td>
                                            <td><strong>Locazione</strong></td>
                                    <?php
                                        }
                                    ?>                                         
                                        <td><strong>Data</strong></td>
                                        <td><strong>Ora</strong></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $temp['codFiscCliente'];?></td>
                                        <td><?php echo $temp['tipoPrenotazione'];?></td>
                                    <?php
                                        if($temp['tipoPrenotazione'] == "Servizio al tavolo"){
                                    ?>  
                                            <td><?php echo substr($temp['numeroTavolo'], 1);?></td>
                                            <td><?php echo $temp['locazioneTavolo'];?></td>
                                    <?php
                                        }
                                    ?>                                        
                                        <td>
                                        <?php
                                            $stringaData = $temp['dataPrenotazione'];
                                            $giorno = substr($stringaData, 8,2);       
                                            $mese = substr($stringaData,5,2 );
                                            $anno = substr($stringaData,0,4 );
                                            echo $giorno."-".$mese."-".$anno;
                                        ?>
                                        </td>
                                        <td><?php echo substr($temp['oraPrenotazione'], 0 , 5);?></td>
                                    <?php
                                        if($temp['tipoPrenotazione'] == "Servizio in camera"){    
                                            echo "<td><input type=\"submit\" class=\"button\" name=\"{$temp['idPrenotazione']}\" value=\"DETTAGLI\" /></td>";
                                        }
                                    ?>

                                    </tr>
                                </table>
                            </div>
                    <?php
                        }
                    ?>
                    </div>
            <?php
                }
                else{
                    echo '<p class="alignCenter scrittaCentrale">Non sono state trovate prenotazioni passate...</p>';
                }
            ?>
            <input type="hidden" name="bottonePremuto" />
    
        </form>

        <script>
            function myEvent(){
                var choice =confirm("Confermi di voler annullare la prenotazione ?");
                if(choice == true){
                    document.cookie = "Cancella" + "=" + "Cancella" + "" + "; path=/";  
                }
            }
        </script>
    </body>
</html>