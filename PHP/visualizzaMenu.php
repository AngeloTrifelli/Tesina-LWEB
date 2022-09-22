<?php
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
        <div class="mainContainer marginBottom">
            <div class="areaDati">
                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Nome</strong></td>
                        <td><strong>Prezzo</strong></td>
                    </tr>
                    <tr>
                        <td>
                            Antipasto di montagna
                        </td>
                        <td>
                            8&euro;
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
                    <tr>
                        <td>
                            Antipasto di mare
                        </td>
                        <td>
                            7&euro;
                        </td>
                    <?php
                        if(isset($accessoStaff)){
                    ?>
                        <td>
                            <input type="submit" class="button" name="Antipasto di mare" value="MODIFICA" />
                            <input type="submit" class="button" name="Antipasto di mare" value="ELIMINA" />
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                </table>
            </div>
        </div>
    
        <h3 class="titoloImportante alignCenter">PRIMI PIATTI:</h3>
        <div class="mainContainer marginBottom">
            <div class="areaDati">
                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Nome</strong></td>
                        <td><strong>Prezzo</strong></td>
                    </tr>
                    <tr>
                        <td>
                            Spaghetti alla carbonara
                        </td>
                        <td>
                            8&euro;
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
                    <tr>
                        <td>
                            Lasagne
                        </td>
                        <td>
                            9&euro;
                        </td>
                    <?php
                        if(isset($accessoStaff)){
                    ?>
                        <td>
                            <input type="submit" class="button" name="Lasagne" value="MODIFICA" />
                            <input type="submit" class="button" name="Lasagne" value="ELIMINA" />
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                </table>
            </div>
        </div>

        <h3 class="titoloImportante alignCenter">SECONDI PIATTI:</h3>
        <div class="mainContainer marginBottom">
            <div class="areaDati">
                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Nome</strong></td>
                        <td><strong>Prezzo</strong></td>
                    </tr>
                    <tr>
                        <td>
                            Tagliata di manzo
                        </td>
                        <td>
                            15&euro;
                        </td>
                    <?php
                        if(isset($accessoStaff)){
                    ?>
                        <td>
                            <input type="submit" class="button" name="Tagliata di manzo" value="MODIFICA" />
                            <input type="submit" class="button" name="Tagliata di manzo" value="ELIMINA" />
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td>
                            Grigliata mista
                        </td>
                        <td>
                            20&euro;
                        </td>
                    <?php
                        if(isset($accessoStaff)){
                    ?>
                        <td>
                            <input type="submit" class="button" name="Grigliata mista" value="MODIFICA" />
                            <input type="submit" class="button" name="Grigliata mista" value="ELIMINA" />
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                </table>
            </div>
        </div>

        <h3 class="titoloImportante alignCenter">DOLCI:</h3>
        <div class="mainContainer marginBottom">
            <div class="areaDati">
                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Nome</strong></td>
                        <td><strong>Prezzo</strong></td>
                    </tr>
                    <tr>
                        <td>
                            Tiramisu
                        </td>
                        <td>
                            3&euro;
                        </td>
                    <?php
                        if(isset($accessoStaff)){
                    ?>
                        <td>
                            <input type="submit" class="button" name="Tiramisu" value="MODIFICA" />
                            <input type="submit" class="button" name="Tiramisu" value="ELIMINA" />
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                    <tr>
                        <td>
                            Sorbetto al limone
                        </td>
                        <td>
                            2&euro;
                        </td>
                    <?php
                        if(isset($accessoStaff)){
                    ?>
                        <td>
                            <input type="submit" class="button" name="Sorbetto al limone" value="MODIFICA" />
                            <input type="submit" class="button" name="Sorbetto al limone" value="ELIMINA" />
                        </td>
                    <?php
                        }
                    ?>
                    </tr>
                </table>
            </div>
        </div>

        <?php
            if(isset($accessoStaff)){
        ?>
            </form>
        <?php
            }
        ?>
    </body>
</html>