<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');

    session_start();

    if(isset($_POST['bottonePremuto'])){        
        $idRecensione = $_SESSION['recensioneScelta'];
        if(isset($_POST[$idRecensione])){            
            if($_POST[$idRecensione] == "INSERISCI RISPOSTA"){                
                $arrayDati['idRecensione'] = $idRecensione;
                $arrayDati['tipoAzione'] = "aggiungiCommento"; 
                $_SESSION['tipoAzioneRecensione'] = $arrayDati;
                header('Location: modificaRecensione.php');
                exit();                               
            }
            else{
                $arrayDati['idOggetto'] = $idRecensione;
                if($_POST[$idRecensione] == "INSERISCI GIUDIZIO"){
                    $arrayDati['tipoAzione'] = "aggiungiValutazione";                    
                }
                else{
                    $arrayDati['tipoAzione'] = "modificaValutazione";
                }
                $_SESSION['tipoAzioneValutazione'] = $arrayDati;
                header('Location: inserisciGiudizio.php');
                exit();
            }            
        }
        else{
            $idOggettoScelto = individuaBottoneRisposteRecensione($idRecensione);
            if(strpos($idOggettoScelto , "CMR") == false){                        
                if($_POST[$idOggettoScelto] == "INSERISCI RISPOSTA"){
                    $arrayDati['idRecensione'] = $idRecensione;        
                    $arrayDati['idCommento'] = $idOggettoScelto;
                    $arrayDati['tipoAzione'] = "aggiungiRispostaCommento";
                    $_SESSION['tipoAzioneRecensione'] = $arrayDati;
                    header('Location: modificaRecensione.php');
                    exit();
                }
                else{
                    $arrayDati['idRecensione'] = $idRecensione;
                    $arrayDati['idOggetto'] = $idOggettoScelto;
                    if($_POST[$idOggettoScelto] == "INSERISCI GIUDIZIO"){
                        $arrayDati['tipoAzione'] = "aggiungiValutazione";                    
                    }
                    else{
                        $arrayDati['tipoAzione'] = "modificaValutazione";
                    }
                    $_SESSION['tipoAzioneValutazione'] = $arrayDati;
                    header('Location: inserisciGiudizio.php');
                    exit();
                }                
            }
            else{
                $arrayDati['idRecensione'] = $idRecensione;
                $arrayDati['idOggetto'] = $idOggettoScelto;
                if($_POST[$idOggettoScelto] == "INSERISCI GIUDIZIO"){
                    $arrayDati['tipoAzione'] = "aggiungiValutazione";                    
                }
                else{
                    $arrayDati['tipoAzione'] = "modificaValutazione";
                }
                $_SESSION['tipoAzioneValutazione'] = $arrayDati;
                header('Location: inserisciGiudizio.php');
                exit();
            }
        }
        
    }


    if(isset($_SESSION['recensioneScelta'])){
        $idRecensione = $_SESSION['recensioneScelta'];
        $datiRecensione = getDatiRecensione($idRecensione);
        $listaCommenti = getCommentiRispostaRecensione($idRecensione);
        if(isset($_SESSION['loginType']) && $_SESSION['loginType'] == "Cliente"){
            $accessoCliente = "True";
            $categorieCliente = getCategorieCliente($_SESSION['codFiscUtenteLoggato']);
            if(array_key_exists($datiRecensione['categoria'] , $categorieCliente)){
                $giudizioPermesso = "True";
            }
        }
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
        <title>Recensioni</title>

        <style type="text/css">
            <?php include "../CSS/recensioni.css" ?>
         </style>

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&amp;display=swap" rel="stylesheet" />  
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      </head>

    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
        <div class="top">
            <div class="topLeft">               
                <a href="./recensioni.php">TORNA ALLA LISTA<br />DELLE RECENSIONI</a>  
            </div>                                
        </div>
        
            <div class="mainContainer marginTop minHeight">
                <div class="topBox">          
                    <div class="miniBox" style="padding-top: 1.2% ;">
                        <?php
                            $numStelleVuote = 5 - $datiRecensione['voto'];
                            for($j=0 ; $j < $datiRecensione['voto'] ; $j++){
                                echo '<span class="larger"><span class="fa fa-star checked"></span><span>';
                            }
                            
                            for($j=0; $j < $numStelleVuote ; $j++){
                                echo '<span class="larger"><span class="fa fa-star"></span><span>';
                            }
                        ?>                                
                    </div>                 
                        <div class="miniBox specialText">
                            Autore: <?php echo $datiRecensione['nomeAutore']." ".$datiRecensione['cognomeAutore'];?>
                        </div>                
                        <div class="miniBox specialText">
                            Categoria: <?php echo $datiRecensione['categoria'];?>
                        </div>
                </div>

                <div class="middleBox">
                    <?php echo $datiRecensione['testoRecensione'];?>                         
                </div>

                <div class="bottomBox">
                    <span class="specialText" style="color: white;">Utilit&agrave; totale: <?php echo $datiRecensione['utilita'];?></span>
                    <span class="specialText" style="color: white;">Accordo totale: <?php echo $datiRecensione['accordo'];?></span>
                <?php 
                    if(isset($accessoCliente)){
                        echo "<input type=\"submit\" class=\"specialButton\" name=\"{$idRecensione}\" value=\"INSERISCI RISPOSTA\" />";

                        $codFiscAutoreRecensione = $datiRecensione['codFiscAutore'];
                        if($_SESSION['codFiscUtenteLoggato'] != $codFiscAutoreRecensione && isset($giudizioPermesso)){
                            $giudizioPresente = getValutazione($idRecensione , $_SESSION['codFiscUtenteLoggato']);
                            if($giudizioPresente != "null"){
                                echo "<input type=\"submit\" class=\"specialButton\" name=\"{$idRecensione}\" value=\"MODIFICA GIUDIZIO\" />";    
                            }
                            else{
                                echo "<input type=\"submit\" class=\"specialButton\" name=\"{$idRecensione}\" value=\"INSERISCI GIUDIZIO\" />";
                            }                            
                        }                        
                    }
                ?>                                                           
                </div>
            </div>
        
        <h3 class="titoloImportante alignCenter">RISPOSTE:</h3>
        <?php
            $numCommenti = count($listaCommenti);
            if($numCommenti >= 1){
                for($i=0 ; $i < $numCommenti ; $i++){
                    $commento = $listaCommenti->item($i);
                    $idCommento = $commento->getAttribute("id");
                    $nomeAutore = $commento->getElementsByTagName("nomeAutore")->item(0)->textContent;
                    $cognomeAutore = $commento->getElementsByTagName("cognomeAutore")->item(0)->textContent;
        ?>
                    <div class="mainContainer marginTop noBottomBorder">
                        <div class="topBox">                                            
                            <div class="miniBox specialText">
                                Autore: <?php echo $nomeAutore." ".$cognomeAutore;?>
                            </div>           
                            <div class="miniBox specialText">
                                Utilit&agrave; totale: <?php echo $commento->getElementsByTagName("utilita")->item(0)->textContent;?>
                            </div>     
                            <div class="miniBox specialText">
                                Accordo totale: <?php echo $commento->getElementsByTagName("accordo")->item(0)->textContent;?>
                            </div>                                                                
                            <?php
                                if(isset($accessoCliente)){
                                    echo "<input type=\"submit\" class=\"specialButton\" name=\"{$idCommento}\" value=\"INSERISCI RISPOSTA\" />";

                                    $codFiscAutoreCommento = $commento->getElementsByTagName("codFiscAutore")->item(0)->textContent;

                                    if($_SESSION['codFiscUtenteLoggato'] != $codFiscAutoreCommento && isset($giudizioPermesso) ){ 
                                        $giudizioPresente = getValutazione($idCommento , $_SESSION['codFiscUtenteLoggato']);
                                        if($giudizioPresente != "null"){
                                            echo "<input type=\"submit\" class=\"specialButton\" name=\"{$idCommento}\" value=\"MODIFICA GIUDIZIO\" />";    
                                        }                                       
                                        else{
                                            echo "<input type=\"submit\" class=\"specialButton\" name=\"{$idCommento}\" value=\"INSERISCI GIUDIZIO\" />";
                                        }                                    
                                    }                                                                                    
                                }
                            ?>        
                        </div>

                        <div class="middleBox">
                            <?php echo $commento->getElementsByTagName("testo")->item(0)->textContent;?>                            
                        </div>
                    
                    </div>
                <?php
                    $listaRisposteCommento = $commento->getElementsByTagName("risposta");
                    $numRisposte = count($listaRisposteCommento);
                    if($numRisposte >= 1){   
                        for($j=0 ; $j < $numRisposte ; $j++){   
                            $rispostaCommento = $listaRisposteCommento->item($j);
                            $idRispostaCommento = $rispostaCommento->firstChild->textContent;
                            $nomeAutoreRisposta = $rispostaCommento->getElementsByTagName("nomeAutoreRisposta")->item(0)->textContent;              
                            $cognomeAutoreRisposta = $rispostaCommento->getElementsByTagName("cognomeAutoreRisposta")->item(0)->textContent;
                ?>

                            <div class="mainContainer marginLeft noTopBorder">
                                <div class="topBox noTopBorder">                                            
                                    <div class="miniBox specialText smallerText">
                                        Autore: <?php echo $nomeAutoreRisposta." ".$cognomeAutoreRisposta;?>
                                    </div>           
                                    <div class="miniBox specialText smallerText">
                                        Utilit&agrave; totale: <?php echo $rispostaCommento->getElementsByTagName("utilitaRisposta")->item(0)->textContent;?>              
                                    </div>     
                                    <div class="miniBox specialText smallerText">
                                        Accordo totale: <?php echo $rispostaCommento->getElementsByTagName("accordoRisposta")->item(0)->textContent;?>              
                                    </div>                                                                                                           
                                    <?php
                                        if(isset($accessoCliente)){                                        
                                            $codFiscAutoreRispostaCommento = $rispostaCommento->getElementsByTagName("codFiscAutoreRisposta")->item(0)->textContent;

                                            if($_SESSION['codFiscUtenteLoggato'] != $codFiscAutoreRispostaCommento && isset($giudizioPermesso)){   
                                                $giudizioPresente = getValutazione($idRispostaCommento , $_SESSION['codFiscUtenteLoggato']);
                                                if($giudizioPresente != "null"){
                                                    echo "<input type=\"submit\" class=\"specialButton\" name=\"{$idRispostaCommento}\" value=\"MODIFICA GIUDIZIO\" />";    
                                                }                                             
                                                else{
                                                    echo "<input type=\"submit\" class=\"specialButton\" name=\"{$idRispostaCommento}\" value=\"INSERISCI GIUDIZIO\" />";
                                                }                                                   
                                            }                                               
                                        }
                                    ?>        
                                </div>

                                <div class="middleBox">
                                    <?php echo $rispostaCommento->getElementsByTagName("testoRisposta")->item(0)->textContent;?>                                   
                                </div>
                            
                            </div>
        <?php
                        }
                    }
                }
            }
            else{
                echo '<p class="alignCenter scrittaCentrale">Non sono stati trovati dei commenti di risposta...</p>';
            }            
        ?>

            <input type="hidden" name="bottonePremuto" />            

            

    
        </form>
    </body>
</html>