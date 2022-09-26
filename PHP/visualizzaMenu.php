<?php
    require_once('funzioniGetPHP.php');

    session_start();
    if(isset($_SESSION['codFiscUtenteLoggato'])){
        $temp = $_SESSION['soggiornoAttivo'];
        if($temp != "null"){
            if($temp['statoSoggiorno'] != "Approvato"){
                header('Location: areaUtente.php');
            }
        }
        else{
            header('Location: prenotaOra.php');
        }
    }
    else{
        if(!isset($_SESSION['loginType'])){   
            header('Location: login.php');
        }
        else{
            $accessoStaff = "True";
        }
    }

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
            if(isset($accessoStaff)){
        ?>
            <form action="./modificaPortata.php" method="post"  >
        <?php
            }
        ?>
        <div class="top">
            <div class="topLeft">
                <?php 
                    if(!isset($accessoStaff)){
                        echo '<a href="./homeRistorante.php">TORNA INDIETRO</a>';
                    }
                    else{
                        echo '<a href="./areaUtente.php">TORNA INDIETRO</a>';    
                    }
                ?>
                
            </div>
            <?php 
                if(!isset($accessoStaff)){
                    echo '
                    <h1 class="alignCenter">MEN&Ugrave;</h1>
                    <div style="width: 18.5%;"></div>';
                }
                else{
                    echo '
                    <h1 class="alignCenter" style="margin-left: 2%;">MEN&Ugrave;</h1>
                    <div style="padding-right: 2%;">
                        <input type="submit" class="fakeLink" name="AGGIUNGI" value="AGGIUNGI PORTATA" />
                    </div>
                    ';
                }
            ?>

            
        </div>

    
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
                                if(isset($accessoStaff)){
                            ?>
                                <td>
                                    <input type="submit" class="button" name="Antipastodimontagna" value="MODIFICA" />
                                    <input type="submit" class="button" name="Antipastodimontagna" value="ELIMINA" />
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
                            if(isset($accessoStaff)){
                        ?>
                            <td>
                                <input type="submit" class="button" name="Spaghetti alla carbonara" value="MODIFICA" />
                                <input type="submit" class="button" name="Spaghetti alla carbonara" value="ELIMINA" />
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
                            if(isset($accessoStaff)){
                        ?>
                            <td>
                                <input type="submit" class="button" name="Spaghetti alla carbonara" value="MODIFICA" />
                                <input type="submit" class="button" name="Spaghetti alla carbonara" value="ELIMINA" />
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
                            if(isset($accessoStaff)){
                        ?>
                            <td>
                                <input type="submit" class="button" name="Spaghetti alla carbonara" value="MODIFICA" />
                                <input type="submit" class="button" name="Spaghetti alla carbonara" value="ELIMINA" />
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
            if(isset($accessoStaff)){
        ?>
            </form>
        <?php
            }
        ?>
    </body>
</html>