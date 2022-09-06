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
        <form>
            <div class="containerDati">
                <div class="datiUtenteSx">
                    <div>
                        <strong>Nome:</strong> Angelo <input type="submit" class="modificaButton" value="Modifica" />
                    </div>
                    <div>
                        <strong>Cognome:</strong> Trifelli <input type="submit" class="modificaButton" value="Modifica" />
                    </div>
                    <div>
                        <strong>Codice fiscale:</strong> TRFNGL00T25A341U <input type="submit" class="modificaButton" value="Modifica" />
                    </div>
                    <div>
                        <strong>Data di nascita:</strong> 25-12-2000 <input type="submit" class="modificaButton" value="Modifica" />
                    </div>
                    <div>
                        <strong>Indirizzo:</strong> via Francesco Cilea 7 <input type="submit" class="modificaButton" value="Modifica" />
                    </div>
                </div>
                <div class="datiUtenteDx">
                    <div class="data">
                        <strong>Telefono:</strong> 3490750745 <input type="image" class="immagine" alt="modifica"  src="../Immagini/edit.png"/>
                    </div>
                    <div class="data">
                        <strong>Email:</strong> trifelli.angelo@outlook.it <input type="image" class="immagine" alt="modifica"  src="../Immagini/edit.png"/>
                    </div>
                    <div>
                        <strong>Numero carta:</strong> 0000-0000-0000-0000 <input type="image" class="immagine" alt="modifica"  src="../Immagini/edit.png"/>
                    </div>
                    <div class="data">
                        <strong>Username:</strong> AngeloTrifelli<input type="image" class="immagine" alt="modifica"  src="../Immagini/edit.png"/>
                    </div>
                    <div class="data">
                        <strong>Password:</strong> FioreDiZucca <input type="image" class="immagine" alt="modifica"  src="../Immagini/edit.png"/>
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
