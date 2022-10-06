<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniInsertPHP.php');
    session_start();

    if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA'])   ){
        if(isset($_POST['ANNULLA'])){
            if(isset($_POST['aggiungiRecensione'])){
                header('Location: recensioni.php');
                exit();
            }
            elseif(isset($_POST['aggiungiCommento']) || isset($_POST['aggiungiRispostaCommento'])){
                $_SESSION['recensioneScelta'] = $_POST['idRecensione'];   
                header('Location: risposteRecensione.php');
                exit();
            }
        }
        else{
            if(isset($_POST['aggiungiRecensione'])){
                if(isset($_POST['scegliCategoria']) && $_POST['testoRecensione'] != "" && isset($_POST['scegliVoto'])){
                    aggiungiRecensione($_SESSION['codFiscUtenteLoggato'] , $_POST['scegliCategoria'] , $_POST['testoRecensione'] , $_POST['scegliVoto']);
                    header('Location: recensioni.php');
                    exit();
                }
                else{
                    $arrayDati['idRecensione'] = "";
                    $arrayDati['tipoAzione'] = "aggiungiRecensione";
                    $_SESSION['tipoAzioneRecensione'] = $arrayDati;
                }
            }
            elseif(isset($_POST['aggiungiCommento'])){
                if($_POST['testoCommento'] != "" ){
                    aggiungiCommento($_POST['idRecensione'] , $_SESSION['codFiscUtenteLoggato'] , $_POST['testoCommento']);
                    $_SESSION['recensioneScelta'] = $_POST['idRecensione'];                    
                    header('Location: risposteRecensione.php');
                    exit();
                }
                else{
                    $arrayDati['idRecensione'] = $_POST['idRecensione'];
                    $arrayDati['tipoAzione'] = "aggiungiCommento";
                    $_SESSION['tipoAzioneRecensione'] = $arrayDati;                    
                }
            }
            elseif(isset($_POST['aggiungiRispostaCommento'])){
                if($_POST['testoRisposta'] != ""){
                    aggiungiRispostaCommento($_POST['idCommento'] , $_SESSION['codFiscUtenteLoggato'] , $_POST['testoRisposta']);
                    $_SESSION['recensioneScelta'] = $_POST['idRecensione'];
                    header('Location: risposteRecensione.php');
                    exit();
                }
                else{
                    $arrayDati['idRecensione'] = $_POST['idRecensione'];
                    $arrayDati['idCommento'] = $_POST['idCommento'];
                    $arrayDati['tipoAzione'] = "aggiungiRispostaCommento";
                    $_SESSION['tipoAzioneRecensione'] = $arrayDati;
                }
            }
        }
    }

    if(isset($_SESSION['tipoAzioneRecensione'])){
        $temp = $_SESSION['tipoAzioneRecensione'];
        if($temp['tipoAzione'] == "aggiungiRecensione"){
            $categorieCliente = getCategorieCliente($_SESSION['codFiscUtenteLoggato']);  
            $aggiungiRecensione = "True";
        }
        elseif($temp['tipoAzione'] == "aggiungiCommento"){
            $idRecensione = $temp['idRecensione'];
            $datiRecensione = getDatiRecensione($idRecensione);
            $aggiungiCommento = "True";
        }
        elseif($temp['tipoAzione'] == "aggiungiRispostaCommento"){ 
            $idRecensione = $temp['idRecensione'];                       
            $idCommento = $temp['idCommento'];
            $datiCommento =getDatiCommento($idCommento);
            $aggiungiRispostaCommento = "True";
        }

        unset($_SESSION['tipoAzioneRecensione']);
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
        <title>Modifica recensioni</title>

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
            if(isset($aggiungiRecensione)){
                echo '<h1>AGGIUNGI RECENSIONE:</h1>';        
            }        
            elseif(isset($aggiungiCommento)){
                echo '<h1>AGGIUNGI COMMENTO:</h1>';
            }
            elseif(isset($aggiungiRispostaCommento)){
                echo '<h1>AGGIUNGI RISPOSTA AL COMMENTO:</h1>';
            }
        ?>
        
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
            <div class="mainArea">                   
            <?php
                if(isset($aggiungiRecensione)){
            ?>
                    <span class="item">Scegli la categoria:</span>
                    <div class="row">                            
                        <select class="selectInput" name="scegliCategoria">
                        <?php
                            if(isset($_POST['CONFERMA']) && isset($_POST['scegliCategoria'])){
                                echo "<option selected value=\"{$_POST['scegliCategoria']}\">{$_POST['scegliCategoria']}</option> ";
                                $nomiCategorie = array_keys($categorieCliente);    
                                for($i=0 ; $i < count($nomiCategorie) ; $i++){
                                    $nomeCategoria = $nomiCategorie[$i];
                                    if($_POST['scegliCategoria'] != $nomeCategoria ){
                                        if($categorieCliente[$nomeCategoria] == "Attiva"){
                                            echo "<option value=\"{$nomeCategoria}\">{$nomeCategoria}</option>";
                                        }
                                        else{
                                            echo "<option disabled value=\"{$nomeCategoria}\">{$nomeCategoria}</option>";
                                        }
                                    }
                                }  
                            }
                            else{
                                echo '<option disabled selected value="Scegli">-- Scegli -- </option>';
                                $nomiCategorie = array_keys($categorieCliente);    
                                for($i=0 ; $i < count($nomiCategorie) ; $i++){
                                    $nomeCategoria = $nomiCategorie[$i];
                                    if($categorieCliente[$nomeCategoria] == "Attiva"){
                                        echo "<option value=\"{$nomeCategoria}\">{$nomeCategoria}</option>";
                                    }
                                    else{
                                        echo "<option disabled value=\"{$nomeCategoria}\">{$nomeCategoria}</option>";
                                    }
                                }      
                            }                                                                                                                    
                        ?>
                        </select>
                    </div>    
                <?php
                    if(isset($_POST['CONFERMA']) && !isset($_POST['scegliCategoria'])){
                        echo '<p class="errorLabel">Scegliere una categoria!</p>';
                    }
                ?>
                    <span class="item marginTop">Testo recensione:</span>
                    <div class="row">
                        <textarea type="text" class="textInput" name="testoRecensione"><?php if(isset($_POST['testoRecensione'])){echo $_POST['testoRecensione'];}?></textarea>  
                    </div>
                <?php
                    if(isset($_POST['CONFERMA']) && $_POST['testoRecensione'] == "" ){
                        echo '<p class="errorLabel">Dati mancanti!</p>';
                    }
                ?>

                    <span class="item marginTop">Voto recensione:</span>
                    <div class="row">     
                        <select class="selectInput" style="text-align: center; width: 20%; min-width: fit-content;" name="scegliVoto">
                        <?php
                            if(isset($_POST['CONFERMA']) && isset($_POST['scegliVoto'])){
                                echo "<option selected value=\"{$_POST['scegliVoto']}\">{$_POST['scegliVoto']}</option> ";
                            }
                            else{
                                echo '<option disabled selected value="Scegli">-- Scegli -- </option>';      
                            }
                        ?>                            
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
            <?php
                    if(isset($_POST['CONFERMA']) && !isset($_POST['scegliVoto'] )){
                        echo '<p class="errorLabel">Inserire un voto!</p>';
                    }
                 
                }
                elseif(isset($aggiungiCommento)){
            ?>
                    <span class="item">Recensione:</span>
                    <div class="row">
                        <?php echo $datiRecensione['testoRecensione'];?>
                    </div>

                    <span class="item marginTop">Commento:</span>
                    <div class="row">
                        <textarea type="text" class="textInput" name="testoCommento"><?php if(isset($_POST['testoCommento'])){echo $_POST['testoCommento'];}?></textarea>  
                    </div>                
            <?php         
                    if(isset($_POST['CONFERMA']) && $_POST['testoCommento'] == ""  ){
                        echo '<p class="errorLabel">Dati mancanti!</p>';
                    }
            
                }
                elseif(isset($aggiungiRispostaCommento)){
            ?>
                    <span class="item">Commento:</span>
                    <div class="row">
                        <?php echo $datiCommento['testo'];?>
                    </div>

                    <span class="item marginTop">Risposta:</span>
                    <div class="row">
                        <textarea type="text" class="textInput" name="testoRisposta"><?php if(isset($_POST['testoRisposta'])){echo $_POST['testoRisposta'];}?></textarea>  
                    </div>  
            <?php
                    if(isset($_POST['CONFERMA']) && $_POST['testoRisposta'] == ""  ){
                        echo '<p class="errorLabel">Dati mancanti!</p>';
                    }
            
                }
            ?>
                                                
                    
                                                     
                                                        
            <?php
                if(isset($aggiungiRecensione)){
                    echo '<input type="hidden" name="aggiungiRecensione" />';
                }
                elseif(isset($aggiungiCommento)){
                    echo "<input type=\"hidden\" name=\"aggiungiCommento\" />";
                    echo "<input type=\"hidden\" name=\"idRecensione\" value=\"{$idRecensione}\" />";                    
                }
                elseif(isset($aggiungiRispostaCommento)){
                    echo "<input type=\"hidden\" name=\"aggiungiRispostaCommento\" />";
                    echo "<input type=\"hidden\" name=\"idRecensione\" value=\"{$idRecensione}\" />";
                    echo "<input type=\"hidden\" name=\"idCommento\" value=\"{$idCommento}\" />";
                }
            ?>
                    

                <div class="zonaBottoni">                    
                    <input type="submit" class="button" name="ANNULLA" value="ANNULLA" />
                    <input type="submit" class="button" name="CONFERMA" value="CONFERMA" />                                            
                </div>

            </div>
           
                   
            </form>
        </div>


          

    </body>
</html>