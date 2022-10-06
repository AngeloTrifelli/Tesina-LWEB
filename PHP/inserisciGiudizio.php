<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniInsertPHP.php');
    require_once('funzioniModificaPHP.php');

    session_start();

    if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA']) || isset($_POST['MODIFICA'])  ){
        if(isset($_POST['ANNULLA'])){
            if(strpos($_POST['idOggetto'] , "CMR") != false || substr($_POST['idOggetto'] , 0 , 2) == "CM"){
                $_SESSION['recensioneScelta'] = $_POST['idRecensione'];
                header('Location: risposteRecensione.php');
                exit();
            }
            else{
                header('Location: recensioni.php');
                exit();
            }
        }
        elseif(isset($_POST['CONFERMA'])){
            if(isset($_POST['votoUtilita']) && isset($_POST['votoAccordo'])){
                if($_POST['votoUtilita'] != $_POST['votoAccordo'] || ( $_POST['votoUtilita'] == $_POST['votoAccordo'] && $_POST['votoUtilita'] != 0) ){
                    aggiungiValutazione($_POST['idOggetto'] , $_POST['tipoOggetto'] , $_SESSION['codFiscUtenteLoggato'] , $_POST['votoUtilita'] , $_POST['votoAccordo']);
                    if($_POST['tipoOggetto'] == "Recensione"){
                        header('Location: recensioni.php');
                        exit();
                    }
                    else{
                        $_SESSION['recensioneScelta'] = $_POST['idRecensione'];
                        header('Location: risposteRecensione.php');
                        exit();
                    }
                }
                else{
                    $erroreVoto = "True";
                    $arrayDati['idOggetto'] = $_POST['idOggetto'];
                    $arrayDati['tipoAzione'] = "aggiungiValutazione";
                    if($_POST['tipoOggetto'] == "Commento" || $_POST['tipoOggetto'] == "RispostaCommento"){
                        $arrayDati['idRecensione'] = $_POST['idRecensione'];
                    }
                    $_SESSION['tipoAzioneValutazione'] = $arrayDati;
                }
            }
            else{
                $arrayDati['idOggetto'] = $_POST['idOggetto'];
                $arrayDati['tipoAzione'] = "aggiungiValutazione";
                if($_POST['tipoOggetto'] == "Commento" || $_POST['tipoOggetto'] == "RispostaCommento"){
                    $arrayDati['idRecensione'] = $_POST['idRecensione'];
                }
                $_SESSION['tipoAzioneValutazione'] = $arrayDati;
            }
        }
        else{            
            modificaValutazione($_POST['idOggetto'] , $_POST['tipoOggetto'] , $_SESSION['codFiscUtenteLoggato'] , $_POST['votoUtilita'] , $_POST['votoAccordo']);
            if($_POST['tipoOggetto'] == "Recensione"){
                header('Location: recensioni.php');
                exit();
            }
            else{
                $_SESSION['recensioneScelta'] = $_POST['idRecensione'];
                header('Location: risposteRecensione.php');
                exit();
            }
        }

    }



    if(isset($_SESSION['tipoAzioneValutazione'])){        
        $temp = $_SESSION['tipoAzioneValutazione'];
        $idOggetto = $temp['idOggetto'];               
        if(strpos($idOggetto , "CMR") != false){
            $idRecensione = $temp['idRecensione'];
            $tipoOggetto = "RispostaCommento";
            $datiRisposta = getDatiRispostaCommento($idOggetto);
        }
        elseif(substr($idOggetto , 0 , 2) == "CM"){ 
            $idRecensione = $temp['idRecensione'];           
            $tipoOggetto = "Commento";
            $datiCommento = getDatiCommento($idOggetto);
        }   
        else{                        
            $tipoOggetto = "Recensione";
            $datiRecensione = getDatiRecensione($idOggetto);
        }     

        if($temp['tipoAzione'] == "aggiungiValutazione"){
            $aggiungiValutazione = "True";
        }
        else{
            $valutazione = getValutazione($idOggetto , $_SESSION['codFiscUtenteLoggato']);
            $modificaValutazione = "True";
        }

        unset($_SESSION['tipoAzioneValutazione']);
    }
    else{
        header('Location: recensioni.php');
        exit();
    }


    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Inserisci giudizio</title>

        <style type="text/css">
            <?php include "../CSS/modificaRecensione.css" ?>
        </style>
        

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&amp;display=swap" />
    </head>

    <body>

        <div class="containerCentrale">
        <?php
            if(isset($aggiungiValutazione)){
                echo '<h1>INSERISCI GIUDIZIO:</h1>';        
            }                    
            elseif(isset($modificaValutazione)){
                echo '<h1>MODIFICA GIUDIZIO:</h1>';        
            }    
        ?>
        
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
            <div class="mainArea">                   
            <?php
                if($tipoOggetto == "Recensione"){
            ?>
                    <span class="item">Recensione:</span>
                    <div class="row">    
                        <?php echo $datiRecensione['testoRecensione'];?>                                                
                    </div>                                                        
           <?php
                }
                elseif($tipoOggetto == "Commento"){                    

            ?>
                    <span class="item">Commento:</span>
                    <div class="row">    
                        <?php echo $datiCommento['testo'];?>                                                
                    </div>  
            <?php
                }
                else{
            ?>
                    <span class="item">Risposta:</span>
                    <div class="row">    
                        <?php echo $datiRisposta['testoRisposta'];?>                                                
                    </div> 
            <?php
                }                
            ?>
             
        
                    <div class="row marginTop" style="justify-content:space-around">  
                        <div style="display: flex ; flex-direction:column; align-items:center; width:50%;">
                            <span class="item marginTop">Utilita del contenuto:</span>
                            <select class="selectInput" style="text-align: center; width: 20%; min-width: fit-content; height:fit-content;" name="votoUtilita">                                                       
                            <?php
                                if(isset($_POST['CONFERMA']) && isset($_POST['votoUtilita'])){
                                    echo "<option selected value=\"{$_POST['votoUtilita']}\">{$_POST['votoUtilita']}</option> ";
                                }
                                else{
                                    if(isset($modificaValutazione)){
                                        $votoUtilitaPrecedente = $valutazione->getElementsByTagName("votoUtilita")->item(0)->textContent;
                                        echo "<option selected value=\"{$votoUtilitaPrecedente}\">{$votoUtilitaPrecedente} </option>";
                                    }
                                    else{
                                        echo '<option disabled selected value="Scegli">-- Scegli -- </option>';      
                                    }                                       
                                }
                            ?>                
                                <option value="0">0</option>            
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        <?php
                            if(isset($_POST['CONFERMA']) && !isset($_POST['votoUtilita'])){
                                echo '<p class="errorLabel">Dati mancanti!</p>';
                            }
                        ?>
                        </div> 
                        <div style="display: flex ; flex-direction:column; align-items:center; width:50%;">                                                                            
                            <span class="item marginTop">Accordo con il contenuto:</span>
                            <select class="selectInput" style="text-align: center; width: 20%; min-width: fit-content; height:fit-content;" name="votoAccordo">                       
                            <?php
                                if(isset($_POST['CONFERMA']) && isset($_POST['votoAccordo'])){
                                    echo "<option selected value=\"{$_POST['votoAccordo']}\">{$_POST['votoAccordo']}</option> ";
                                }
                                else{
                                    if(isset($modificaValutazione)){
                                        $votoAccordoPrecedente = $valutazione->getElementsByTagName("votoAccordo")->item(0)->textContent;
                                        echo "<option selected value=\"{$votoAccordoPrecedente}\">{$votoAccordoPrecedente} </option>";
                                    }
                                    else{
                                        echo '<option disabled selected value="Scegli">-- Scegli -- </option>';      
                                    }                                             
                                }
                            ?>                            
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>                                                 
                            </select>  
                        <?php
                            if(isset($_POST['CONFERMA']) && !isset($_POST['votoAccordo'])){
                                echo '<p class="errorLabel">Dati mancanti!</p>';
                            }
                        ?>
                        </div>
                    </div>

            <?php       
                    if(isset($_POST['CONFERMA']) && isset($erroreVoto)){
                        echo '<p class="errorLabel">Inserire almeno un voto diverso da zero!</p>';
                    }   
            ?>  
                
           
                    
            

                    
                                                     
                <input type="hidden" name="idOggetto" value="<?php echo $idOggetto;?>" />    
                <input type="hidden" name="tipoOggetto" value="<?php echo $tipoOggetto;?>" />            
                <?php
                    if(isset($aggiungiValutazione)){
                        echo '<input type="hidden" name="aggiungiValutazione" /> ';                        
                    }
                    else{
                        echo '<input type="hidden" name="modificaValutazione" /> ';                        
                    }                    

                    if($tipoOggetto ==  "Commento" || $tipoOggetto == "RispostaCommento"){
                        echo "<input type=\"hidden\" name=\"idRecensione\" value=\"{$idRecensione}\"  /> ";                        
                    }
                ?>

                <div class="zonaBottoni">                                        
                <?php
                    if(isset($aggiungiValutazione)){
                        echo '<input type="submit" class="button" name="ANNULLA" value="ANNULLA" />';
                        echo '<input type="submit" class="button" name="CONFERMA" value="CONFERMA" />';
                    }
                    else{
                        echo '
                        <button class="button" type="submit" name="ANNULLA" value="ANNULLA">
                            ANNULLA MODIFICA
                        </button>
                        <button class="button" type="submit" name="MODIFICA" value="MODIFICA">
                            CONFERMA MODIFICA
                        </button>';                        
                    }
                ?>
                    
                </div>

            </div>
           
                   
            </form>
        </div>


          

    </body>
</html>