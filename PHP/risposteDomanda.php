<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');

    session_start();

    if(isset($_POST['bottonePremuto'])){
        if(isset($_POST['inserisciRisposta'])){
            $arrayDati['idDomanda'] = $_POST['idDomanda'];
            $arrayDati['tipoAzione'] = "aggiungiRisposta";
            $_SESSION['tipoAzioneDomanda'] = $arrayDati;
            header('Location: modificaDomande.php');
            exit();
        }
        elseif(isset($_POST['elevaFaq'])){
            $arrayDati['idDomanda'] = $_POST['idDomanda'];
            $arrayDati['tipoAzione'] = "elevaFaq";            
            $idRisposta = individuaBottoneRispostaSelezionata($_POST['idDomanda']);            
            $arrayDati['idRisposta'] = $idRisposta;
            $_SESSION['tipoAzioneDomanda'] = $arrayDati;
            header('Location: modificaDomande.php');
            exit();
        }
    }



    if(isset($_SESSION['loginType'])){
        if(isset($_SESSION['domandaScelta'])){
            $temp = $_SESSION['domandaScelta'];
            $idDomanda = $temp['idDomanda'];
            $datiDomanda = getDatiDomanda($idDomanda);    
            
            if($temp['azione'] != "VISUALIZZA RISPOSTE" && $_SESSION['loginType'] == "Cliente"){
                unset($_SESSION['domandaScelta']);
                header('Location: domande.php');
                exit();
            }
            else{
                if($temp['azione'] != "VISUALIZZA RISPOSTE"){
                    $elevaFaq = "True";
                }                
            }            

            if(isset($_SESSION['codFiscUtenteLoggato'])){
                $categorieCliente = getCategorieCliente($_SESSION['codFiscUtenteLoggato']);
                if(array_key_exists($datiDomanda['categoria'] , $categorieCliente  )){
                    $rispostaPermessa = "True";
                }
            }            
        }
        else{
            header('Location: domande.php');
            exit();
        }
    }
    else{
        header('Location: login.php');
        exit();
    }

    echo'<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Domande</title>

        <style type="text/css">
            <?php include "../CSS/domande.css" ?>
         </style>
    
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&amp;display=swap" rel="stylesheet" />  
      </head>

    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
        <div class="top">
            <div class="topLeft">               
                <a href="./domande.php">TORNA ALLA LISTA<br /> DELLE DOMANDE</a>  
            </div>      
        <?php
            if(isset($elevaFaq)){
                echo '
                <h1 class="alignCenter">SCEGLI RISPOSTA</h1>
                <div style="width: 18.5%;"></div>';
            }
        ?>
               
            

        </div>
       
    
        <div class="mainContainer minHeight">
            <div class="topBox">
                    <div class="miniBox specialText">
                        <?php echo $datiDomanda['nomeAutore']." ".$datiDomanda['cognomeAutore'];?>
                    </div>
                    <div class="miniBox specialText">
                        Categoria: <?php echo $datiDomanda['categoria'];?>
                    </div>
            </div>

            <div class="middleBox">
                <?php echo $datiDomanda['testoDomanda'];?>                 
            </div>

            <div class="bottomBox">
            <?php
                if($_SESSION['loginType'] != "Cliente"){
                    if(!isset($elevaFaq)){
                        echo '<input type="submit" class="specialButton" name="inserisciRisposta" value="AGGIUNGI RISPOSTA" />';
                    }                    
                }
                else{
                    if($_SESSION['codFiscUtenteLoggato'] != $datiDomanda['codFiscAutore'] && isset($rispostaPermessa)){
                        echo '<input type="submit" class="specialButton" name="inserisciRisposta" value="AGGIUNGI RISPOSTA" />';
                    }
                }
            ?>                
            </div>
        </div>


        <h3 class="titoloImportante alignCenter">RISPOSTE:</h3>
        <?php
            $listaRisposte = $datiDomanda['listaRisposte'];
            $numRisposte = $listaRisposte->length;
            if($numRisposte >= 1){
                for($i=0 ; $i < $numRisposte ; $i++){
                    $risposta = $listaRisposte->item($i);
                    $idRisposta = $risposta->getElementsByTagName("idRisposta")->item(0)->textContent;
                    $autore = $risposta->getElementsByTagName("autore")->item(0)->textContent;
                    if($autore != "Staff"){
                        $nomeAutoreRisposta = $risposta->getElementsByTagName("nomeAutoreRisposta")->item(0)->textContent;
                        $cognomeAutoreRisposta = $risposta->getElementsByTagName("cognomeAutoreRisposta")->item(0)->textContent;
                    }
                    $testoRisposta = $risposta->getElementsByTagName("testoRisposta")->item(0)->textContent;                
        ?>              
                    <div class="mainContainer minHeight">
                        <div class="topBox">
                                <div class="miniBox specialText">
                                    Autore risposta: 
                                <?php
                                    if($autore != "Staff"){
                                        echo $nomeAutoreRisposta." ".$cognomeAutoreRisposta;
                                    }   
                                    else{
                                        echo $autore;
                                    }
                                ?>
                                </div>                                                                                       
                        </div>

                        <div class="middleBox">
                            <?php echo $testoRisposta;?>                 
                        </div>

                        <div class="bottomBox">
                        <?php
                            if(isset($elevaFaq)){
                                echo "<input type=\"submit\" class=\"specialButton\" name=\"{$idRisposta}\" value=\"SELEZIONA\" />";
                            }
                        ?>
                        </div>
                    </div>    
        <?php
                }
            }
            else{
                echo '<p class="alignCenter scrittaCentrale">Non sono state trovate delle risposte...</p>';
            }
        ?>
            <input type="hidden" name="bottonePremuto" />
            <input type="hidden" name="idDomanda" value="<?php echo $idDomanda;?>" />
        <?php
            if(isset($elevaFaq)){
                echo '<input type="hidden" name="elevaFaq" />';
            }
        ?>

        </form>
      
    </body>
</html>