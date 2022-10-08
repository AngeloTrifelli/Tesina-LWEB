<?php
    session_start();

    $patternDate = "/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/";

    if(isset($_SESSION['loginType']) && $_SESSION['loginType'] != "Cliente"){
        header('Location: areaUtente.php');
        exit();
    }

    if(!isset($_SESSION['soggiornoAttivo']) || $_SESSION['soggiornoAttivo'] == "null"){
        if(isset($_POST['VERIFICA'])){
            if($_POST['dataArrivo'] != "" && $_POST['dataPartenza'] != "" ){
                if(preg_match($patternDate , $_POST['dataArrivo']) && preg_match($patternDate , $_POST['dataPartenza'])){
                    $stringaData = $_POST['dataArrivo'];
                    $anno = substr($stringaData, 6 , 4);
                    $mese = substr($stringaData, 3 , 2);
                    $giorno = substr($stringaData , 0 , 2 );
                    $arrayDate['dataArrivo'] = $anno."-".$mese."-".$giorno;
                
                    $stringaData = $_POST['dataPartenza'];
                    $anno = substr($stringaData, 6 , 4);
                    $mese = substr($stringaData, 3 , 2);
                    $giorno = substr($stringaData , 0 , 2 );
                    $arrayDate['dataPartenza'] = $anno."-".$mese."-".$giorno;

                    $_SESSION['datePrenotazioneCamera'] = $arrayDate;
                    $_SESSION['accessoPermesso'] = true;
                    header('Location: visualizzaDisponibilita.php');
                }
                else{
                    $erroreDate = "True";
                }
            }
            else{
                $datiMancanti = "True";
            }
        }
    }
    else{
        header('Location: intro.php');
        exit();
    }




     echo '<?xml version="1.0" encoding="UTF-8"?>';
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
        <title>Sapienza hotel: Prenota ora</title>

        <style>
            <?php include "../CSS/prenotaOra.css" ?>
        </style>

        <link href="https://code.jquery.com/ui/1.13.2/themes/blitzer/jquery-ui.css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet" /> 
    </head>

    <body>
        
        <div id="leftColumn">   
            <img id="logo" src="https://upload.wikimedia.org/wikipedia/it/thumb/0/0d/Uniroma1.svg/2560px-Uniroma1.svg.png" alt="Immagine non caricata"/>
            <div id="links">
                <a class="item" href="./intro.php">HOME</a>
                <br/>
                <a class="item" href="./recensioni.php">RECENSIONI</a>
                <br/>
                <a class="item" href="./faq.php">FAQ</a>
                <br/>
                <?php
                    if(!isset($_SESSION['codFiscUtenteLoggato'])){
                ?>
                    <a class="item" href="./registrazioneUtente.php">REGISTRATI</a>
                    <br/>
                    <a class="item" href="./login.php">LOGIN</a>
                    <br/>
                <?php
                    }
                ?>
            </div>
        </div>

        <div id="rightColumn">
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                <div id="containerCentrale">
                    <h1 id="mainTitle">PRENOTA ORA</h1>
                    <div class="row">
                        <h2 class="text marginRight">Inserisci la data di arrivo:</h2>
                        <h2 class="text" id="partenzaText">Inserisci la data di partenza:</h2>
                    </div>
                    <div class="row">
                        <?php
                            if(isset($_POST['dataArrivo'])){
                                echo "<input type=\"text\" name=\"dataArrivo\" class=\"dateInput marginRight dataArrivo\"  value=\"{$_POST['dataArrivo']}\" />";
                            }
                            else{
                                echo  "<input type=\"text\" name=\"dataArrivo\" class=\"dateInput marginRight dataArrivo\"  />";
                            }
                            
                            if(isset($_POST['dataPartenza'])){
                                echo "<input type=\"text\" name=\"dataPartenza\" class=\"dateInput dataPartenza\" value=\"{$_POST['dataPartenza']}\" />";                        
                            }
                            else{
                                echo "<input type=\"text\" name=\"dataPartenza\" class=\"dateInput dataPartenza\" />";                        
                            }
                        ?>                            
                    </div>
                    <?php
                        if(isset($_POST['VERIFICA']) && isset($datiMancanti)){
                    ?>
                            <div class="row">
                                <h2 class="errorLabel">Dati mancanti!</h2>
                            </div>
                    <?php
                        }
                    ?>
                    <?php
                        if(isset($_POST['VERIFICA']) && isset($erroreDate)){
                    ?>
                            <div class="row">
                                <h2 class="errorLabel">Le date inserite non sono valide!</h2>
                            </div>
                    <?php
                        }
                    ?>
                    <div class="buttonRow">
                        <input type="submit" class="button" name="VERIFICA" value="VERIFICA DISPONIBILIT&Agrave;" />
                    </div>
                </div>
            </form>
        </div>




        <script>
            $(".dataArrivo").attr("autocomplete" , "off");      //Rimuove l'autocompletamento (cioè non vengono suggerite delle date)
            $(".dataArrivo").datepicker({
                dateFormat: 'dd-mm-yy',         
                minDate: new Date(),             //Con questo attributo imposto che la data minima selezionabile è la data odierna
                changeMonth: true,      //Questo attributo permette di selezionare il mese con il menu a tendina
                changeYear: true,        //Stessa cosa, però con l'anno
                onSelect: function(date){
                    var selectedDate = $(".dataArrivo").datepicker("getDate");
                    var msecsInAday = 86400000;
                    var endDate = new Date(selectedDate.getTime() + msecsInAday);
                    $(".dataPartenza").datepicker("option","minDate", endDate);
                }
            });

            $(".dataPartenza").attr("autocomplete" , "off");      
            $(".dataPartenza").datepicker({
                dateFormat: 'dd-mm-yy',         
                minDate: 0,             
                changeMonth: true,      
                changeYear: true
            });
        </script>
        
    </body>

</html>