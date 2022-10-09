<?php    
    require_once('funzioniPHP.php');
    require_once('funzioniInsertPHP.php');
    require_once('funzioniGetPHP.php');
    require_once('funzioniModificaPHP.php');

    $patternOrarioRistorante = "/^[0-9]{2}:[03]{1}[0]{1}$/";
    $patternOrarioAttivita = "/^[0-9]{2}:[0]{2}$/";
    $patternOrarioUpdate = "/^[0-9]{2}:[0]{2}$/";

    session_start();

    if(!isset($_SESSION['codFiscUtenteLoggato'])){
        if(!isset($_SESSION['loginType'])){
            header('Location: intro.php');
            exit();
        }
    }
    else{
        header('Location: areaUtente.php');
        exit();
    }
    $checkOrariRistorante="False";
    if(isset($_POST['ristorante']) || isset($_POST['updateRistorante']) || isset($_SESSION['idAttivitaOrariDaModificare']) || isset($_POST['idAttivita'])){
        
        if(isset($_SESSION['idAttivitaOrariDaModificare'])){
            $idAttivita=$_SESSION['idAttivitaOrariDaModificare'];
            unset($_SESSION['idAttivitaOrariDaModificare']);
        }
        else{

            if(isset($_POST['annulla']) || isset($_POST['cambia'])){
                if(isset($_POST['annulla'])){
                    unset($_SESSION['idAttivitaOrariDaModificare']);
                    header('Location: listaOrari.php');
                    exit();
                }
                else{
                    if(isset($_POST['ristorante'])){
                        if (preg_match($patternOrarioRistorante, $_POST['oraAperturaPranzo']) && preg_match($patternOrarioRistorante, $_POST['oraChiusuraPranzo']) && preg_match($patternOrarioRistorante, $_POST['oraAperturaCena']) && preg_match($patternOrarioRistorante, $_POST['oraChiusuraCena'])){
                            if($_POST['oraAperturaPranzo']<$_POST['oraChiusuraPranzo'] &&  $_POST['oraAperturaCena']<$_POST['oraChiusuraCena']){
                            modificaOrariRistorante($_POST['oraAperturaPranzo'],$_POST['oraChiusuraPranzo'],$_POST['oraAperturaCena'] , $_POST['oraChiusuraCena']);
                            header('Location: listaOrari.php');
                            exit();
                            }else{
                                $orariScorretti="True";
                            }
                        }else{
                            if(isset($_POST['oraAperturaPranzo']) && !preg_match($patternOrarioRistorante , $_POST['oraAperturaPranzo'])){
                                $erroreOrarioAperturaPranzo = "True";
                            }
                            if(isset($_POST['oraChiusuraPranzo']) && !preg_match($patternOrarioRistorante , $_POST['oraChiusuraPranzo'])){
                                $erroreOrarioChiusuraPranzo = "True";
                            }
                            if(isset($_POST['oraAperturaCena']) && !preg_match($patternOrarioRistorante , $_POST['oraAperturaCena'])){
                                $erroreOrarioAperturaCena = "True";
                            }
                            if(isset($_POST['oraChiusuraCena']) && !preg_match($patternOrarioRistorante , $_POST['oraChiusuraCena'])){
                                $erroreOrarioChiusuraCena = "True";
                            }
                        }
                    }elseif(isset($_POST['updateRistorante'])){
                        if (preg_match($patternOrarioUpdate,$_POST['oraInizioUpdate']) && preg_match($patternOrarioUpdate,$_POST['oraFineUpdate'])){
                            $checkOrariRistorante=checkOrariRistorante($_POST['oraInizioUpdate'],$_POST['oraFineUpdate']);
                            if($checkOrariRistorante=="False"){
                                if($_POST['oraInizioUpdate']<$_POST['oraFineUpdate']){
                            modificaOrariUpdateRistorante($_POST['oraInizioUpdate'],$_POST['oraFineUpdate']);
                            header('Location: listaOrari.php');
                            exit();
                                }else{
                                    $orariScorretti="True";
                                }
                            }
                        }else{
                            if(isset($_POST['oraInizioUpdate']) && !preg_match($patternOrarioUpdate, $_POST['oraInizioUpdate'])){
                                $erroreOrarioInizioUpdate = "True";
                            }
                            if(isset($_POST['oraFineUpdate']) && !preg_match($patternOrarioUpdate, $_POST['oraFineUpdate'])){
                                $erroreOrarioFineUpdate = "True";
                            }
                        }

                    }else{
                        if(preg_match($patternOrarioAttivita,$_POST['oraAperturaAttivita']) && preg_match($patternOrarioAttivita,$_POST['oraChiusuraAttivita'])){
                            if($_POST['oraAperturaAttivita']<$_POST['oraChiusuraAttivita']){
                            modificaOrariAttivita($_POST['idAttivita'],$_POST['oraAperturaAttivita'],$_POST['oraChiusuraAttivita']);
                            header('Location: listaOrari.php');
                        exit();
                            }else{
                                $idAttivita=$_POST['idAttivita'];
                                $orariScorretti="True";
                            }
                        }
                        else{
                            $idAttivita=$_POST['idAttivita'];
                            if(isset($_POST['oraAperturaAttivita']) && !preg_match($patternOrarioAttivita , $_POST['oraAperturaAttivita'])){
                                $erroreOrarioAperturaAttivita = "True";
                            }
                            if(isset($_POST['oraChiusuraAttivita']) && !preg_match($patternOrarioAttivita , $_POST['oraChiusuraAttivita'])){
                                $erroreOrarioChiusuraAttivita = "True";
                            }
                        }
                    }
                    
                }
            }
        
        }
    
    }
    else{
        header('Location: listaOrari.php');
        exit();
    }   

    echo '<?xml version="1.0" encoding="UTF-8?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Modifica orari</title>

    <style>
        <?php include "../CSS/modificaOrari.css" ?>
    </style>

        <link href="https://code.jquery.com/ui/1.13.2/themes/blitzer/jquery-ui.css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>



    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&display=swap" />
</head>

<body>
    <div class="containerCentrale">

    <h1>MODIFICA ORARI:</h1>

    <?php
    if(isset($_POST['updateRistorante'])){

    ?>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

        <div class="riga">

        <div class="containerColumn">


        <p><strong>Inserisci l'orario di inizio update:</strong></p>
        <?php 
                        if(isset($_POST['oraInizioUpdate'])){
                            echo "<input  name=\"oraInizioUpdate\" class=\"oraUpdate\" value=\"{$_POST['oraInizioUpdate']}\" />";                        
                        }
                        else{
                            echo "<input  name=\"oraInizioUpdate\" class=\"oraUpdate\" />";                        
                        }
                        if(isset($_POST['cambia']) && $_POST['oraInizioUpdate'] ==""){

                            echo '<p class="errorLabel">Inserire l\'ora di inizio update!</p>';
                        }
                        if(isset($_POST['cambia']) && isset($erroreOrarioInizioUpdate) && $_POST['oraInizioUpdate']!=""){

                            echo '<p class="errorLabel">L\'orario inserito non è valido!</p>';
                        }
                    
                    ?>
        </div>

            <div class="containerColumn">

        <p><strong>Inserisci l'orario di fine update:</strong></p>
        <?php 
                        if(isset($_POST['oraFineUpdate'])){
                            echo "<input  name=\"oraFineUpdate\" class=\"oraUpdate\" value=\"{$_POST['oraFineUpdate']}\" />";                        
                        }
                        else{
                            echo "<input  name=\"oraFineUpdate\" class=\"oraUpdate\" />";                        
                        }
                        if(isset($_POST['cambia']) && $_POST['oraFineUpdate'] ==""){

                            echo '<p class="errorLabel">Inserire l\'ora di fine update!</p>';
                        }
                        if(isset($_POST['cambia']) && isset($erroreOrarioFineUpdate) && $_POST['oraFineUpdate']!=""){

                            echo '<p class="errorLabel">L\'orario inserito non è valido!</p>';
                        }
                        
                    ?>
            
        </div>

        </div>

        
        <div class="spaceBetween">
        <input type="submit" class="buttonSx" value="Annulla" name="annulla" />
        <input type="submit" class="buttonDx" value="Cambia" name="cambia" />
        <input type="hidden" name="updateRistorante" value="updateRistorante">
        </div>

        </form>
    <?php
            if ($checkOrariRistorante=="True"){
                echo "
                    <div class\"riga\">
                    <p class=\"errorLabel\">Il range orario va in contrasto con gli orari di pranzo e di cena!</p>
                    </div>
                ";
                }
            if (isset($orariScorretti)){
                    echo "
                        <div class\"riga\">
                        <p class=\"errorLabel\">L'orario di inizio non può essere maggiore o uguale di quello di fine!</p>
                        </div>
                    ";
                    }
    } 
    ?>

    <?php
    if(isset($_POST['ristorante'])){

    ?>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

        <div class="riga">

        <div class="containerColumn">


        <p><strong>Inserisci l'orario di apertura a pranzo:</strong></p>
        <?php 
                        if(isset($_POST['oraAperturaPranzo'])){
                            echo "<input type=\"text\" name=\"oraAperturaPranzo\" class=\"textInput oraRistorantePranzo\" value=\"{$_POST['oraAperturaPranzo']}\" />";                        
                        }
                        else{
                            echo "<input type=\"text\" name=\"oraAperturaPranzo\" class=\"textInput oraRistorantePranzo\" />";                        
                        }
                        if(isset($_POST['cambia']) && $_POST['oraAperturaPranzo'] ==""){

                            echo '<p class="errorLabel">Inserire l\'ora di apertura a pranzo!</p>';
                        }
                        if(isset($_POST['cambia']) && isset($erroreOrarioAperturaPranzo) && $_POST['oraAperturaPranzo']!=""){

                            echo '<p class="errorLabel">L\'orario inserito non è valido!</p>';
                        }
                        
                    
                    ?>
        </div>

            <div class="containerColumn">

        <p><strong>Inserisci l'orario di chiusura a pranzo:</strong></p>
        <?php 
                        if(isset($_POST['oraChiusuraPranzo'])){
                            echo "<input type=\"text\"  name=\"oraChiusuraPranzo\" class=\"textInput oraRistorantePranzo\" value=\"{$_POST['oraChiusuraPranzo']}\" />";                        
                        }
                        else{
                            echo "<input type=\"text\"  name=\"oraChiusuraPranzo\" class=\"textInput oraRistorantePranzo\" />";                        
                        }
                        if(isset($_POST['cambia']) && $_POST['oraChiusuraPranzo'] ==""){

                            echo '<p class="errorLabel">Inserire l\'ora di chiusura a pranzo!</p>';
                        }
                        if(isset($_POST['cambia']) && isset($erroreOrarioChiusuraPranzo) && $_POST['oraChiusuraPranzo']!=""){

                            echo '<p class="errorLabel">L\'orario inserito non è valido!</p>';
                        }
                        
                    ?>
            
        </div>

        </div>

        <div class="riga">

        <div class="containerColumn">


        <p><strong>Inserisci l'orario di apertura a cena:</strong></p>
        <?php 
                        if(isset($_POST['oraAperturaCena'])){
                            echo "<input type=\"text\"  name=\"oraAperturaCena\" class=\"textInput oraRistoranteCena\" value=\"{$_POST['oraAperturaCena']}\" />";                        
                        }
                        else{
                            echo "<input type=\"text\"  name=\"oraAperturaCena\" class=\"textInput oraRistoranteCena\" />";                        
                        }
                        if(isset($_POST['cambia']) && $_POST['oraAperturaCena'] ==""){

                            echo '<p class="errorLabel">Inserire l\'ora di apertura a cena!</p>';
                        }
                        if(isset($_POST['cambia']) && isset($erroreOrarioAperturaCena) && $_POST['oraAperturaCena']!=""){

                            echo '<p class="errorLabel">L\'orario inserito non è valido!</p>';
                        }
                        
                    ?>
        </div>

            <div class="containerColumn">

        <p><strong>Inserisci l'orario di chiusura a cena:</strong></p>
        <?php 
                        if(isset($_POST['oraChiusuraCena'])){
                            echo "<input type=\"text\"  name=\"oraChiusuraCena\" class=\"textInput oraRistoranteCena\" value=\"{$_POST['oraChiusuraCena']}\" />";                        
                        }
                        else{
                            echo "<input type=\"text\"  name=\"oraChiusuraCena\" class=\"textInput oraRistoranteCena\" />";                        
                        }
                        if(isset($_POST['cambia']) && $_POST['oraChiusuraCena'] ==""){

                            echo '<p class="errorLabel">Inserire l\'ora di chiusura a cena!</p>';
                        }
                        if(isset($_POST['cambia']) && isset($erroreOrarioChiusuraCena) && $_POST['oraChiusuraCena']!=""){

                            echo '<p class="errorLabel">L\'orario inserito non è valido!</p>';
                        }
                        
                    ?>
            
        </div>

        </div>

        <div class="spaceBetween">
        <input type="submit" class="buttonSx" value="Annulla" name="annulla" />
        <input type="submit" class="buttonDx" value="Cambia" name="cambia" />
        <input type="hidden" name="ristorante" value="ristorante">
        </div>


        </form>

    <?php
                if (isset($orariScorretti)){
                    echo "
                        <div class\"riga\">
                        <p class=\"errorLabel\">L'orario d'apertura non può essere maggiore o uguale di quello di chiusura!</p>
                        </div>
                    ";
                    }
    }
    if(isset($idAttivita)){
        $attivita=getDatiAttivita($idAttivita);

    ?>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

        <div class="riga">

        <div class="containerColumn">


        <p><strong>Inserisci l'orario di apertura dell'attività:<?php echo $attivita['nome']?></strong></p>
        <?php 
                        if(isset($_POST['oraAperturaAttivita'])){
                            echo "<input type=\"text\"  name=\"oraAperturaAttivita\" class=\"oraInizioAttivita\" value=\"{$_POST['oraAperturaAttivita']}\" />";                        
                        }
                        else{
                            echo "<input type=\"text\"  name=\"oraAperturaAttivita\" class=\"oraInizioAttivita\" />";                        
                        }
                        if(isset($_POST['cambia']) && $_POST['oraAperturaAttivita'] ==""){

                            echo '<p class="errorLabel">Inserire l\'ora di apertura dell\'attività!</p>';
                        }
                        if(isset($_POST['cambia']) && isset($erroreOrarioAperturaAttivita) && $_POST['oraAperturaAttivita']!=""){

                            echo '<p class="errorLabel">L\'orario inserito non è valido!</p>';
                        }
                    
                    ?>
        </div>

            <div class="containerColumn">

        <p><strong>Inserisci l'orario di chiusura dell'attività:<?php echo $attivita['nome']?></strong></p>
        <?php 
                        if(isset($_POST['oraChiusuraAttivita'])){
                            echo "<input type=\"text\"  name=\"oraChiusuraAttivita\" class=\"oraFineAttivita\" value=\"{$_POST['oraChiusuraAttivita']}\" />";                        
                        }
                        else{
                            echo "<input type=\"text\"  name=\"oraChiusuraAttivita\" class=\"oraFineAttivita\" />";                        
                        }
                        if(isset($_POST['cambia']) && $_POST['oraChiusuraAttivita'] ==""){

                            echo '<p class="errorLabel">Inserire l\'ora di chiusura dell\'attività!</p>';
                        }
                        if(isset($_POST['cambia']) && isset($erroreOrarioChiusuraAttivita) && $_POST['oraChiusuraAttivita']!=""){

                            echo '<p class="errorLabel">L\'orario inserito non è valido!</p>';
                        }
                        
                    ?>
            
        </div>

        </div>

        <div class="spaceBetween">
        <input type="submit" class="buttonSx" value="Annulla" name="annulla" />
        <input type="submit" class="buttonDx" value="Cambia" name="cambia" />
        <input type="hidden" name="idAttivita" value="<?php echo $idAttivita?>">
        </div>

        </form>
    <?php
                if (isset($orariScorretti)){
                    echo "
                        <div class\"riga\">
                        <p class=\"errorLabel\">L'orario d'apertura non può essere maggiore o uguale di quello di chiusura!</p>
                        </div>
                    ";
                    }
    } 
    ?>
    </div>

    <script>                    
            $(".oraRistorantePranzo").attr("autocomplete" , "off");
            $(".oraRistoranteCena").attr("autocomplete" , "off");        
            $(".oraInizioAttivita").attr("autocomplete" , "off");
            $(".oraFineAttivita").attr("autocomplete" , "off");

            $(".oraUpdate").attr("autocomplete" , "off");

            $('.oraInizioAttivita').timepicker({
                timeFormat: 'HH:mm',
                interval: 60,
                minTime: "00:00",
                maxTime: "23:00",
                dynamic: false,
                dropdown: true,
                scrollbar: true,
            });
            
            
            $('.oraFineAttivita').timepicker({
                timeFormat: 'HH:mm',
                interval: 60,
                minTime: "00:00",
                maxTime: "23:00",
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });

            $('.oraRistorantePranzo').timepicker({
                timeFormat: 'HH:mm',
                interval: 30,
                minTime: "11:00",
                maxTime: "17:30",
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
            $(".oraRistorantePranzo").on("change", function(){                                           
                        var element = $(this).val();             
                        if(JSON.stringify(element).length > 5){
                            $(this).val(element.substring(0,5));
                        }
                        
                        if(element < "11:00"){
                            $(this).val("11:00");
                        }
                        
                        if(element > "17:30"){                                                                        
                            $(this).val("17:30");
                        }                                                
                    });
            $('.oraRistoranteCena').timepicker({
                timeFormat: 'HH:mm',
                interval: 30,
                minTime: "18:00",
                maxTime: "23:30",
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
            $(".oraRistoranteCena").on("change", function(){                                           
                        var element = $(this).val();             
                        if(JSON.stringify(element).length > 5){
                            $(this).val(element.substring(0,5));
                        }
                        
                        if(element < "18:00"){
                            $(this).val("18:00");
                        }
                        
                        if(element > "23:30"){                                                                        
                            $(this).val("23:30");
                        }                                                
                    });

            
            $('.oraUpdate').timepicker({
                timeFormat: 'HH:mm',
                interval: 60,
                minTime: "00:00",
                maxTime: "23:00",
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });



        </script>

</body>

</html>