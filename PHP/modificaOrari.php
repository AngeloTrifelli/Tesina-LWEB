<?php
    echo '<?xml version="1.0" encoding="UTF-8?>';
    require_once('funzioniPHP.php');
    require_once('funzioniInsertPHP.php');
    require_once('funzioniGetPHP.php');
    require_once('funzioniModificaPHP.php');

    session_start();
    $orariScorretti="";

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
    
    if(isset($_POST['ristorante']) || isset($_SESSION['idAttivita']) || isset($_POST['idAttivita'])){
        
        if(isset($_SESSION['idAttivita'])){
            $idAttivita=$_SESSION['idAttivita'];
            unset($_SESSION['idAttivita']);
        }
        else{

            if(isset($_POST['annulla']) || isset($_POST['cambia'])){
                if(isset($_POST['annulla'])){
                    unset($_SESSION['idAttivita']);
                    header('Location: listaOrari.php');
                    exit();
                }
                else{
                    if(isset($_POST['ristorante'])){
                        if ($_POST['oraAperturaPranzo']!="" && $_POST['oraChiusuraPranzo']!="" && $_POST['oraAperturaCena']!="" && $_POST['oraChiusuraCena']!=""){
                            modificaOrariRistorante($_POST['oraAperturaPranzo'],$_POST['oraChiusuraPranzo'],$_POST['oraAperturaCena'] , $_POST['oraChiusuraCena']);
                            header('Location: listaOrari.php');
                            exit();
                        }else{
                            $orariScorretti="True";
                        }
                    }else{
                        if($_POST['oraAperturaAttivita']!="" && $_POST['oraChiusuraAttivita']!=""){
                    
                        modificaOrariAttivita($_POST['idAttivita'],$_POST['oraAperturaAttivita'],$_POST['oraChiusuraAttivita']);
                        header('Location: listaOrari.php');
                        exit();
                        }
                        else{
                            $idAttivita=$_POST['idAttivita'];
                            $orariScorretti="True";
                        }
                    }
                    
                }
            }

        }

    }else{
        header('Location: listaOrari.php');
    }   
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
    if(isset($_POST['ristorante'])){

    ?>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

        <div class="riga">

        <div class="containerColumn">


        <p><strong>Inserisci l'orario di apertura a pranzo:</strong></p>
        <?php 
                        if(isset($_POST['oraAperturaPranzo'])){
                            echo "<input  name=\"oraAperturaPranzo\" class=\"oraRistorantePranzo\" value=\"{$_POST['oraAperturaPranzo']}\" />";                        
                        }
                        else{
                            echo "<input  name=\"oraAperturaPranzo\" class=\"oraRistorantePranzo\" />";                        
                        }
                    
                    ?>
        </div>

            <div class="containerColumn">

        <p><strong>Inserisci l'orario di chiusura a pranzo:</strong></p>
        <?php 
                        if(isset($_POST['oraChiusuraPranzo'])){
                            echo "<input  name=\"oraChiusuraPranzo\" class=\"oraRistorantePranzo\" value=\"{$_POST['oraChiusuraPranzo']}\" />";                        
                        }
                        else{
                            echo "<input  name=\"oraChiusuraPranzo\" class=\"oraRistorantePranzo\" />";                        
                        }
                        
                    ?>
            
        </div>

        </div>

        <div class="riga">

        <div class="containerColumn">


        <p><strong>Inserisci l'orario di apertura a cena:</strong></p>
        <?php 
                        if(isset($_POST['oraAperturaCena'])){
                            echo "<input  name=\"oraAperturaCena\" class=\"oraRistoranteCena\" value=\"{$_POST['oraAperturaCena']}\" />";                        
                        }
                        else{
                            echo "<input  name=\"oraAperturaCena\" class=\"oraRistoranteCena\" />";                        
                        }
                    
                    ?>
        </div>

            <div class="containerColumn">

        <p><strong>Inserisci l'orario di chiusura a cena:</strong></p>
        <?php 
                        if(isset($_POST['oraChiusuraCena'])){
                            echo "<input  name=\"oraChiusuraCena\" class=\"oraRistoranteCena\" value=\"{$_POST['oraChiusuraCena']}\" />";                        
                        }
                        else{
                            echo "<input  name=\"oraChiusuraCena\" class=\"oraRistoranteCena\" />";                        
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
        if ($orariScorretti=="True"){
            echo "
                <div class\"riga\">
                <p class=\"errorLabel\">Dati mancanti!</p>
                </div>
            ";
            }
    } 
    ?>
    

    <?php
    if(isset($idAttivita)){
        $attivita=getDatiAttivita($idAttivita);

    ?>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

        <div class="riga">

        <div class="containerColumn">


        <p><strong>Inserisci l'orario di apertura dell'attività:<?php echo $attivita['nome']?></strong></p>
        <?php 
                        if(isset($_POST['oraAperturaAttivita'])){
                            echo "<input  name=\"oraAperturaAttivita\" class=\"oraInizioAttivita\" value=\"{$_POST['oraAperturaAttivita']}\" />";                        
                        }
                        else{
                            echo "<input  name=\"oraAperturaAttivita\" class=\"oraInizioAttivita\" />";                        
                        }
                    
                    ?>
        </div>

            <div class="containerColumn">

        <p><strong>Inserisci l'orario di chiusura dell'attività:<?php echo $attivita['nome']?></strong></p>
        <?php 
                        if(isset($_POST['oraChiusuraAttivita'])){
                            echo "<input  name=\"oraChiusuraAttivita\" class=\"oraFineAttivita\" value=\"{$_POST['oraChiusuraAttivita']}\" />";                        
                        }
                        else{
                            echo "<input  name=\"oraChiusuraAttivita\" class=\"oraFineAttivita\" />";                        
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
        if ($orariScorretti=="True"){
            echo "
                <div class\"riga\">
                <p class=\"errorLabel\">Dati mancanti!</p>
                </div>
            ";
            }
    } 
    ?>
    </div>

    <script>

//Perfezionare la parte sottonstanza 
           
            $(".oraRistorantePranzo").attr("autocomplete" , "off");
            $(".oraRistoranteCena").attr("autocomplete" , "off");

;
            $(".oraInizioAttivita").attr("autocomplete" , "off");
            $(".oraFineAttivita").attr("autocomplete" , "off");

            $('.oraInizioAttivita').timepicker({
                timeFormat: 'HH:mm',
                interval: 60,
                minTime: "00:00",
                maxTime: "23:00",
                dynamic: false,
                dropdown: true,
                scrollbar: true
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
            $('.oraRistoranteCena').timepicker({
                timeFormat: 'HH:mm',
                interval: 30,
                minTime: "18:00",
                maxTime: "23:30",
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });





        </script>

</body>

</html>