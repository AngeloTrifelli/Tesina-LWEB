<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');

    session_start();
    
    $oraUpdateConfermata=confermaOraUpdateMenu();
    $oraUpdateConfermata = "True";

    if(isset($_SESSION['portataDaModificare']) && isset($_SESSION['loginType']) && $_SESSION['loginType'] != "Cliente" && $oraUpdateConfermata == "True"){
        $descrizionePortata=$_SESSION['portataDaModificare'];
        $prezzoPortata=getPrezzoPortata($descrizionePortata['nomeSeparato']);
    }
    else{
        unset($_SESSION['portataDaModificare']);
        header('Location: visualizzaMenu.php');
        exit();
    }

      

    $error="False";
    $nomePortataGiaEsistente="False";

    if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA'])){
        if(isset($_POST['ANNULLA'])){
            unset($_SESSION['portataDaModificare']);
            header('Location: visualizzaMenu.php');
            exit();
        }
        else{            
            if($_POST['nomePortata']!="" || $_POST['prezzo']!=""){
                if($_POST['nomePortata']!=""){
                    $nomePortataGiaEsistente=checkNomePortata($_POST['nomePortata']);
                    if($nomePortataGiaEsistente=="False"){
                        modificaPortata($_POST['nomePortata'],$_POST['prezzo']);
                        unset($_SESSION['portataDaModificare']);
                        header('Location: visualizzaMenu.php');
                        exit();
                    }
                }
                else{
                    modificaPortata($_POST['nomePortata'],$_POST['prezzo']);
                    unset($_SESSION['portataDaModificare']);
                    header('Location: visualizzaMenu.php');
                }
            }
            else{
                $error="True";
            }        
        }
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
            <h1>MODIFICA PORTATA</h1>
                

            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >

                <div class="mainArea">
                    <div class="zonaSuperiore">
                        
                        <div class="zonaSx">

                            <span class="item">Nome portata:</span>
                                        
                            <p> <?php echo $descrizionePortata['nomeSeparato']; ?> </p>
                                  
                            <span class="item">Nuovo nome:</span>

                            <input type="text" class="textInput" name="nomePortata" placeholder="Inserisci il nome" />
                            
                        </div>

                        <div class="zonaDx">
                            <h2 class="noSpace">Prezzo:</h2>
                            <p><?php echo $prezzoPortata.'&euro;';?></p>
                            <span class="item">Nuovo prezzo:</span>
                            <input type="number" class="textInput" name="prezzo" />                      
                        </div>    

                    </div>

                    <div class="zonaBottoni">
                        <input type="submit" class="button" name="ANNULLA" value="ANNULLA" />
                        <input type="submit" class="button" name="CONFERMA" value="CONFERMA" />
                    </div>
                    
                </div>
            </form>
            <?php
        if ($error=="True"){
            echo "
                <div class\"riga\">
                <p class=\"errorLabel\">Cambiare almeno un dato!</p>
                </div>
            ";
            }
            if ($nomePortataGiaEsistente=="True"){
                echo "
                    <div class\"riga\">
                    <p class=\"errorLabel\">Nome della portata gi?? presente nel menu!</p>
                    </div>
                ";
                }
    ?>
        </div>

        <script>
            $('input[type=number][name=prezzo]').on("change" , function(e){            
                if(e.target.value < 0){
                    e.target.value = "";
                }
            })
        </script>
        
    </body>
</html>