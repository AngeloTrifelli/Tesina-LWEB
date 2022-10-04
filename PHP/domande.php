<?php 
    require_once('funzioniGetPHP.php');    
    require_once('funzioniPHP.php');

    session_start();

    if(isset($_SESSION['codFiscUtenteLoggato'])){
        $temp = $_SESSION['soggiornoAttivo'];
        if($temp != "null"){
            if($temp['statoSoggiorno'] != "Approvato"){
                header('Location: areaUtente.php');
                exit();
            }
        }
        else{
            header('Location: prenotaOra.php');
            exit();
        }
    }
    else{
        if(isset($_SESSION['loginType'])){
            $accessoStaff = "True";
        }
        else{
            header('Location: login.php');
            exit();
        }        
    }


    if(isset($_POST['bottonePremuto'])){
        if(isset($_POST['AGGIUNGI'])){
            $arrayDati['idDomanda'] = "";
            $arrayDati['tipoAzione'] = "aggiungiDomanda";
            $_SESSION['tipoAzioneDomanda'] = $arrayDati;
            header('Location: modificaDomande.php');
            exit();
        }
        elseif(isset($_POST['CONFERMA'])){            
            if(isset($_POST['scegliCategoria'])){      
                if(isset($_POST['domandePersonali'])){
                    $listaDomande = getDomande($_SESSION['codFiscUtenteLoggato'] , $_POST['scegliCategoria']);
                    $domandePersonali = "True";
                    $categoriaScelta = $_POST['scegliCategoria'];
                }          
                else{
                    $listaDomande = getDomande("null" , $_POST['scegliCategoria']);
                    $categoriaScelta = $_POST['scegliCategoria'];
                }                
            }
            else{
                if(isset($_POST['domandePersonali'])){
                    $listaDomande = getDomande($_SESSION['codFiscUtenteLoggato'] , "null");
                    $domandePersonali = "True";
                    $erroreCategoria = "True";
                }
                else{
                    $listaDomande = getDomande("null" , "null");
                    $erroreCategoria = "True";
                }
            }
            
        }
        elseif(isset($_POST['allCategorie'])){
            if(isset($_POST['domandePersonali'])){
                $listaDomande = getDomande($_SESSION['codFiscUtenteLoggato'] , "null");
                $domandePersonali = "True";
            }
            else{
                $listaDomande = getDomande("null" , "null");
            }   
        }
        elseif(isset($_POST['visualizzaPersonale'])){
            if(isset($_POST['categoriaScelta'])){
                $listaDomande = getDomande($_SESSION['codFiscUtenteLoggato'] , $_POST['categoriaScelta']);
                $domandePersonali = "True";
                $categoriaScelta = $_POST['categoriaScelta'];
            }
            else{
                $listaDomande = getDomande($_SESSION['codFiscUtenteLoggato'] , "null");
                $domandePersonali = "True";
            }                        
        }
        elseif(isset($_POST['visualizzaAll'])){
            if(isset($_POST['categoriaScelta'])){
                $listaDomande = getDomande("null" , $_POST['categoriaScelta']);
                $categoriaScelta = $_POST['categoriaScelta'];
            }
            else{
                $listaDomande = getDomande("null" , "null");
            }        
        }
        else{
            $idDomanda = individuaBottoneDomanda();
            $arrayDati['idDomanda'] = $idDomanda;
            $arrayDati['azione'] = $_POST[$idDomanda];                           
            $_SESSION['domandaScelta'] = $arrayDati;
            header('Location: risposteDomanda.php');
            exit();
        }
    }
    else{
        $listaDomande = getDomande("null" , "null");
    }


    

    $tabelleCategorie = getCategorie();    

    


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
                <a href="./areaUtente.php">TORNA ALL'AREA PERSONALE</a>  
            </div>      
        <?php
            if($_SESSION['loginType'] == "Cliente"){
        ?>
                <div>
                    <input type="submit" id="fakeLink" name="AGGIUNGI" value="AGGIUNGI DOMANDA" />
                </div>                     
        <?php
            }
        ?>
            
        </div>

        <div class="mainContainer">
            <h1 id="mainTitle">DOMANDE</h1>
            <div class="row">
            <?php
                if(!isset($categoriaScelta)){                
            ?>
                    <h3 class="specialText">Scegli la categoria:</h3>
                    <select id="selectInput" name="scegliCategoria">
                        <option disabled selected value="Scegli">-- Scegli -- </option>                
                    <?php
                        for($i=0 ; $i < 2 ; $i++){
                            $tabella = $tabelleCategorie[$i];
                            for($j=0 ; $j < count($tabella) ; $j++ ){
                                $categoria = $tabella[$j];
                                echo "<option value=\"{$categoria['nome']}\">{$categoria['nome']}</option>";
                            }
                        }
                    ?>                    
                    </select>                 
                    <input type="submit" class="button" name="CONFERMA" value="CONFERMA" />  
            <?php
                }
                else{
            ?>      <h3 class="specialText">Categoria scelta: <?php echo $categoriaScelta;?></h3>
                    <button class="button bigButton" type="submit" name="allCategorie">
                        VISUALIZZA TUTTE LE CATEGORIE
                    </button>                    
            <?php
                }
            ?>
            </div>
            <?php
                if(isset($erroreCategoria)){
                    echo '<p class="errorLabel">Selezionare una categoria!</p>';
                }
            ?>

            <div class="row">
            <?php
                if($_SESSION['loginType'] == "Cliente"){
                    if(!isset($domandePersonali)){
                        echo '<h3 class="specialText">Visualizza le tue domande:</h3>';
                        echo '<input type="submit" class="button" name="visualizzaPersonale" value="VISUALIZZA" />';
                    }
                    else{
                        echo '<h3 class="specialText">Visualizza le domande di tutti i clienti:</h3>';
                        echo '<input type="submit" class="button" name="visualizzaAll" value="VISUALIZZA" />';
                    }
                }
            ?>                
                         
            </div>
        </div>

    <?php
        $numDomande = $listaDomande->length;
        if($numDomande >= 1){
            for($i=0 ; $i < $numDomande ; $i++){ 
                $domanda = $listaDomande->item($i);
                $idDomanda = $domanda->getAttribute("id"); 
                $elevataAFaq = $domanda->getAttribute("faq");                
                $nomeAutore = $domanda->getElementsByTagName("nomeAutore")->item(0)->textContent;
                $cognomeAutore = $domanda->getElementsByTagName("cognomeAutore")->item(0)->textContent;                        
    ?>
                <div class="mainContainer minHeight">
                    <div class="topBox">
                            <div class="miniBox specialText">
                                <?php echo $nomeAutore." ".$cognomeAutore;?>
                            </div>
                            <div class="miniBox specialText">
                                    Categoria: <?php echo $domanda->getElementsByTagName("categoria")->item(0)->textContent;?>
                            </div>
                    </div>

                    <div class="middleBox">
                        <?php echo $domanda->getElementsByTagName("testoDomanda")->item(0)->textContent;?>                   
                    </div>

                    <div class="bottomBox">
                        <input type="submit" class="specialButton" name="<?php echo $idDomanda;?>" value="VISUALIZZA RISPOSTE" />
                    <?php
                        if(isset($accessoStaff) && $elevataAFaq != "true"){
                    ?>
                            <input type="submit" class="specialButton" name="<?php echo $idDomanda;?>" value="ELEVA A FAQ" />          
                    <?php
                        }
                    ?>
                    
                    </div>
                </div>
    <?php
            }
        }
        else{
            echo '<p class="alignCenter scrittaCentrale">Non sono state trovate domande...</p>';
        }
    ?>
            <input type="hidden" name="bottonePremuto" value="bottonePremuto" />
        <?php
            if(isset($domandePersonali)){
                echo '<input type="hidden" name="domandePersonali" value="bottonePremuto" />';
            }

            if(isset($categoriaScelta)){
                echo "<input type=\"hidden\" name=\"categoriaScelta\" value=\"{$categoriaScelta}\" />";
            }
        ?>

        </form>
    </body>
</html>
