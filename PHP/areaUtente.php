<?php
    session_start();
    $soggiornoAttivo = "";
    $tipoLogin = "";

    if(isset($_SESSION['loginType'])){
        if(isset($_POST['LOGOUT'])){
            if($_SESSION['loginType'] == "Cliente"){
                unset($_SESSION['codFiscUtenteLoggato']);
                unset($_SESSION['soggiornoAttivo']);
                unset($_SESSION['loginType']);
            }
            else{
                unset($_SESSION['loginType']);
            }
            header('Location: intro.php');
        }

        if($_SESSION['loginType'] == "Cliente"){
            $tipoLogin = "Cliente";
            if($_SESSION['soggiornoAttivo'] != "null"){
                $temp = $_SESSION['soggiornoAttivo'];
                if($temp['statoSoggiorno'] == "Approvato"){
                    $soggiornoAttivo = "True";
                }
                else{
                    $soggiornoAttivo = "False";
                }
            }
            else{
                $soggiornoAttivo = "False";
            }
        }
        else{
            if($_SESSION['loginType'] == "Concierge"){
                $tipoLogin = "Concierge";
            }
            else{
                $tipoLogin = "Admin";
            }
        }
    }
    else{   
        header('Location: intro.php');
    }

    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">


    <head>
        <title>Sapienza hotel: Area  Utente</title>
        <style>
            <?php include "../CSS/areaUtente.css" ?>
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
                switch($tipoLogin){
                    case "Cliente":
                        echo ' <a class="item" href="./intro.php">HOME</a><br />';
                        if($soggiornoAttivo == "True"){
                            echo '
                            <a class="item" href="./homeRistorante.php">SERVIZIO DI RISTORAZIONE</a>
                            <br /> 
                          
                            <a class="item" href="./attivita.php">ATTIVIT&agrave;</a>
                            <br />
                
                            <a class="item" href="./domande.php">DOMANDE</a>
                            <br />';
                        }
                        echo ' <a class="item" href="./datiPersonali.php">DATI PERSONALI</a><br />';
                        break;
                    case "Concierge":
                        echo '                         
                        <a class="item" href="./visualizzaMenu.php">MODIFICA MENU RISTORANTE</a>
                        <br />
            
                        <a class="item" href="./listaOrari.php">MODIFICA ORARI</a>
                        <br />
                        
                        <a class="item" href="./listaAttivita.php">MODIFICA ATTIVIT&agrave;</a>
                        <br /> 
                    
                        <a class="item" href="./prenotazioniClienti.php">VISUALIZZA PRENOTAZIONI CLIENTI</a>
                        <br />
            
                        <a class="item" href="#">DOMANDE</a>
                        <br />';
                        break;
                    case "Admin":
                        echo '            
                        <a class="item" href="./visualizzaMenu.php">MODIFICA MENU RISTORANTE</a>
                        <br />
            
                        <a class="item" href="./listaOrari.php">MODIFICA ORARI</a>
                        <br />
                        
                        <a class="item" href="./listaAttivita.php">MODIFICA ATTIVIT&agrave;</a>
                        <br /> 
                    
                        <a class="item" href="./prenotazioniClienti.php">VISUALIZZA PRENOTAZIONI CLIENTI</a>
                        <br />
            
                        <a class="item" href="#">DOMANDE</a>
                        <br />
        
                        <br />
                        <br />
                        <br />

                        <a class="item" href="./categorie.php">CATEGORIE</a>
                        <br />
                        
                        <a class="item" href="./pagamentiClienti.php">PAGAMENTI CLIENTI</a>
                        <br />

                        <a class="item" href="./visualizzaClienti.php">VISUALIZZA CLIENTI</a>
                        <br />';
                        break;
                }
            ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <br />
                <input type="submit" class="logoutButton" value="LOGOUT" name="LOGOUT" />
            </form>
            <br/>
  
            </div>
            
        </div>

    <div id="rightColumn">

        <?php 
            if($tipoLogin == "Cliente"){
                echo '<h1 class="mainTitle">AREA CLIENTE</h1>';
            }
            else{
                if($tipoLogin == "Concierge"){
                    echo '<h1 class="mainTitle">AREA CONCIERGE</h1>';
                }
                else{
                    echo '<h1 class="mainTitle">AREA ADMIN</h1>';
                }
            }
        ?>
        <img id="img" src="../img/hotel.jpg" alt="Immagine non trovata"/>
            
    </div>


    </body>

</html>
