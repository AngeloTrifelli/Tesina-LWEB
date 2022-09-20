<?php 
    require_once('funzioniPHP.php');
    require_once('funzioniGetPHP.php');
    $codFisc = "";
    $trovato = "";
    $erroreSoggiornoAttivo = "";

    session_start();

    if(isset($_SESSION['loginType'])){
        header('Location: areaUtente.php');
    }

    if(isset($_SESSION['prenotazioneCamera'])){
        $prenotazioneCamera = $_SESSION['prenotazioneCamera'];
        $idCamera = $prenotazioneCamera['idCamera'];
        $dataArrivo = $prenotazioneCamera['dataArrivo'];
        $dataPartenza = $prenotazioneCamera['dataPartenza'];
        unset($_SESSION['prenotazioneCamera']);
    }


    if(isset($_POST['accedi'])){
        if(isset($_POST['idCamera'])){
            if($_POST['username']!= "" && $_POST['password'] != ""){
                $codFisc = eseguiLoginCliente($_POST['username'], md5($_POST['password']));
                if($codFisc != "null"){
                    $_SESSION['soggiornoAttivo'] = getSoggiornoAttivo($codFisc);
                    if($_SESSION['soggiornoAttivo'] == "null"){
                        $arrayDati['idCamera'] = $_POST['idCamera'];
                        $arrayDati['dataArrivo'] = $_POST['dataArrivo'];
                        $arrayDati['dataPartenza'] = $_POST['dataPartenza'];
                        $_SESSION['prenotazioneCamera'] = $arrayDati;
                        $_SESSION['codFiscUtenteLoggato'] = $codFisc;
                        $_SESSION['loginType'] = "Cliente";
                        header('Location: confermaPrenotazione.php');
                    }
                    else{
                        $erroreSoggiornoAttivo = "True";
                        $idCamera = $_POST['idCamera'];
                        $dataArrivo = $_POST['dataArrivo'];
                        $dataPartenza = $_POST['dataPartenza'];
                    }
                }
                else{
                    $idCamera = $_POST['idCamera'];
                    $dataArrivo = $_POST['dataArrivo'];
                    $dataPartenza = $_POST['dataPartenza'];
                }
            }
            else{
                $idCamera = $_POST['idCamera'];
                $dataArrivo = $_POST['dataArrivo'];
                $dataPartenza = $_POST['dataPartenza'];
            }
        }
        else{
            if($_POST['username']!="" && $_POST['password']!="" && isset($_POST['type']) ){
                if($_POST['type'] == "cliente"){
                    $codFisc = eseguiLoginCliente($_POST['username'], md5($_POST['password']));
                    if($codFisc != "null"){
                        $_SESSION['codFiscUtenteLoggato'] = $codFisc;
                        $_SESSION['soggiornoAttivo'] = getSoggiornoAttivo($codFisc);
                        $_SESSION['loginType'] = "Cliente";
                        header('Location: intro.php');
                    }
                }
                else{
                    if($_POST['type'] == "concierge"){
                        $trovato = eseguiLoginStaff($_POST['username'], md5($_POST['password']), "Concierge");
                        if($trovato != "False"){
                            $_SESSION['loginType'] = "Concierge";
                            header('Location: areaUtente.php');
                        }
                    }
                    else{
                        $trovato = eseguiLoginStaff($_POST['username'], md5($_POST['password']), "Admin");
                        if($trovato != "False"){
                            $_SESSION['loginType'] = "Admin";
                            header('Location: areaUtente.php');
                        }
                    }
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
            
            <?php
                if(isset($idCamera)){
            ?>
                    <a class="item" href="./prenotaOra.php">ANNULLA PRENOTAZIONE</a>  
            <?php
                }
                else{
            ?>
                <a class="item" href="./intro.php">HOME</a>
                <br/>            
                <a class="item" href="./camere.php">CAMERE E SUITE</a>
                <br/>             
                <a class="item" href="#">RECENSIONI</a>
                <br/>
                <a class="item" href="./prenotaOra.php">PRENOTA ORA</a>
                <br/>
                <a class="item" href="./registrazioneUtente.php">REGISTRATI</a>
                <br/>
            <?php
                }
            ?>

  
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
                        if(isset($_POST['accedi']) && $_POST['username']!="" && $_POST['password']!="" && isset($_POST['type']) && ($codFisc=="null" || $trovato == "False")){
                            echo "
                                <br />
                                <p class=\"errorLabel\">Username e/o password errati!</p>";
                        }

                        if(isset($_POST['accedi']) && isset($_POST['idCamera']) && $codFisc == "null"){
                            echo "
                                <br />
                                <p class=\"errorLabel\">Username e/o password errati!</p>";
                        }

                        if(isset($_POST['accedi']) && isset($_POST['idCamera']) && $erroreSoggiornoAttivo == "True"){
                            echo "
                                <br />
                                <p class=\"errorLabel\">L'utente ha già un soggiorno attivo. Impossibile completare la prenotazione!</p>";
                        }
                    ?>
                    </div>
                    
                    <?php
                        if(!isset($idCamera)){
                    ?>
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
                        }
                    ?>


                    </div>
                </div> 

                <input type="submit" class="continuaButton button" name="accedi" value="Accedi" />

            <?php
                if(isset($idCamera)){
            ?>
                    <input type="hidden" class="continuaButton button" name="idCamera" value="<?php echo $idCamera;?>" />
                    <input type="hidden" class="continuaButton button" name="dataArrivo" value="<?php echo $dataArrivo;?>" />
                    <input type="hidden" class="continuaButton button" name="dataPartenza" value="<?php echo $dataPartenza;?>" />
            <?php
                }
            ?>

            </form>
            <div id="registrazione">
                <a  href="./registrazioneUtente.php">Non sei ancora registrato? Clicca qui!</a>
            </div>

        </div>

    </body>

</html>
