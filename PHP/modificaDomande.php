<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniModificaPHP.php');
    require_once('funzioniInsertPHP.php');

    session_start();

    if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA'])){
        if(isset($_POST['ANNULLA'])){
            if(isset($_POST['aggiungiDomanda'])){
                header('Location: domande.php');
                exit();
            }   
            else{
                header('Location: risposteDomanda.php');
                exit();
            }        
        }
        else{
            if(isset($_POST['aggiungiDomanda'])){
                if(isset($_POST['scegliCategoria']) && $_POST['testoDomanda'] != ""){
                    aggiungiDomanda($_SESSION['codFiscUtenteLoggato'] , $_POST['scegliCategoria'] , $_POST['testoDomanda']);
                    header('Location: domande.php');
                    exit();
                }
                else{                    
                    $arrayDati['idDomanda'] = "";
                    $arrayDati['tipoAzione'] = "aggiungiDomanda";
                    $_SESSION['tipoAzioneDomanda'] = $arrayDati;
                }
            }  
            elseif(isset($_POST['aggiungiRisposta'])){
                if($_POST['testoRisposta'] != ""  ){
                    if($_SESSION['loginType'] == "Cliente"){
                        aggiungiRispostaDomanda($_POST['idDomanda'] , "Cliente" , $_SESSION['codFiscUtenteLoggato'] , $_POST['testoRisposta']);
                    }
                    else{
                        aggiungiRispostaDomanda($_POST['idDomanda'] , "Staff" , "null" , $_POST['testoRisposta'] );
                    }
                    header('Location: risposteDomanda.php');
                    exit();
                }
                else{
                    $arrayDati['idDomanda'] = $_POST['idDomanda'];
                    $arrayDati['tipoAzione'] = "aggiungiRisposta";
                    $_SESSION['tipoAzioneDomanda'] = $arrayDati;
                }
            }
            elseif(isset($_POST['elevaFaq'])){
                if($_POST['testoRisposta'] != ""){
                    $datiDomanda = getDatiDomanda($_POST['idDomanda']);
                    aggiungiFaq($datiDomanda['testoDomanda'], $_POST['testoRisposta'] , $_POST['idDomanda']);
                    modificaAttributoFaqDomanda($_POST['idDomanda'] , "true");
                    modificaCreditiCliente($datiDomanda['codFiscAutore'] , 100);
                    header('Location: domande.php');
                    exit();
                }
                else{
                    $arrayDati['idDomanda'] = $_POST['idDomanda'];
                    $arrayDati['tipoAzione'] = "elevaFaq";
                    $arrayDati['idRisposta'] = $_POST['idRisposta'];
                    $_SESSION['tipoAzioneDomanda'] = $arrayDati;
                }
            }          
        }
    }


    if(isset($_SESSION['tipoAzioneDomanda'])){
        $temp = $_SESSION['tipoAzioneDomanda'];
        if($temp['tipoAzione'] == "aggiungiDomanda"){            
            $tabelleCategorie = getCategorie();
            $categorieAttive = $tabelleCategorie[0];
            $categorieDisattivate = $tabelleCategorie[1];  
            $aggiungiDomanda = "True";
        }
        elseif($temp['tipoAzione'] == "aggiungiRisposta"){
            $idDomanda = $temp['idDomanda'];
            $datiDomanda = getDatiDomanda($idDomanda);
            $aggiungiRisposta = "True";
        }
        elseif($temp['tipoAzione'] == "elevaFaq"){
            $idDomanda = $temp['idDomanda'];
            $idRisposta = $temp['idRisposta'];
            $datiDomanda = getDatiDomanda($idDomanda);
            $datiRisposta = getDatiRisposta($idRisposta);
            $elevaFaq = "True";
        }
        unset($_SESSION['tipoAzioneDomanda']);
    }
    else{
        header('Location: domande.php');
        exit();
    }


   echo'<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Modifica domande</title>

        <style type="text/css">
            <?php include "../CSS/modificaDomande.css" ?>
        </style>

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&amp;display=swap" />
    </head>

    <body>

        <div class="containerCentrale">
        <?php
            if(isset($aggiungiDomanda)){
                echo '<h1>AGGIUNGI DOMANDA:</h1>';        
            }
            elseif(isset($aggiungiRisposta)){
                echo '<h1>AGGIUNGI RISPOSTA:</h1>';        
            }
            elseif(isset($elevaFaq)){
                echo '<h1>CONFERMA ELEVAZIONE A FAQ:</h1>';        
            }
        ?>
        
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
                <div class="mainArea">   
                <?php
                    if(isset($aggiungiDomanda)){
                ?>
                        <span class="item">Scegli la categoria:</span>
                        <div class="row">
                            <select id="selectInput" name="scegliCategoria">
                                <option disabled selected value="Scegli">-- Scegli -- </option> 
                            <?php                                 
                                for($i=0 ; $i < count($categorieAttive) ; $i++){
                                    $categoria = $categorieAttive[$i];                                
                                    echo "<option value=\"{$categoria['nome']}\">{$categoria['nome']}</option>";                                    
                                }                           

                                for($i=0 ; $i < count($categorieDisattivate) ; $i++){
                                    $categoria = $categorieDisattivate[$i];                                
                                    echo "<option disabled value=\"{$categoria['nome']}\">{$categoria['nome']}</option>"; 
                                }                                                           
                            ?>
                            </select>
                        </div>    
                    <?php
                        if(isset($_POST['CONFERMA']) && !isset($_POST['scegliCategoria'])){
                            echo '<p class="errorLabel">Scegliere una categoria!</p>';
                        }
                    ?>
                        
                        <span class="item marginTop">Domanda:</span>
                        <div class="row">                       
                            <textarea type="text" class="textInput" name="testoDomanda"><?php if(isset($_POST['testoDomanda'])){echo $_POST['testoDomanda'];}?></textarea>  
                        </div>
                    <?php
                        if(isset($_POST['CONFERMA']) && $_POST['testoDomanda'] == "" ){
                            echo '<p class="errorLabel">Dati mancanti!</p>';
                        }
                                                
                    }
                    elseif(isset($aggiungiRisposta) || isset($elevaFaq)){
                ?>
                        <span class="item">Domanda:</span>
                        <div class="row">
                        <?php echo $datiDomanda['testoDomanda'];?>                        
                        </div>

                        <span class="item marginTop">Risposta:</span>
                    <?php
                        if(isset($aggiungiRisposta)){                            
                    ?>
                            <div class="row">                       
                                <textarea type="text" class="textInput" name="testoRisposta"><?php if(isset($_POST['testoRisposta'])){echo $_POST['testoRisposta'];}?></textarea>  
                            </div>  
                    <?php
                        }
                        else{
                            
                    ?>   
                            <div class="row">                       
                                <textarea type="text" class="textInput" name="testoRisposta"><?php if(isset($_POST['testoRisposta'])){echo $_POST['testoRisposta'];}else{echo $datiRisposta['testoRisposta'];}?></textarea>  
                            </div>                       
                    <?php
                        }
                    ?>
                                              
                    <?php
                        if(isset($_POST['CONFERMA']) && $_POST['testoRisposta'] == ""){
                            echo '<p class="errorLabel">Dati mancanti!</p>';
                        }

                    }
                ?>
                                                                                        
                    

                    <div class="zonaBottoni">                    
                        <input type="submit" class="button" name="ANNULLA" value="ANNULLA" />
                        <input type="submit" class="button" name="CONFERMA" value="CONFERMA" />                                            
                    </div>
                </div>
            <?php
                if(isset($aggiungiDomanda)){
                    echo '<input type="hidden" name="aggiungiDomanda" />';
                }
                elseif(isset($aggiungiRisposta)){
                    echo "<input type=\"hidden\" name=\"aggiungiRisposta\" />";
                    echo "<input type=\"hidden\" name=\"idDomanda\" value=\"{$idDomanda}\" />";
                }
                elseif(isset($elevaFaq)){
                    echo "<input type=\"hidden\" name=\"elevaFaq\" />";
                    echo "<input type=\"hidden\" name=\"idDomanda\" value=\"{$idDomanda}\" />";
                    echo "<input type=\"hidden\" name=\"idRisposta\" value=\"{$idRisposta}\" />";
                }
            ?>
                   
            </form>
        </div>


          

    </body>
</html>

