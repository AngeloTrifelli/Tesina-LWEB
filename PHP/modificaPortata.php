<?php
    session_start();
    if(!isset($_SESSION['codFiscUtenteLoggato'])){
        if(!isset($_SESSION['loginType'])){
            header('Location: intro.php');
        }
    }
    else{
        header('Location: areaUtente.php');
    }

    if(isset($_POST['ANNULLA'])){
        header('Location: visualizzaMenu.php');
    }

    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Modifica menu</title>

        <style type="text/css">
            <?php include "../CSS/modificaPortata.css" ?>
        </style>

        <link href="https://code.jquery.com/ui/1.13.2/themes/blitzer/jquery-ui.css" rel="stylesheet"/>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css" />

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&amp;display=swap" />
    </head>

    <body>

        <div class="containerCentrale">
        <?php
            if(isset($_POST['AGGIUNGI'])){
                echo '<h1>AGGIUNGI PORTATA:</h1>';
            }
            else{
                if($_POST['Antipastodimontagna'] == "MODIFICA"){
                    echo '<h1>MODIFICA PORTATA</h1>';
                }
                else{
                    echo '<h1>ELIMINA PORTATA</h1>';
                }
            }
        ?>
        
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >

                <div class="mainArea">
                    <div class="zonaSuperiore">
                        
                        <div class="zonaSx">
                            <?php 
                                if(isset($_POST['AGGIUNGI'])){
                            ?>
                                    <span class="item">Scegli il tipo di portata:</span>
                                    <select id="selectInput" name="tipoPortata">
                                        <option disabled selected value="Scegli">-- Scegli -- </option>
                                        <option value="Antipasto">Antipasto</option>
                                        <option value="Primo piatto">Primo piatto</option>
                                        <option value="Secondo piatto">Secondo piatto</option>
                                        <option value="Dolce">Dolce</option>
                                    </select>
                            <?php        
                                }
                                else{
                                    if($_POST['Antipastodimontagna'] == "ELIMINA"){
                                        echo
                                         '<span class="item">Tipo portata:</span>
                                          <p>Antipasto</p>    
                                        ';
                                    
                                    }
                                }
                            ?>
                            
                            <?php
                                if(isset($_POST['Antipastodimontagna'])){
                                    if($_POST['Antipastodimontagna'] == "MODIFICA" ){
                            ?>
                                    <span class="item">Inserisci il nome della portata:</span>
                                    <input type="text" class="textInput" name="nomePortata" placeholder="Inserisci il nome" />
                            <?php
                                    }
                                    else{
                                        echo
                                        '<span class="item">Nome portata:</span>
                                        <p>Antipasto di montagna</p>    
                                        ';
                                    }
                                }
                                else{
                            ?>
                                    <span class="item">Inserisci il nome della portata:</span>
                                    <input type="text" class="textInput" name="nomePortata" placeholder="Inserisci il nome" />
                            <?php
                                }
                            ?>
                            
                        </div>

                        <div class="zonaDx">
                            <?php 
                                if(isset($_POST['AGGIUNGI'])){
                                    echo '<h2>Prezzo:</h2>';
                                }
                                else{
                                    echo '<h2 class="noSpace">Prezzo:</h2>';
                                }
                                
                                if(isset($_POST['Antipastodimontagna'])){
                                    if($_POST['Antipastodimontagna'] == "MODIFICA"){
                                        echo '<input type="number" class="textInput" name="prezzo" />';
                                    }
                                    else{
                                        echo '<p>8 &euro;</p>';
                                    }
                                }   
                                else{
                                    echo '<input type="number" class="textInput" name="prezzo" />';
                                }
                            ?>                                
                            
                        </div>    

                    </div>

                    <div class="zonaBottoni">
                        <input type="submit" class="button" name="ANNULLA" value="ANNULLA" />
                        <input type="submit" class="button" name="CONFERMA" value="CONFERMA" />
                    </div>
                </div>
            </form>
        </div>

        
    </body>
</html>