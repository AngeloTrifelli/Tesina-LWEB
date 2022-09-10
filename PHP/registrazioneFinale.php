<?php 
echo'<?xml version="1.0" encoding="UTF-8"?>';
require_once('funzioniGetPHP.php');
require_once('funzioniInsertPHP.php');


$duplicato = "False";

session_start();

 

    if (!isset($_POST['registrati'])){
        if (!isset($_SESSION['accessoPermesso'])){     
            header('Location: registrazioneUtente.php');
        }
        else{
            unset($_SESSION['accessoPermesso']);       
        }
    }
    else{    
        if($_POST['username']!="" && $_POST['password']!="" && $_POST['confermaPassword']!="" && $_POST['password']==$_POST['confermaPassword']){

            $duplicato = getUsername($_POST['username']);

            if($duplicato == "False"){

                $username= $_POST['username'];
                $password= $_POST['password'];
                $_SESSION['username'] = $_POST['username'];   //Imposto questa variabile di sessione in modo tale da permettere di effettuare il controllo dentro registrazioneCompletata.php

                nuovoCliente();

                header('Location: registrazioneCompletata.php');
                exit();

            }
        }
    }        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Sapienza hotel: Registrazione Finale</title>

        <style>
            <?php include "../CSS/registrazioneFinale.css" ?>
    </style>
    
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet" />  
      </head>
    
    <body>

        <div id="leftColumn">
                     
            <img id="logo" src="https://upload.wikimedia.org/wikipedia/it/thumb/0/0d/Uniroma1.svg/2560px-Uniroma1.svg.png" alt="Immagine non caricata"/>
         
         
            <div id="links">
                     
         
                <a class="item" href="../PHP/registrazioneUtente.php">TORNA INDIETRO</a>

            </div>

        </div>

        <div id="rightColumn" >  

            <h1 id="mainTitle">REGISTRAZIONE UTENTE</h1>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >



                <div class="riga">

                    <div class="containerColumn">

                        <?php
                                if(isset($_POST['username'])){
                                    echo "<input class=\"textInput\" type=\"text\" name=\"username\" value=\"{$_POST['username']}\" placeholder=\"Inserisci l'username\"   >";
                                }
                                else{
                                    echo "<input class=\"textInput\" type=\"text\" name=\"username\" placeholder=\"Inserisci l'username\" >";     
                                }
                                if(isset($_POST['registrati']) && $_POST['username']==""){
                                    echo "
                                        <p class=\"errorLabel\">Inserire l'username!</p> 
                                    ";
                                }   
                        ?>
                    </div>

                </div>

                <div class="riga">

                    <div class="containerColumn">

                        <input class="textInput" type="text" name="password" placeholder="Inserisci la password" >  
                        <?php 
                        if(isset($_POST['registrati']) && $_POST['password']==""){
                            echo "
                                <p class=\"errorLabel\">Inserire la password!</p> 
                            ";
                        }

                    ?> 

                    </div>  

                </div>

                <div class="riga">

                    <div class="containerColumn">

                        <input class="textInput" type="text" name="confermaPassword" placeholder="Conferma password" >   
                        <?php 
                        if(isset($_POST['registrati']) && $_POST['password']!="" && $_POST['password']!=$_POST['confermaPassword']){
                            echo "
                            <p class=\"errorLabel\">Le password inserite <br /> non corrispondono!</p> 
                            ";
                        }
                    ?> 

                    </div>

                </div>

                <?php 
                            if ($duplicato == "True"){
                            echo "
                                <div class\"riga\">   
                                    
                                    <p class=\"errorLabel\">Il cliente con tale username risulta essere già registrato!</p>
                                
                                </div>
                            ";
                            }
                        ?>


                <input type="submit" class="continuaButton button" name="registrati" value="Registrati" />

                
            </form>        
    
        </div>

    </body>

</html>
