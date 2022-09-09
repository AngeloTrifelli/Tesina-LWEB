<<<<<<< HEAD
<?php 
    require_once('funzioniPHP.php');
    require_once('funzioniGetPHP.php');

    if(isset($_POST['accedi'])){
        if($_POST['username']!="" && $_POST['password']!="" && isset($_POST['type']) ){
            if($_POST['type'] == "cliente"){
                $codFisc = eseguiLoginCliente($_POST['username'], md5($_POST['password']));
                if($codFisc != "null"){
                    session_start();
                    $_SESSION['codFiscUtenteLoggato'] = $codFisc;
                    $_SESSION['soggiornoAttivo'] = getSoggiornoAttivo($codFisc);
                }
            }
            else{
                if($_POST['type'] == "concierge"){
                    die(var_dump("test2"));
                    // DA IMPLEMENTARE 
                }
                else{
                    die(var_dump("test3"));
                    // DA IMPLEMENTARE
                }
            }
        }
    }


















    echo'<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Sapienza hotel: Registrazione Utente</title>
        <style>
            <?php include "../CSS/login.css" ?>
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

            <a class="item" href="./registrazioneUtente.php">REGISTRATI</a>
            <br/>

  
            </div>
            
        </div>


        <div id="rightColumn" >  
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                <h1 id="mainTitle">LOGIN</h1>

                <div class="containerDati">
                    <div class="divSx">
                    <?php
                        if(isset($_POST['username'])){
                            echo "<input class=\"textInput\" type=\"text\" name=\"username\" placeholder=\"Inserisci l'username\" value=\"{$_POST['username']}\" />";
                        }
                        else{
                            echo "<input class=\"textInput\" type=\"text\" name=\"username\" placeholder=\"Inserisci l'username\" />";
                        }
                        if(isset($_POST['accedi']) && $_POST['username'] == ""){
                            echo "
                                <br />
                                <p class=\"errorLabel\">Inserisci l'username!</p>";
                        }
                    ?>
                        <br>
                        <input class="textInput" type="text" name="password" placeholder="Inserisci la password" >
                    <?php
                        if(isset($_POST['accedi']) && $_POST['password'] == ""){
                            echo "
                                <br />
                                <p class=\"errorLabel\">Inserisci la password!</p>";
                        }
                    ?>
                    </div>

                    <div class="divDx">    
                        
                        <p class="title">Che tipo di utente sei?</p>
                        <br />
                        <input type="radio" id="cliente" name="type" value="cliente" <?php if(isset($_POST['type'])){if($_POST['type']=="cliente"){echo 'checked';}} ?> />
                        <label for="cliente">Cliente</label><br /> 
                        <input type="radio" id="concierge" name="type" value="concierge"  <?php if(isset($_POST['type'])){if($_POST['type']=="concierge"){echo 'checked';}} ?> />
                        <label for="concierge">Concierge</label><br />
                        <input type="radio" id="admin" name="type" value="admin"  <?php if(isset($_POST['type'])){if($_POST['type']=="admin"){echo 'checked';}} ?> />
                        <label for="admin">Admin</label>
                    <?php
                        if(isset($_POST['accedi']) && !isset($_POST['type'])){
                            echo "
                                <br />
                                <br />
                                <p class=\"errorLabel\">Scegliere il tipo di utente!</p>";
                        }
                    ?>


                    </div>
                </div> 

                <input type="submit" class="continuaButton button" name="accedi" value="Accedi">
            </form>
            <div id="registrazione">
                <a  href="./registrazioneUtente.php">Non sei ancora registrato? Clicca qui!</a>
            </div>

        </div>

    </body>

</html>
=======
<?php 
    require_once('funzioniPHP.php');
    require_once('funzioniGetPHP.php');

    if(isset($_POST['accedi'])){
        if($_POST['username']!="" && $_POST['password']!="" && isset($_POST['type']) ){
            if($_POST['type'] == "cliente"){
                $codFisc = eseguiLoginCliente($_POST['username'], md5($_POST['password']));
                if($codFisc != "null"){
                    session_start();
                    $_SESSION['codFiscUtenteLoggato'] = $codFisc;
                    $_SESSION['soggiornoAttivo'] = getSoggiornoAttivo($codFisc);
                    $_SESSION['loginType'] = "Cliente";
                    header('Location: areaUtente.php');
                }
            }
            else{
                if($_POST['type'] == "concierge"){
                    die(var_dump("test2"));
                    // DA IMPLEMENTARE 
                }
                else{
                    die(var_dump("test3"));
                    // DA IMPLEMENTARE
                }
            }
        }
    }


















    echo'<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Sapienza hotel: Registrazione Utente</title>
        <style>
            <?php include "../CSS/login.css" ?>
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

            <a class="item" href="./registrazioneUtente.php">REGISTRATI</a>
            <br/>

  
            </div>
            
        </div>


        <div id="rightColumn" >  
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                <h1 id="mainTitle">LOGIN</h1>

                <div class="containerDati">
                    <div class="divSx">
                    <?php
                        if(isset($_POST['username'])){
                            echo "<input class=\"textInput\" type=\"text\" name=\"username\" placeholder=\"Inserisci l'username\" value=\"{$_POST['username']}\" />";
                        }
                        else{
                            echo "<input class=\"textInput\" type=\"text\" name=\"username\" placeholder=\"Inserisci l'username\" />";
                        }
                        if(isset($_POST['accedi']) && $_POST['username'] == ""){
                            echo "
                                <br />
                                <p class=\"errorLabel\">Inserire l'username!</p>";
                        }
                    ?>
                        <br>
                        <input class="textInput" type="text" name="password" placeholder="Inserisci la password" >
                    <?php
                        if(isset($_POST['accedi']) && $_POST['password'] == ""){
                            echo "
                                <br />
                                <p class=\"errorLabel\">Inserire la password!</p>";
                        }
                        if(isset($_POST['accedi']) && $_POST['username']!="" && $_POST['password']!="" && isset($_POST['type']) && $codFisc=="null"){
                            echo "
                                <br />
                                <p class=\"errorLabel\">Username e/o password errati!</p>";
                        }
                    ?>
                    </div>

                    <div class="divDx">    
                        
                        <p class="title">Che tipo di utente sei?</p>
                        <br />
                        <input type="radio" id="cliente" name="type" value="cliente" <?php if(isset($_POST['type'])){if($_POST['type']=="cliente"){echo 'checked';}} ?> />
                        <label for="cliente">Cliente</label><br /> 
                        <input type="radio" id="concierge" name="type" value="concierge"  <?php if(isset($_POST['type'])){if($_POST['type']=="concierge"){echo 'checked';}} ?> />
                        <label for="concierge">Concierge</label><br />
                        <input type="radio" id="admin" name="type" value="admin"  <?php if(isset($_POST['type'])){if($_POST['type']=="admin"){echo 'checked';}} ?> />
                        <label for="admin">Admin</label>
                    <?php
                        if(isset($_POST['accedi']) && !isset($_POST['type'])){
                            echo "
                                <br />
                                <br />
                                <p class=\"errorLabel\">Scegliere il tipo di utente!</p>";
                        }
                    ?>


                    </div>
                </div> 

                <input type="submit" class="continuaButton button" name="accedi" value="Accedi">
            </form>
            <div id="registrazione">
                <a  href="./registrazioneUtente.php">Non sei ancora registrato? Clicca qui!</a>
            </div>

        </div>

    </body>

</html>
>>>>>>> master
