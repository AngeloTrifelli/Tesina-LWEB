<?php 
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');

    session_start();
   
    if(isset($_SESSION['loginType'])){
        if($_SESSION['loginType'] == "Cliente"){
            $accessoCliente = "True";
            $categorieCliente = getCategorieCliente($_SESSION['codFiscUtenteLoggato']);            
        }       
    }


    if(isset($_POST['bottonePremuto'])){
        if(isset($_POST['AGGIUNGI'])){
            $arrayDati['idRecensione'] = "";
            $arrayDati['tipoAzione'] = "aggiungiRecensione";
            $_SESSION['tipoAzioneRecensione'] = $arrayDati;
            header('Location: modificaRecensione.php');
            exit();
        }
        elseif(isset($_POST['CONFERMA'])){            
            if(isset($_POST['scegliCategoria'])){      
                if(isset($_POST['recensioniPersonali'])){
                    $listaRecensioni = getRecensioni($_SESSION['codFiscUtenteLoggato'] , $_POST['scegliCategoria']);
                    $recensioniPersonali = "True";
                    $categoriaScelta = $_POST['scegliCategoria'];
                }          
                else{
                    $listaRecensioni = getRecensioni("null" , $_POST['scegliCategoria']);
                    $categoriaScelta = $_POST['scegliCategoria'];
                }                
            }
            else{
                if(isset($_POST['recensioniPersonali'])){
                    $listaRecensioni = getRecensioni($_SESSION['codFiscUtenteLoggato'] , "null");
                    $recensioniPersonali = "True";
                    $erroreCategoria = "True";
                }
                else{
                    $listaRecensioni = getRecensioni("null" , "null");
                    $erroreCategoria = "True";
                }
            }
            
        }
        elseif(isset($_POST['allCategorie'])){
            if(isset($_POST['recensioniPersonali'])){
                $listaRecensioni = getRecensioni($_SESSION['codFiscUtenteLoggato'] , "null");
                $recensioniPersonali = "True";
            }
            else{
                $listaRecensioni = getRecensioni("null" , "null");
            }   
        }
        elseif(isset($_POST['visualizzaPersonale'])){
            if(isset($_POST['categoriaScelta'])){
                $listaRecensioni = getRecensioni($_SESSION['codFiscUtenteLoggato'] , $_POST['categoriaScelta']);
                $recensioniPersonali = "True";
                $categoriaScelta = $_POST['categoriaScelta'];
            }
            else{
                $listaRecensioni = getRecensioni($_SESSION['codFiscUtenteLoggato'] , "null");
                $recensioniPersonali = "True";
            }                        
        }
        elseif(isset($_POST['visualizzaAll'])){
            if(isset($_POST['categoriaScelta'])){
                $listaRecensioni = getRecensioni("null" , $_POST['categoriaScelta']);
                $categoriaScelta = $_POST['categoriaScelta'];
            }
            else{
                $listaRecensioni = getRecensioni("null" , "null");
            }        
        }
        else{
            $idRecensione = individuaBottoneRecensione();
            if($_POST[$idRecensione] == "VISUALIZZA COMMENTI"){
                $_SESSION['recensioneScelta'] = $idRecensione;
                header('Location: risposteRecensione.php');
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
    }
    else{
        $listaRecensioni = getRecensioni("null" , "null");
    }

    $tabelleCategorie = getCategorie();


    echo'<?xml version="1.0" encoding="UTF-8"?>';
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
                <a href="./intro.php">TORNA ALLA HOME</a>  
            </div>  
        <?php
            if(isset($accessoCliente)){
        ?>
                <div>
                    <input type="submit" id="fakeLink" name="AGGIUNGI"   value="AGGIUNGI RECENSIONE" />
                </div>    
        <?php
            }
        ?>                             
        </div>

        <div class="mainContainer marginTop">
            <h1 id="mainTitle">RECENSIONI</h1>
            <div class="row">
            <?php
                if(!isset($categoriaScelta)){                
            ?>
                <h3 class="specialText">Scegli la categoria:</h3>
                <select name="scegliCategoria"   id="selectInput">
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
            ?>
                    <h3 class="specialText">Categoria scelta: <?php echo $categoriaScelta;?></h3>
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
                if(isset($accessoCliente)){
                    if(!isset($recensioniPersonali)){
                        echo '<h3 class="specialText">Visualizza le tue recensioni:</h3>';
                        echo '<input type="submit" class="button" name="visualizzaPersonale" value="VISUALIZZA" />';
                    }
                    else{
                        echo '<h3 class="specialText">Visualizza le recensioni di tutti i clienti:</h3>';
                        echo '<input type="submit" class="button" name="visualizzaAll" value="VISUALIZZA" />';
                    }
                }
            ?>         
            </div>
        </div>
      
    <?php
        $numRecensioni = $listaRecensioni->length;
        if($numRecensioni >= 1){
            for($i=0 ; $i < $numRecensioni ; $i++){ 
                $recensione = $listaRecensioni->item($i);
                $idRecensione = $recensione->getAttribute("id");                                
                $nomeAutore = $recensione->getElementsByTagName("nomeAutore")->item(0)->textContent;
                $cognomeAutore = $recensione->getElementsByTagName("cognomeAutore")->item(0)->textContent; 
                $votoRecensione = $recensione->getElementsByTagName("voto")->item(0)->textContent;        
                $categoriaRecensione = $recensione->getElementsByTagName("categoria")->item(0)->textContent;                           
    ?>
                    
                <div class="mainContainer marginTop">
                    <div class="topBox">
                            <div class="miniBox" style="padding-top: 1.2% ;">
                            <?php
                                $numStelleVuote = 5 - $votoRecensione;
                                for($j=0 ; $j < $votoRecensione ; $j++){
                                    echo '<span class="larger"><span class="fa fa-star checked"></span><span>';
                                }
                                
                                for($j=0; $j < $numStelleVuote ; $j++){
                                    echo '<span class="larger"><span class="fa fa-star"></span><span>';
                                }
                            ?>                                
                            </div>
                            <div class="miniBox specialText">
                                Autore: <?php echo $nomeAutore." ".$cognomeAutore;?>
                            </div>                
                            <div class="miniBox specialText">
                                Categoria: <?php echo $categoriaRecensione?>
                            </div>
                    </div>

                    <div class="middleBox">
                        <?php echo $recensione->getElementsByTagName("testoRecensione")->item(0)->textContent;?>                             
                    </div>

                    <div class="bottomBox">
                        <span class="specialText" style="color: white;">Utilit&agrave; totale: <?php echo $recensione->getElementsByTagName("utilita")->item(0)->textContent;?></span>
                        <span class="specialText" style="color: white;">Accordo totale: <?php echo $recensione->getElementsByTagName("accordo")->item(0)->textContent;?></span>
                        <input type="submit" class="specialButton" name="<?php echo $idRecensione;?>" value="VISUALIZZA COMMENTI" />      
                    <?php
                        if(isset($accessoCliente)){
                            $codFiscAutoreRecensione = $recensione->getElementsByTagName("codFiscAutore")->item(0)->textContent;
                            if($_SESSION['codFiscUtenteLoggato'] != $codFiscAutoreRecensione && array_key_exists($categoriaRecensione , $categorieCliente)){
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
    <?php
            }
        }
        else{
            echo '<p class="alignCenter scrittaCentrale">Non sono state trovate recensioni...</p>';
        }
    ?>
            <input type="hidden" name="bottonePremuto" value="bottonePremuto" />
        <?php
            if(isset($recensioniPersonali)){
                echo '<input type="hidden" name="recensioniPersonali" value="bottonePremuto" />';
            }

            if(isset($categoriaScelta)){
                echo "<input type=\"hidden\" name=\"categoriaScelta\" value=\"{$categoriaScelta}\" />";
            }
        ?>        

    
        </form>
    </body>
</html>