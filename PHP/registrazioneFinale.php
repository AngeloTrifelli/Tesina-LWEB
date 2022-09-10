<?php 
echo'<?xml version="1.0" encoding="UTF-8"?>';

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

            $xmlStringClienti = "";
            foreach(file("../XML/Clienti.xml") as $node){
            $xmlStringClienti .= trim($node);
            }
            
            $docClienti = new DOMDocument();
            $docClienti->loadXML($xmlStringClienti);
            $docClienti->formatOutput = true;

            $listaClienti = $docClienti->documentElement->childNodes;
            $i = 0;
            while($i < $listaClienti->length && $duplicato == "False"){          //Controllo se un utente con tale username è già registrato
                $cliente = $listaClienti->item($i);
                $elemCredenziali= $cliente->getElementsByTagName("credenziali")->item(0);
                $testoUsername=$elemCredenziali->firstChild->textContent;
                if($testoUsername == $_POST['username']){
                    $duplicato = "True";
                }
                else{
                    $i++;
                }
            }
            if($duplicato == "False"){

                $username= $_POST['username'];
                $password= $_POST['password'];
                $_SESSION['username'] = $_POST['username'];   //Imposto questa variabile di sessione in modo tale da permettere di effettuare il controllo dentro registrazioneCompletata.php

                $anno = mb_substr($_SESSION['dataNascita'], 0 , 4);
                $mese = mb_substr($_SESSION['dataNascita'], 5 , 2);
                $giorno = mb_substr($_SESSION['dataNascita'], 8 , 2);

                $currentDate= date('Y-m-d');

                $nuovoCliente = $docClienti->createElement("cliente");
                $nuovoCliente->setAttribute("codFisc", $_SESSION['codFisc']);
                $listaClienti = $docClienti->documentElement;
                $listaClienti->appendChild($nuovoCliente);

                $nuovoNome = $docClienti->createElement("nome", $_SESSION['nome']);
                $nuovoCliente->appendChild($nuovoNome);

                $nuovoCognome = $docClienti->createElement("cognome", $_SESSION['cognome']);
                $nuovoCliente->appendChild($nuovoCognome);

                $nuovaDataNascita = $docClienti->createElement("dataDiNascita", $anno."-".$mese."-".$giorno);
                $nuovoCliente->appendChild($nuovaDataNascita);

                $nuovoIndirizzo = $docClienti->createElement("indirizzo", $_SESSION['indirizzo']);
                $nuovoCliente->appendChild($nuovoIndirizzo);

                $nuovoTelefono = $docClienti->createElement("telefono", $_SESSION['telefono']);
                $nuovoCliente->appendChild($nuovoTelefono);

                $nuovaEmail = $docClienti->createElement("email", $_SESSION['email']);
                $nuovoCliente->appendChild($nuovaEmail);

                $nuovoNumCarta = $docClienti->createElement("numeroCarta", $_SESSION['numeroCarta']);
                $nuovoCliente->appendChild($nuovoNumCarta);

                $nuoveCredenziali = $docClienti->createElement("credenziali");
                $nuovoCliente->appendChild($nuoveCredenziali);

                $nuovoUsername = $docClienti->createElement("username", $username);
                $nuoveCredenziali->appendChild($nuovoUsername);

                $nuovaPassword = $docClienti->createElement("password", $password);
                $nuoveCredenziali->appendChild($nuovaPassword);

                $nuoviCrediti = $docClienti->createElement("crediti", 0);
                $nuovoCliente->appendChild($nuoviCrediti);

                $nuovaDataAssegnazioneCrediti = $docClienti->createElement("dataAssegnazioneCrediti", $currentDate);
                $nuovoCliente->appendChild($nuovaDataAssegnazioneCrediti);

                $nuovaSommaGiudizi= $docClienti->createElement("sommaGiudizi", 0);
                $nuovoCliente->appendChild($nuovaSommaGiudizi);

                $docClienti->save("../XML/clienti.xml");

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