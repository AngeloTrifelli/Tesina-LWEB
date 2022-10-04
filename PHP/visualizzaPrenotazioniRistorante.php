<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');
    require_once('funzioniDeletePHP.php');

    session_start();

    if(isset($_POST['bottonePremuto'])){
        $id = individuaBottonePrenotazioniRistorante();        
        if(strpos($id , "PT") != false){            
            if(isset($_COOKIE['Cancella'])){
                unset($_COOKIE['Cancella']);
                setcookie('Cancella', '', time() - 3600, '/');
                rimuoviPrenotazioneTavolo($id);
            }            
        }
        else{
            $_SESSION['dettagliSC'] = $id;                        
            header('Location: dettagliPrenotazioneSC.php');
            exit();
        }            
    }


    if(isset($_SESSION['codFiscUtenteLoggato'])){
        $temp = $_SESSION['soggiornoAttivo'];
        if($temp != "null"){
            if($temp['statoSoggiorno'] != "Approvato"){
                header('Location: areaUtente.php');
                exit();
            }
            else{
                $prenotazioniCliente = getPrenotazioniRistorante($_SESSION['codFiscUtenteLoggato']);
                $prenotazioniAttive = $prenotazioniCliente[0];
                $prenotazioniPassate = $prenotazioniCliente[1];
            }
        }
        else{
            header('Location: prenotaOra.php');
            exit();
        }
    }
    else{
        header('Location: login.php');
        exit();
    }

    





    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Visualizza prenotazioni</title>

        <style>
            <?php include "../CSS/visualizzaPrenotazioniRistorante.css" ?>
        </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    </head>


    <body>
        <div class="top">
            <div class="topLeft">
                <a href="./homeRistorante.php">TORNA NELL'AREA UTENTE</a>    
            </div>
            <h1 class="alignCenter">PRENOTAZIONI RISTORANTE</h1>
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
                    <table class="prenotazione alignCenter" align="center">
                        <tr>
                            <td>
                                <strong>Tipo prenotazione</strong><br />
                                <?php echo $temp['tipoPrenotazione'];?>
                            </td>
                        <?php
                            if($temp['tipoPrenotazione'] == "Servizio al tavolo"){
                        ?>
                                <td>
                                    <strong>Numero tavolo</strong><br />
                                    <?php echo substr($temp['numeroTavolo'], 1);?>
                                </td>    
                                <td>
                                    <strong>Locazione</strong><br />
                                    <?php echo $temp['locazioneTavolo'];?>
                                </td> 
                        <?php        
                            }
                            else{
                                echo'
                                    <td class="hide">
                                    </td>
                                    <td class="hide">
                                    </td>
                                    <td class="hide">
                                    </td>
                                ';
                            }
                        ?>                             
                            <td>
                                <strong>Data</strong><br />
                                <?php
                                    $stringaData = $temp['dataPrenotazione'];
                                    $giorno = substr($stringaData, 8,2);       
                                    $mese = substr($stringaData,5,2 );
                                    $anno = substr($stringaData,0,4 );
                                    echo $giorno."-".$mese."-".$anno;
                                ?>
                            </td>    
                            <td>
                                <strong>Ora</strong><br />
                                <?php echo substr($temp['oraPrenotazione'], 0 , 5);?>
                            </td>
                            <td class="hide">
                            </td>
                            <td>
                            <?php
                                if($temp['tipoPrenotazione'] == "Servizio al tavolo"){
                                    echo "<input type=\"submit\" class=\"button\" name=\"{$temp['idPrenotazione']}\" onClick=\"myEvent()\" value=\"ANNULLA\" />";                                    
                                }
                                else{
                                    echo "<input type=\"submit\" class=\"button\" name=\"{$temp['idPrenotazione']}\" value=\"DETTAGLI\" />";                                    
                                }
                            ?>                                
                            </td>                                                      
                        </tr>
                    </table>
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
                    <table class="prenotazione alignCenter" align="center">
                        <tr>
                            <td>
                                <strong>Tipo prenotazione</strong><br />
                                <?php echo $temp['tipoPrenotazione'];?>
                            </td>
                        <?php
                            if($temp['tipoPrenotazione'] == "Servizio al tavolo"){
                        ?>
                                <td>
                                    <strong>Numero tavolo</strong><br />
                                    <?php echo substr($temp['numeroTavolo'], 1);?>
                                </td>    
                                <td>
                                    <strong>Locazione</strong><br />
                                    <?php echo $temp['locazioneTavolo'];?>
                                </td> 
                        <?php        
                            }
                            else{
                                echo'
                                    <td class="hide">
                                    </td>
                                    <td class="hide">
                                    </td>
                                    <td class="hide">
                                    </td>
                                ';
                            }
                        ?>                             
                            <td>
                                <strong>Data</strong><br />
                                <?php
                                    $stringaData = $temp['dataPrenotazione'];
                                    $giorno = substr($stringaData, 8,2);       
                                    $mese = substr($stringaData,5,2 );
                                    $anno = substr($stringaData,0,4 );
                                    echo $giorno."-".$mese."-".$anno;
                                ?>
                            </td>    
                            <td>
                                <strong>Ora</strong><br />
                                <?php echo substr($temp['oraPrenotazione'], 0 , 5);?>
                            </td>
                            <td class="hide">
                            </td>
                            <td>
                            <?php
                                if($temp['tipoPrenotazione'] == "Servizio in camera"){
                                    echo "<input type=\"submit\" class=\"button\" name=\"{$temp['idPrenotazione']}\" value=\"DETTAGLI\" />";                                    
                                }                                
                            ?>                                
                            </td>                                                      
                        </tr>
                    </table>
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