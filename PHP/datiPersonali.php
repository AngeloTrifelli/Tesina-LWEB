<?php
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
            <a href="test">TORNA INDIETRO</a>    
        </div>
        <h1 class="alignCenter">Ciao Angelo !</h1>
        <div style="width: 18.5%;"></div>
    </div>

    
    <h3 class="titoloImportante alignCenter">I TUOI DATI:</h3>
    <div class="mainContainer marginBottom">
        <form action="modificaDatiUtente.php"  method="post">
            <div class="containerDati">
                <div class="datiUtenteSx">
                    <div>
                        <strong>Nome:</strong> Angelo 
                        <button class="button" type="submit" name="nome" value="nome">
                            <img class="immagine" src="../Immagini/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Cognome:</strong> Trifelli
                        <button class="button" type="submit" name="cognome" value="cognome">
                            <img class="immagine" src="../Immagini/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Codice fiscale:</strong> TRFNGL00T25A341U 
                        <button class="button" type="submit" name="codFisc" value="codFisc">
                            <img class="immagine" src="../Immagini/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Data di nascita:</strong> 25-12-2000
                        <button class="button" type="submit" name="dataNascita" value="dataNascita">
                            <img class="immagine" src="../Immagini/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Indirizzo:</strong> via Francesco Cilea 7 
                        <button class="button" type="submit" name="indirizzo" value="indirizzo">
                            <img class="immagine" src="../Immagini/edit.png" />
                        </button>
                    </div>
                </div>
                <div class="datiUtenteDx">
                    <div>
                        <strong>Telefono:</strong> 3490750745
                        <button class="button" type="submit" name="telefono" value="telefono">
                            <img class="immagine" src="../Immagini/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Email:</strong> trifelli.angelo@outlook.it
                        <button class="button" type="submit" name="email" value="email">
                            <img class="immagine" src="../Immagini/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Numero carta:</strong> 0000-0000-0000-0000
                        <button class="button" type="submit" name="numeroCarta" value="numeroCarta">
                            <img class="immagine" src="../Immagini/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Username:</strong> AngeloTrifelli
                        <button class="button" type="submit" name="username" value="username">
                            <img class="immagine" src="../Immagini/edit.png" />
                        </button>
                    </div>
                    <div>
                        <strong>Password:</strong> **********
                        <button class="button" type="submit" name="password" value="password">
                            <img class="immagine" src="../Immagini/edit.png" />
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="datiUtenteCentro">
            <strong>Crediti: 0</strong>
            <strong>Somma giudizi ricevuti: 0</strong>
        </div>
    </div>
   

    <h3 class="titoloImportante alignCenter">IL TUO SOGGIORNO:</h3>
    <div class="mainContainer">
        <table class="prenotazione alignCenter" align="center">
            <tr>
                <td>
                    <strong>Numero Camera</strong><br />
                    110
                </td>
                <td>
                    <strong>Tipo</strong><br />
                    Standard Doppia
                </td>
                <td>
                    <strong>Stato soggiorno</strong><br />
                    <span class="success">Approvato</span>
                </td>
                <td>
                    <strong>Inizio</strong><br />
                    28-10-2022
                </td>
                <td>
                    <strong>Fine</strong><br />
                    31-10-2022
                </td>
            </tr>
        </table>
    </div>
    <!-- <p class="alignCenter scrittaCentrale">Non è stata trovata una prenotazione attiva...</p> -->
    <!-- Con il php poi controlla quando mostrare questa scritta o meno -->  

    <h3 class="titoloImportante alignCenter">SOGGIORNI PASSATI:</h3>
    <div class="mainContainer">
        <table class="prenotazione alignCenter" align="center">
            <tr>
                <td>
                    <strong>Numero Camera</strong><br />
                    110
                </td>
                <td>
                    <strong>Tipo</strong><br />
                    Standard Doppia
                </td>
                <td>
                    <strong>Stato soggiorno</strong><br />
                    <span class="insuccess">Pagamento rifiutato</span>
                </td>
                <td>
                    <strong>Inizio</strong><br />
                    28-10-2022
                </td>
                <td>
                    <strong>Fine</strong><br />
                    31-10-2022
                </td>
            </tr>
        </table>
        <table class="prenotazione alignCenter" align="center">
            <tr>
                <td>
                    <strong>Numero Camera</strong><br />
                    200
                </td>
                <td>
                    <strong>Tipo</strong><br />
                    Standard Singola
                </td>
                <td>
                    <strong>Stato soggiorno</strong><br />
                    <span class="success">Terminato</span>
                </td>
                <td>
                    <strong>Inizio</strong><br />
                    13-06-2022
                </td>
                <td>
                    <strong>Fine</strong><br />
                    18-06-2022
                </td>
            </tr>
        </table>
    </div>      
    <!-- <p class="alignCenter scrittaCentrale marginBottom">Non sono stati trovati soggiorni passati...</p> -->
    <!-- come prima, con php controlli se mostrare questa scritta o meno -->
</body>


</html>
