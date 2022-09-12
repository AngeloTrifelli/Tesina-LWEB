<?php 
echo'<?xml version="1.0" encoding="UTF-8"?>';
require_once('funzioniGetPHP.php');


$duplicato = "False";

$patternCodFisc = "/^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$/";        //Il codice fiscale viene considerato valido solamente se rispetta questo pattern
$patternNumCarta= "/^([0-9]{4}-){3}[0-4]{4}$/";
$patternEmail= "/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/";
$patternTelefono= "/^[0-9]{10}$/";

if(isset($_POST['continua'])){
    if ($_POST['nome']!="" && $_POST['cognome']!=""  && $_POST['codFisc']!="" && $_POST['dataNascita']!="" && $_POST['indirizzo']!="" && $_POST['telefono']!="" && $_POST['email']!="" && $_POST['numeroCarta']!="" ){
         
        if(preg_match($patternCodFisc, $_POST['codFisc'])){

            $duplicato = getCodFisc($_POST['codFisc']);                           //Controllo se l'utente è già registrato
           

            if ($duplicato == "False"){
                session_start();
                $_SESSION['nome'] = $_POST['nome'];
                $_SESSION['cognome'] = $_POST['cognome'];
                $_SESSION['codFisc'] = $_POST['codFisc'];
                $_SESSION['dataNascita'] = $_POST['dataNascita'];
                $_SESSION['indirizzo'] = $_POST['indirizzo'];
                $_SESSION['telefono'] = $_POST['telefono'];
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['numeroCarta'] = $_POST['numeroCarta'];

                $_SESSION['accessoPermesso'] = "True";
                header('Location: registrazioneFinale.php');
                exit();
            }
        }
    }
}




?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Sapienza hotel: Registrazione Utente</title>

        <style>
            <?php include "../CSS/registrazioneUtente.css" ?>
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
            

            <a class="item" href="./intro.php">HOME</a>
            <br/>
         
            <a class="item" href="./camere.php">CAMERE E SUITE</a>
            <br/> 
      
            <a class="item" href="#">RECENSIONI</a>
            <br/>

            <a class="item" href="#">PRENOTA ORA</a>
            <br/>

            <a class="item" href="./login.php">LOGIN</a>
            <br/>
  
            </div>
            
        </div>


        <div id="rightColumn" >  

            <h1 id="mainTitle">REGISTRAZIONE UTENTE</h1>
        
        

            <div class="containerCentrale">

                <div class="tabella">

                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

                        <div class="riga">

                            <div class="containerColumn">

                                <?php 
                                        if(isset($_POST['nome'])){
                                            echo "<input class=\"textInput\" type=\"text\" name=\"nome\" value=\"{$_POST['nome']}\" placeholder=\"Inserisci il nome\"   >";
                                        }
                                        else{
                                            echo "<input class=\"textInput\" type=\"text\" name=\"nome\" placeholder=\"Inserisci il nome\" >";     
                                        }
                                        if(isset($_POST['continua']) && $_POST['nome']==""){
                                            echo "
                                                <p class=\"errorLabel\">Inserire il nome!</p> 
                                            ";
                                        }
                                    ?>

                            </div>

                        
                            <div class="containerColumn">

                                <?php 
                                        if(isset($_POST['cognome'])){
                                            echo "<input class=\"textInput\" type=\"text\" name=\"cognome\" value=\"{$_POST['cognome']}\" placeholder=\"Inserisci il cognome\" >";
                                        }
                                        else{
                                            echo "<input class=\"textInput\" type=\"text\" name=\"cognome\" placeholder=\"Inserisci il cognome\" >";     
                                        }
                                        if(isset($_POST['continua']) && $_POST['cognome']==""){
                                            echo "
                                                <p class=\"errorLabel\">Inserire il cognome!</p> 
                                            ";
                                        }
                                    ?>

                            </div>
                        
                        </div>
                    
                    

                        <div class="riga">
                    

                            <div class="containerColumn">

                                <?php 
                                        if(isset($_POST['codFisc'])){
                                            echo "<input class=\"textInput\" id=\"codiceFiscale\" type=\"text\" name=\"codFisc\" value=\"{$_POST['codFisc']}\" placeholder=\"Inserisci il codice fiscale\"   >";
                                        }
                                        else{
                                            echo "<input class=\"textInput\" id=\"codiceFiscale\" type=\"text\" name=\"codFisc\" placeholder=\"Inserisci il codice fiscale\" >";     
                                        }
                                        if(isset($_POST['continua']) && $_POST['codFisc']==""){
                                            echo "
                                                <p class=\"errorLabel\">Inserire il codice fiscale!</p> 
                                            ";
                                        }
                                        elseif (isset($_POST['continua']) && (!preg_match($patternCodFisc, $_POST['codFisc'])) ){
                                            echo "
                                                <p class=\"errorLabel\">Il codice fiscale inserito non è valido!</p>
                                            ";
                                        }
                                    ?>
                            </div>


                            <div class="containerData">

                            <h3 class="title"> Inserisci la data di nascita:</h3>

                                <?php 
                                        if(isset($_POST['dataNascita'])){
                                            echo "<input class=\"dateInput\" type=\"date\" name=\"dataNascita\" value=\"{$_POST['dataNascita']}\" >";
                                        }
                                        else{
                                            echo "<input class=\"dateInput\" type=\"date\" name=\"dataNascita\">";     
                                        }
                                        if(isset($_POST['continua']) && $_POST['dataNascita']==""){
                                            echo "
                                                <p class=\"errorLabel\">Inserire la data di nascita!</p> 
                                            ";
                                        }
                                    ?>

                            </div>

                        </div>

                        <div class="riga">

                            <div class="containerColumn">
                                
                            <?php 
                                    if(isset($_POST['indirizzo'])){
                                        echo "<input class=\"textInput\" type=\"text\" name=\"indirizzo\" value=\"{$_POST['indirizzo']}\" placeholder=\"Inserisci l'indirizzo di domicilio\" >";
                                    }
                                    else{
                                        echo "<input class=\"textInput\" type=\"text\" name=\"indirizzo\" placeholder=\"Inserisci l'indirizzo di domicilio\" >";     
                                    }
                                    if(isset($_POST['continua']) && $_POST['indirizzo']==""){
                                        echo "
                                            <p class=\"errorLabel\">Inserire l'indirizzo!</p> 
                                        ";
                                    }
                                ?>

                            </div>

                            <div class="containerColumn">

                                <?php 
                                    if(isset($_POST['email'])){
                                        echo "<input class=\"textInput\" type=\"text\" name=\"email\" value=\"{$_POST['email']}\" placeholder=\"Inserisci l'email\" >";
                                    }
                                    else{
                                        echo "<input class=\"textInput\" type=\"text\" name=\"email\" placeholder=\"Inserisci l'email\" >";     
                                    }
                                    if(isset($_POST['continua']) && $_POST['email']==""){
                                        echo "
                                            <p class=\"errorLabel\">Inserire l'email!</p> 
                                        ";
                                    }
                                    elseif (isset($_POST['continua']) && (!preg_match($patternEmail, $_POST['email'])) ){
                                        echo "
                                            <p class=\"errorLabel\">Email non valida!</p>
                                        ";
                                    }
                                ?>

                            </div>

                        </div>

                        <div class="riga">

                            <div class="containerColumn">

                                <?php 
                                        if(isset($_POST['telefono'])){
                                            echo "<input class=\"textInput\" type=\"text\" name=\"telefono\" value=\"{$_POST['telefono']}\" placeholder=\"Inserisci il numero di telefono\" >";
                                        }
                                        else{
                                            echo "<input class=\"textInput\" type=\"text\" name=\"telefono\" placeholder=\"Inserisci il numero di telefono\" >";     
                                        }
                                        if(isset($_POST['continua']) && $_POST['telefono']==""){
                                            echo "
                                                <p class=\"errorLabel\">Inserire il numero di telefono!</p> 
                                            ";
                                        }
                                        elseif (isset($_POST['continua']) && (!preg_match($patternTelefono, $_POST['telefono'])) ){
                                            echo "
                                                <p class=\"errorLabel\">Il numero di telefono non è valido!</p>
                                            ";
                                        }
                                    ?>
                                    
                            </div>

                            <div class="containerColumn">

                                <?php 
                                        if(isset($_POST['numeroCarta'])){
                                            echo "<input class=\"textInput\" type=\"text\" name=\"numeroCarta\" value=\"{$_POST['numeroCarta']}\" placeholder=\"Inserisci la carta di credito(????-????-????-????)\" >";
                                        }
                                        else{
                                            echo "<input class=\"textInput\" type=\"text\" name=\"numeroCarta\" placeholder=\"Inserisci la carta di credito(????-????-????-????)\" >";     
                                        }
                                        if(isset($_POST['continua']) && $_POST['numeroCarta']==""){
                                            echo "
                                                <p class=\"errorLabel\">Inserire la carta di credito!</p> 
                                            ";
                                        }
                                        elseif (isset($_POST['continua']) && (!preg_match($patternNumCarta, $_POST['numeroCarta'])) ){
                                            echo "
                                                <p class=\"errorLabel\">La carta di credito non è valida!</p>
                                            ";
                                        }
                                    ?>

                            </div>

                        </div>

                        <input type="submit" class="continuaButton button" name="continua" value="Continua">

                        <?php 
                            if ($duplicato == "True"){
                            echo "
                                <div class\"riga\">
                                <p class=\"errorLabel\">Il cliente risulta essere già registrato!</p>
                                </div>
                            ";
                            }
                        ?>
                     

                    </form>
                        

                

                </div>

            </div>

        </div> 

    </body>

</html>