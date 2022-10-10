<?php
    require_once('funzioniGetPHP.php');

    session_start();
    if(isset($_SESSION['loginType'])){
        if(isset($_SESSION['codFiscUtenteLoggato'])){
            $cliente = getDatiCliente($_SESSION['codFiscUtenteLoggato']);
            $soggiorniPassati = getSoggiorniPassati($_SESSION['codFiscUtenteLoggato']);
        }
        else{
            if(!isset($_SESSION['codFiscUtenteDaModificare'])){
                header('Location: areaUtente.php');
                exit();
            }else{
            $cliente = getDatiCliente($_SESSION['codFiscUtenteDaModificare']);
            }
        }
    }
    else{
        header('Location: intro.php');
        exit();
    }    

    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Dati personali</title>

    <style>
        <?php include "../CSS/datiPersonali.css" ?>
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
</head>


<body>
    <div class="top">
        <div class="topLeft">
            <?php
                if(isset($_SESSION['codFiscUtenteLoggato'])){
                    echo "<a href=\"./areaUtente.php\">TORNA NELL'AREA UTENTE</a> ";
                }
                else{
                    echo "<a href=\"./visualizzaClienti.php\">TORNA ALLA LISTA CLIENTI</a> ";
                }
            ?>   
        </div>
        <h1 style="display:inline ;">Ciao <?php echo $cliente['nome'];?>!</h1>        
    </div>

    
    <h3 class="titoloImportante alignCenter">I TUOI DATI:</h3>
    <div class="mainContainer marginBottom">
        <form action="modificaDatiUtente.php"  method="post">
            <div class="containerDati">
                <div class="datiUtenteSx">
                    <div>
                        <strong>Nome:</strong> <?php echo $cliente['nome'];?>
                        <button class="button" type="submit" name="nome" value="nome">
                            <img class="immagine" src="../img/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Cognome:</strong> <?php echo $cliente['cognome'];?>
                        <button class="button" type="submit" name="cognome" value="cognome">
                            <img class="immagine" src="../img/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Codice fiscale:</strong> <?php echo $cliente['codFisc'];?>
                        <button class="button" type="submit" name="codFisc" value="codFisc">
                            <img class="immagine" src="../img/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Data di nascita:</strong> <?php echo $cliente['dataDiNascita'];?>
                        <button class="button" type="submit" name="dataDiNascita" value="dataDiNascita">
                            <img class="immagine" src="../img/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Indirizzo:</strong> <?php echo $cliente['indirizzo'];?>
                        <button class="button" type="submit" name="indirizzo" value="indirizzo">
                            <img class="immagine" src="../img/edit.png" />
                        </button>
                    </div>
                </div>
                <div class="datiUtenteDx">
                    <div>
                        <strong>Telefono:</strong> <?php echo $cliente['telefono'];?>
                        <button class="button" type="submit" name="telefono" value="telefono">
                            <img class="immagine" src="../img/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Email:</strong> <?php echo $cliente['email'];?>
                        <button class="button" type="submit" name="email" value="email">
                            <img class="immagine" src="../img/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Numero carta:</strong> <?php echo $cliente['numeroCarta'];?>
                        <button class="button" type="submit" name="numeroCarta" value="numeroCarta">
                            <img class="immagine" src="../img/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Username:</strong> <?php echo $cliente['username'];?>
                        <button class="button" type="submit" name="username" value="username">
                            <img class="immagine" src="../img/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Password:</strong> **********
                        <button class="button" type="submit" name="password" value="password">
                            <img class="immagine" src="../img/edit.png" />
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="datiUtenteCentro">
            <strong>Crediti: <?php echo $cliente['crediti'];?> </strong>
            <strong>Somma giudizi ricevuti: <?php echo $cliente['sommaGiudizi'];?></strong>
        </div>
    </div>
   
    <?php
        if(isset($_SESSION['codFiscUtenteLoggato'])){  //Se sono un admin non mi interessa visualizzare i soggiorni
    ?>
    <h3 class="titoloImportante alignCenter">IL TUO SOGGIORNO:</h3>

    <?php 
        if($_SESSION['soggiornoAttivo'] != "null"){
            $temp = $_SESSION['soggiornoAttivo'];
    ?>
        <div class="mainContainer">
            <table class="prenotazione alignCenter" align="center">
                <tr>
                    <td>
                        <strong>Numero Camera</strong><br />
                        <?php echo $temp['numeroCamera'];?>
                    </td>
                    <td>
                        <strong>Tipo</strong><br />
                        <?php echo $temp['tipoCamera'];?>
                    </td>
                    <td>
                        <strong>Stato soggiorno</strong><br />
                        <?php
                            if($temp['statoSoggiorno'] == "Approvato"){
                        ?>   
                                <span class="success"><?php echo $temp['statoSoggiorno']?></span>
                        <?php        
                            }
                            else{
                        ?>
                                <span class="waiting"><?php echo $temp['statoSoggiorno']?></span>
                        <?php
                            }
                        ?>
                        
                    </td>
                    <td>
                        <strong>Inizio</strong><br />
                        <?php
                            $stringaData = $temp['dataArrivo'];
                            $giorno = substr($stringaData, 8,2);       
                            $mese = substr($stringaData,5,2 );
                            $anno = substr($stringaData,0,4 );
                            echo $giorno."-".$mese."-".$anno;
                        ?>
                    </td>
                    <td>
                        <strong>Fine</strong><br />
                        <?php
                            $stringaData = $temp['dataPartenza'];
                            $giorno = substr($stringaData, 8,2);       
                            $mese = substr($stringaData,5,2 );
                            $anno = substr($stringaData,0,4 );
                            echo $giorno."-".$mese."-".$anno;
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php
        }
        else{
            echo '<p class="alignCenter scrittaCentrale">Non è stata trovata una prenotazione attiva...</p>';
        }
    ?>


    <h3 class="titoloImportante alignCenter">SOGGIORNI PASSATI/RIFIUTATI:</h3>
    <?php
        $numSoggiorniPassati = count($soggiorniPassati);
        if($numSoggiorniPassati >= 1){
    ?>
    <div class="mainContainer">
    
    <?php
            for($i=0 ; $i < $numSoggiorniPassati ; $i++){
                $temp = $soggiorniPassati[$i];
    ?>
                <table class="prenotazione alignCenter" align="center">
                    <tr>
                        <td>
                            <strong>Numero Camera</strong><br />
                            <?php echo $temp['numeroCamera'];?>
                        </td>
                        <td>
                            <strong>Tipo</strong><br />
                            <?php echo $temp['tipoCamera'];?>
                        </td>
                        <td>
                            <strong>Stato soggiorno</strong><br />
                            <?php
                                if($temp['statoSoggiorno'] == "Terminato"){
                            ?>
                                <span class="success"><?php echo $temp['statoSoggiorno'];?></span>    
                            <?php
                                }
                                else{
                            ?>
                                <span class="insuccess"><?php echo $temp['statoSoggiorno'];?></span>
                            <?php                            
                                }
                            ?>
                        </td>
                        <td>
                            <strong>Inizio</strong><br />
                            <?php
                                $stringaData = $temp['dataArrivo'];
                                $giorno = substr($stringaData, 8,2);       
                                $mese = substr($stringaData,5,2 );
                                $anno = substr($stringaData,0,4 );
                                echo $giorno."-".$mese."-".$anno;
                            ?>
                        </td>
                        <td>
                            <strong>Fine</strong><br />
                            <?php
                                $stringaData = $temp['dataPartenza'];
                                $giorno = substr($stringaData, 8,2);       
                                $mese = substr($stringaData,5,2 );
                                $anno = substr($stringaData,0,4 );
                                echo $giorno."-".$mese."-".$anno;
                            ?>
                        </td>
                    </tr>
                </table>
    <?php
            }
    ?>
    </div>      
    <?php
        }
        else{
            echo '<p class="alignCenter scrittaCentrale marginBottom">Non sono stati trovati soggiorni passati o rifiutati...</p>';
        }
    ?>

<?php
    } 
?>
</body>


</html>
