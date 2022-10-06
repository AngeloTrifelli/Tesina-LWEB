<?php
   require_once('funzioniInsertPHP.php');

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

    $datiNonInseriti="False";
    if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA'])){
        if(isset($_POST['ANNULLA'])){
            header('Location: faq.php');
            exit();
        }
        else{
            if($_POST['domanda']!=""&& $_POST['risposta']!=""){
                aggiungiFaq($_POST['domanda'],$_POST['risposta'],"null");
                header('Location: faq.php');
                exit();
            }else{
                $datiNonInseriti="True";
            }
        }
    }
    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Aggiungi faq</title>

        <style type="text/css">
            <?php include "../CSS/aggiungiFaq.css" ?>
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
            <h1>AGGIUNGI FAQ:</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
            
            <div class="mainArea">
                    <div class="zonaSuperiore">
                        <div class="zonaSx">

                            <span class="item">Scrivi il testo della domanda:</span>
                            <?php 
                                    if(isset($_POST['domanda'])){
                                        echo "<textarea class=\"textInput note\" type=\"text\" name=\"domanda\"/>{$_POST['domanda']}</textarea>";
                                    }
                                    else{
                                        echo "<textarea class=\"textInput note\" type=\"text\" name=\"domanda\"/></textarea>";
                                    }
                            ?>                            
                        </div>

                        <div class="zonaDx">

                            <span class="item">Scrivi il testo della risposta:</span>
                            <?php
                            if(isset($_POST['risposta'])){
                                        echo "<textarea class=\"textInput note\" type=\"text\" name=\"risposta\"/>{$_POST['risposta']}</textarea>";
                                    }
                                    else{
                                        echo "<textarea class=\"textInput note\" type=\"text\" name=\"risposta\"/></textarea>";
                                    }
                            ?>
                        </div>
                    </div>
                    
                    <div class="zonaBottoni">
                        <input type="submit" class="button" name="ANNULLA" value="ANNULLA" />
                        <input type="submit" class="button" name="CONFERMA" value="CONFERMA" />

                    </div>
                    <?php
                            if($datiNonInseriti=="True"){

                            echo "
                                <p class=\"errorLabel\">Dati mancanti!</p> 
                            ";
                            }
                            ?>

                    </div>
            </form>
        </div>
    </body>
</html>