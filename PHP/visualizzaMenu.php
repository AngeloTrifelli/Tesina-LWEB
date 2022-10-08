<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');
    require_once('funzioniDeletePHP.php');
    session_start();

    if(!isset($_SESSION['codFiscUtenteLoggato'])){
        if(!isset($_SESSION['loginType'])){   
            header('Location: login.php');
            exit();
        }
        else{
            $accessoStaff = "True";
        }
    }
    
    $oraUpdateConfermata=confermaOraUpdateMenu();    

    if(isset($_POST['bottonePremuto'])){

        $descrizionePortata = individuaBottoneDescrizionePortata();
        if($_POST[$descrizionePortata['nomeNonSeparato']]=="ELIMINA"){
           
            if(isset($_COOKIE['Cancella'])){
                unset($_COOKIE['Cancella']);
                setcookie('Cancella', '', time() - 3600, '/');
                rimuoviPortataDalMenu($descrizionePortata['nomeSeparato']);
            }  
            
        }else{
            $_SESSION['portataDaModificare']=$descrizionePortata;
            header('Location: modificaPortata.php');
            exit();
        }

    }

    $orariUpdate=getOrariUpdateRistorante();
    $portate = getPortate();
    $antipasti = $portate[0];
    $primiPiatti = $portate[1];
    $secondiPiatti = $portate[2];
    $dolci = $portate[3];
    

    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Visualizza menù</title>

        <style>
            <?php include "../CSS/visualizzaMenu.css" ?>
        </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    </head>


    <body>
        <?php
            if(isset($accessoStaff) && $oraUpdateConfermata=="True"){
        ?>
                <form action="./aggiungiPortata.php" method="post"  >
        <?php
            }
        ?>
        
        <div class="top">
            <div class="topLeft">
                <?php 
                    if(!isset($accessoStaff)){
                        echo '<a href="./homeRistorante.php">TORNA ALLA HOME<br />DEL RISTORANTE</a>';
                    }
                    else{
                        echo '<a href="./areaUtente.php">TORNA NELL\' AREA UTENTE</a>';    
                    }
                ?>
                
            </div>            
            <?php 
                if(isset($accessoStaff) && $oraUpdateConfermata=="True"){
                    echo '
                    <div>
                    <h1>MEN&Ugrave;</h1>
                    </div>
                    <div style="position:absolute; right:0 ; top:0; padding-top: 3%">
                        <input type="submit" class="fakeLink" name="AGGIUNGI" value="AGGIUNGI PORTATA" />
                    </div>
                    ';
                }
                else{
                    echo '
                    <h1 style="display:inline ;">MEN&Ugrave;</h1>';
                }
            ?>
            
        </div>
        <?php
            if(isset($accessoStaff) && $oraUpdateConfermata=="True"){
        ?>
            </form>
        <?php
            }
        ?>
        <?php
            if(isset($accessoStaff) && $oraUpdateConfermata=="True"){
        ?>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
        <?php
            }
        ?>
        
        <?php
        if(isset($accessoStaff) && $oraUpdateConfermata!="True"){
        ?>
        <p class="alignCenter" style="color:red"><strong>
            Non puoi modificare il menu perchè non ti trovi nel range orario per poterlo fare!<br/>
            Se vuoi modificare le portate devi connetterti tra le <?php echo substr($orariUpdate['oraInizioUpdate'],0,5) ?> e le <?php echo substr($orariUpdate['oraFineUpdate'],0,5) ?>.
        </strong></p>
        <?php
        }
        ?>
    
        <h3 class="titoloImportante alignCenter">ANTIPASTI:</h3>
        <?php
            $numAntipasti = count($antipasti);
            if($numAntipasti >= 1){
        ?>
            <div class="mainContainer marginBottom">
                <div class="areaDati">
                    <table class="alignCenter tabella"  align="center">
                        <tr>
                            <td><strong>Nome</strong></td>
                            <td><strong>Prezzo</strong></td>
                        </tr>
                    <?php
                        for($i=0 ; $i < $numAntipasti ; $i++){
                            $temp = $antipasti[$i];
                    ?>
                            <tr>
                                <td>
                                    <?php echo $temp['descrizione'];?>
                                </td>
                                <td>
                                    <?php echo $temp['prezzo'];?>&euro;
                                </td>
                            <?php
                                if(isset($accessoStaff) && $oraUpdateConfermata=="True"){
                            ?>
                                <td>
                                    <input type="submit" class="button" name="<?php echo str_replace(" ", "", $temp['descrizione']);?>" value="MODIFICA" />
                                    <input type="submit" class="button" name="<?php echo str_replace(" ", "", $temp['descrizione']);?>" onClick="myEvent()" value="ELIMINA" />


                                </td>
                            <?php
                                }
                            ?>
                            </tr>
                    <?php
                        }
                    ?>                       
                    </table>
                </div>
            </div>
        <?php
            }
            else{
                echo '<p class="alignCenter scrittaCentrale">Non sono disponibili antipasti al momento...</p>';
            }
        ?>
    
        <h3 class="titoloImportante alignCenter">PRIMI PIATTI:</h3>
        <?php
            $numPrimiPiatti = count($primiPiatti);
            if($numPrimiPiatti >= 1){
        ?>
        <div class="mainContainer marginBottom">
            <div class="areaDati">
                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Nome</strong></td>
                        <td><strong>Prezzo</strong></td>
                    </tr>
                <?php
                    for($i=0 ; $i < $numPrimiPiatti ; $i++){
                        $temp = $primiPiatti[$i];
                ?>
                        <tr>
                            <td>
                                <?php echo $temp['descrizione'];?>
                            </td>
                            <td>
                                <?php echo $temp['prezzo'];?>&euro;
                            </td>
                        <?php
                            if(isset($accessoStaff) && $oraUpdateConfermata=="True"){
                        ?>
                            <td>
                                <input type="submit" class="button" name="<?php echo str_replace(" ", "", $temp['descrizione']);?>" value="MODIFICA" />
                                <input type="submit" class="button" name="<?php echo str_replace(" ", "", $temp['descrizione']);?>" onClick="myEvent()" value="ELIMINA" />

                            </td>
                        <?php
                            }
                        ?>
                        </tr>
                <?php
                    }
                ?>                     
                </table>
            </div>
        </div>
        <?php
            }
            else{
                echo '<p class="alignCenter scrittaCentrale">Non sono disponibili primi piatti al momento...</p>';
            }
        ?>

        <h3 class="titoloImportante alignCenter">SECONDI PIATTI:</h3>
        <?php
            $numSecondiPiatti = count($secondiPiatti);
            if($numSecondiPiatti >= 1){
        ?>
        <div class="mainContainer marginBottom">
            <div class="areaDati">
                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Nome</strong></td>
                        <td><strong>Prezzo</strong></td>
                    </tr>
                <?php
                    for($i=0 ; $i < $numSecondiPiatti ; $i++){
                        $temp = $secondiPiatti[$i];
                ?>
                        <tr>
                            <td>
                                <?php echo $temp['descrizione'];?>
                            </td>
                            <td>
                                <?php echo $temp['prezzo'];?>&euro;
                            </td>
                        <?php
                            if(isset($accessoStaff) && $oraUpdateConfermata=="True"){
                        ?>
                            <td>
                                <input type="submit" class="button" name="<?php echo str_replace(" ", "", $temp['descrizione']);?>" value="MODIFICA" />
                                <input type="submit" class="button" name="<?php echo str_replace(" ", "", $temp['descrizione']);?>" onClick="myEvent()" value="ELIMINA" />

                            </td>
                        <?php
                            }
                        ?>
                        </tr>
                <?php
                    }
                ?>                     
                </table>
            </div>
        </div>
        <?php
            }
            else{
                echo '<p class="alignCenter scrittaCentrale">Non sono disponibili secondi piatti al momento...</p>';
            }
        ?>

        <h3 class="titoloImportante alignCenter">DOLCI:</h3>
        <?php
            $numDolci = count($dolci);
            if($numDolci >= 1){
        ?>
        <div class="mainContainer marginBottom">
            <div class="areaDati">
                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Nome</strong></td>
                        <td><strong>Prezzo</strong></td>
                    </tr>
                <?php
                    for($i=0 ; $i < $numDolci ; $i++){
                        $temp = $dolci[$i];
                ?>
                        <tr>
                            <td>
                                <?php echo $temp['descrizione'];?>
                            </td>
                            <td>
                                <?php echo $temp['prezzo'];?>&euro;
                            </td>
                        <?php
                            if(isset($accessoStaff) && $oraUpdateConfermata=="True"){
                        ?>
                            <td>
                                <input type="submit" class="button" name="<?php echo str_replace(" ", "", $temp['descrizione']);?>" value="MODIFICA" />
                                <input type="submit" class="button" name="<?php echo str_replace(" ", "", $temp['descrizione']);?>" onClick="myEvent()" value="ELIMINA" />

                            </td>
                        <?php
                            }
                        ?>
                        </tr>
                <?php
                    }
                ?>                     
                </table>
            </div>
        </div>
        <?php
            }
            else{
                echo '<p class="alignCenter scrittaCentrale">Non sono disponibili dolci al momento...</p>';
            }
        ?>

        <?php
            if(isset($accessoStaff) && $oraUpdateConfermata=="True"){
                
        ?>
            <input type="hidden" name="bottonePremuto" />

            </form>
        <?php
            }
        ?>

        <script>
            function myEvent(){
                var choice =confirm("Confermi di voler eliminare la portata ?");
                if(choice == true){
                    document.cookie = "Cancella" + "=" + "Cancella" + "" + "; path=/";  
                }
            }
        </script>
    </body>
</html>