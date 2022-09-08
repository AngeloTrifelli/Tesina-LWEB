<?php
    require_once('funzioniPHP.php');
    require_once('funzioniModificaPHP.php');

    $patternCodFisc = "/^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$/";
    $patternNumTelefono = "/^[0-9]{10}$/";
    $patternNumeroCarta = "/^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}$/";
    $result = "";

    if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA'])){
        if(isset($_POST['ANNULLA'])){
            header('Location: datiPersonali.php');
        }
        else{
            $datoDaModificare = $_POST['datoDaModificare'];
            if($datoDaModificare == "nome" || $datoDaModificare == "cognome" || $datoDaModificare == "indirizzo" ||  $datoDaModificare == "dataDiNascita" ||  $datoDaModificare == "username"){
                if($_POST['textInput']!=""){
                    $result = modificaDatiUtente($datoDaModificare , $_POST['textInput']);
                    if($result == "success"){    
                        header('Location: datiPersonali.php');
                    }
                }
            }
            else{
                if($datoDaModificare == "codFisc"){
                    if(preg_match($patternCodFisc , $_POST['nuovoCodFisc'])){
                        $result = modificaDatiUtente($datoDaModificare , $_POST['nuovoCodFisc']);
                        if($result == "success"){    
                            header('Location: datiPersonali.php');
                        }
                    }
                }
                else{
                    if($datoDaModificare == "telefono"){
                        if(preg_match($patternNumTelefono , $_POST['nuovoTelefono'])){
                            $result = modificaDatiUtente($datoDaModificare , $_POST['nuovoTelefono']);
                            header('Location: datiPersonali.php');   
                        }
                    }
                    else{
                        if($datoDaModificare == "email"){
                            if(filter_var($_POST['nuovaEmail'] , FILTER_VALIDATE_EMAIL)){
                                $result = modificaDatiUtente($datoDaModificare , $_POST['nuovaEmail']);
                                header('Location: datiPersonali.php');   
                            }
                        }
                        else{
                            if($datoDaModificare == "numeroCarta"){
                                if(preg_match($patternNumeroCarta , $_POST['nuovoNumeroCarta'])){
                                    $result = modificaDatiUtente($datoDaModificare , $_POST['nuovoNumeroCarta']);
                                    header('Location: datiPersonali.php');   
                                }
                            }
                            else{
                                if($datoDaModificare == "password"){
                                    if($_POST['oldPassword']!="" && $_POST['newPassword']!=""){
                                        $arrayPassword['oldPassword'] = $_POST['oldPassword'];
                                        $arrayPassword['newPassword'] = $_POST['newPassword'];
                                        $result = modificaDatiUtente($datoDaModificare , $arrayPassword);
                                        if($result == "success"){    
                                            header('Location: datiPersonali.php');
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }
    }
    else{
        $datoDaModificare = individuaDatoDaModificare();
        if($datoDaModificare == "null"){    //Se il controllo va a buon fine significa che non sono arrivato in questa pagina mediante datiPersonali.php
            //Controlla se l'utente è loggato o meno e fai l'header di conseguenza
        }
    }
?>




<?php
    echo '<?xml version="1.0" encoding="UTF-8?>'
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Modifica dati</title>

    <style>
        <?php include "../CSS/modificaDatiUtente.css" ?>
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&display=swap" />
</head>

<body>
    <div class="containerCentrale">
    <h1>MODIFICA DATI:</h1>
        <div class="zonaDati">
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                <div class="zonaInput">
                    <?php
                        switch($datoDaModificare){
                            case "nome":
                    ?>
                                <input type="text" class="textInput" placeholder="Inserisci il nome" name="textInput"/>
                    <?php   
                                if(isset($_POST['CONFERMA']) && $_POST['textInput'] == "" ){
                                    echo "<p class=\"errorLabel\">Dati mancanti!</p>";
                                }
                                break;
                            case "cognome":
                    ?>
                                <input type="text" class="textInput" placeholder="Inserisci il cognome" name="textInput" />    
                    <?php
                                if(isset($_POST['CONFERMA']) && $_POST['textInput'] == "" ){
                                    echo "<p class=\"errorLabel\">Dati mancanti!</p>";
                                }
                                break;
                            case "codFisc":
                                if(isset($_POST['nuovoCodFisc'])){
                                    echo "<input class=\"textInput\" type=\"text\" name=\"nuovoCodFisc\" value=\"{$_POST['nuovoCodFisc']}\" placeholder=\"Inserisci il codice fiscale \" maxlenght=\"16\"  />";
                                }
                                else{
                    ?>
                                    <input type="text" class="textInput" placeholder="Inserisci il codice fiscale" maxlength="16" name="nuovoCodFisc"/>    
                    <?php
                                }
                                if(isset($_POST['CONFERMA'])  && $_POST['nuovoCodFisc'] == ""){
                                    echo "<p class=\"errorLabel\">Dati mancanti!</p>";
                                }
                                else{
                                    if(isset($_POST['CONFERMA']) && (!preg_match($patternCodFisc, $_POST['nuovoCodFisc']))){
                                        echo "<p class=\"errorLabel\">Il codice fiscale inserito non è valido!</p>";
                                    }
                                    else{
                                        if(isset($_POST['CONFERMA']) && preg_match($patternCodFisc , $_POST['nuovoCodFisc']) && $result == "insuccess"){
                                            echo "<p class=\"errorLabel\">Il codice fiscale inserito è già associato ad un altro utente!</p>";
                                        }
                                    }
                                }
                                break;
                            case "dataDiNascita":
                    ?>
                                <p><strong>Inserisci la data di nascita:</strong></p>
                                <input type="date" class="dateInput" name="textInput"/>
                    <?php
                                if(isset($_POST['CONFERMA']) && $_POST['textInput'] == "" ){
                                    echo "<p class=\"errorLabel\">Dati mancanti!</p>";
                                }
                                break;
                            case "indirizzo":
                    ?>
                                <input type="text" class="textInput" placeholder="Inserisci l'indirizzo" name="textInput" />
                    <?php
                                if(isset($_POST['CONFERMA']) && $_POST['textInput'] == "" ){
                                    echo "<p class=\"errorLabel\">Dati mancanti!</p>";
                                }
                                break;
                            case "telefono":
                                if(isset($_POST['nuovoTelefono'])){
                                    echo "<input class=\"textInput\" type=\"text\" name=\"nuovoTelefono\" value=\"{$_POST['nuovoTelefono']}\" placeholder=\"Inserisci il telefono \" maxlength=\"10\"  />";
                                }
                                else{
                    ?>
                                <input type="text" class="textInput" placeholder="Inserisci il telefono" name="nuovoTelefono" maxlength="10" />
                    <?php
                                }
                    ?>
                                <p><small>Formato: 0123456789</small></p>
                    <?php
                                if(isset($_POST['CONFERMA'])  && $_POST['nuovoTelefono'] == ""){
                                    echo "<p class=\"errorLabel\">Dati mancanti!</p>";
                                }
                                else{
                                    if(isset($_POST['CONFERMA']) && (!preg_match($patternNumTelefono, $_POST['nuovoTelefono']))){
                                        echo "<p class=\"errorLabel\">Il numero di telefono inserito non è valido!</p>";
                                    }
                                }       
                                break;
                            case "email":
                    ?>
                                <input type="text" class="textInput" placeholder="Inserisci l'email" name="nuovaEmail" />
                    <?php
                                if(isset($_POST['CONFERMA'])  && $_POST['nuovaEmail'] == ""){
                                    echo "<p class=\"errorLabel\">Dati mancanti!</p>";
                                }
                                else{
                                    if(isset($_POST['CONFERMA']) && (!filter_var($_POST['nuovaEmail'], FILTER_VALIDATE_EMAIL))){
                                        echo "<p class=\"errorLabel\">L'email inserita non è valida!</p>";
                                    }
                                }    
                                break;
                            case "numeroCarta":
                                if(isset($_POST['nuovoNumeroCarta'])){
                                    echo "<input class=\"textInput\" type=\"text\" name=\"nuovoNumeroCarta\" value=\"{$_POST['nuovoNumeroCarta']}\" placeholder=\"Inserisci il numero della carta \" maxlength=\"19\" />";
                                }
                                else{
                    ?>
                                <input type="text" class="textInput" placeholder="Inserisci il numero della carta" name="nuovoNumeroCarta" maxlength="19" />
                    <?php
                                }
                    ?>
                                <p><small>Formato: XXXX-XXXX-XXXX-XXXX</small></p>
                    <?php
                                if(isset($_POST['CONFERMA'])  && $_POST['nuovoNumeroCarta'] == ""){
                                    echo "<p class=\"errorLabel\">Dati mancanti!</p>";
                                }
                                else{
                                    if(isset($_POST['CONFERMA']) && (!preg_match($patternNumeroCarta, $_POST['nuovoNumeroCarta']))){
                                        echo "<p class=\"errorLabel\">Il numero di carta di credito inserito non è valido!</p>";
                                    }
                                }   
                                break;
                            case "username":
                    ?>
                                <input type="text" class="textInput" placeholder="Inserisci l'username" name="textInput" />
                    <?php
                                if(isset($_POST['CONFERMA']) && $_POST['textInput'] == "" ){
                                    echo "<p class=\"errorLabel\">Dati mancanti!</p>";
                                }
                                else{
                                    if(isset($_POST['CONFERMA']) && $result == "insuccess"){
                                        echo "<p class=\"errorLabel\">L'username inserito è già in uso!</p>";
                                    }
                                }
                                break;
                            case "password":
                    ?>
                                <input type="text" class="textInput" placeholder="Inserisci la vecchia password" name="oldPassword" />
                                <input type="text" class="textInput" placeholder="Inserisci la nuova password" name="newPassword"/>
                    <?php
                                if(isset($_POST['CONFERMA']) && ($_POST['oldPassword'] == "" || $_POST['newPassword'] =="") ){
                                    echo "<p class=\"errorLabel\">Dati mancanti!</p>";
                                }
                                else{
                                    if(isset($_POST['CONFERMA']) && $_POST['oldPassword']!="" && $_POST['newPassword']!="" && $result == "insuccess"){
                                        echo "<p class=\"errorLabel\">La vecchia password inserita è errata!</p>";
                                    }
                                }
                                break;
                        }
                    ?>
                            
                </div>
                <div class="zonaBottoni">
                    <input type="submit" class="button" value="ANNULLA" name="ANNULLA" /> 
                    <input type="submit" class="button" value="CONFERMA" name="CONFERMA" />
                    <input type="hidden" name="datoDaModificare" value="<?php echo $datoDaModificare;?>" />   

                    <!-- Grazie a questo campo hidden se l'utente clicca conferma ma ha commesso degli errori nella compilazione del dato,
                    viene mantenuto il contenuto della variabile con il refresh della pagina-->
                </div>
            </form>
        </div>
    </div>




</body>
</html>
