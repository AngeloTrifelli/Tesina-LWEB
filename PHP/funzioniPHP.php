<?php
    require_once('funzioniModificaPHP.php');
    require_once('funzioniGetPHP.php');





// Funzione per controllare se le credenziali inserite dal concierge o dall'admin in login.php sono corrette

function eseguiLoginStaff($username , $password , $tipoLogin){
    $xmlString = "";
    if($tipoLogin == "Concierge"){
        foreach(file("../XML/Concierge.xml") as $node){
            $xmlString .= trim($node);
        }
    }
    else{
        foreach(file("../XML/Amministratori.xml") as $node){
            $xmlString .= trim($node);
        }
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    
    $listaStaff = $doc->documentElement->childNodes;
    $trovato = "False";
    $i = 0;
    while ($i < $listaStaff->length && $trovato == "False"){
        $staff = $listaStaff->item($i);
        $testoUsername = $staff->firstChild->textContent;
        $testoPassword = $staff->lastChild->textContent;
        if($testoUsername == $username && $testoPassword == $password){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }
    return $trovato;
}


// Funzione per controllare se le credenziali inserite dall'utente sono corrette in login.php (login cliente)

function eseguiLoginCliente ($username , $password){
    $xmlString = "";
    foreach(file("../XML/Clienti.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $listaClienti = $doc->documentElement->childNodes;
    $trovato = "False";
    $i = 0;

    while($i < $listaClienti->length && $trovato == "False"){
        $cliente = $listaClienti->item($i);
        $elemCredenziali = $cliente->getElementsByTagName("credenziali")->item(0);

        $testoUsername = $elemCredenziali->firstChild->textContent;
        $testoPassword = $elemCredenziali->lastChild->textContent;
        
        if($testoUsername == $username && $testoPassword == $password){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }

    if($trovato == "True"){
        $codFisc = $cliente->getAttribute("codFisc");
        $date = date('Y-m-d');
        $dataAssegnazioneCrediti = $cliente->getElementsByTagName("dataAssegnazioneCrediti")->item(0)->textContent;
        if($date != $dataAssegnazioneCrediti){
            modificaDataCreditiGiornalieri($codFisc);
        }
        return $codFisc;
    }
    else{
        return "null";          //Questo significa che l'username e la password inserita sono errate (non corrispondono a nessun utente registrato)
    }
}




// Funzione per capire che dato si vuole modificare in modificaDatiUtente.php

function individuaDatoDaModificare(){
    if(isset($_POST['nome']) || isset($_POST['cognome']) || isset($_POST['codFisc']) || isset($_POST['dataDiNascita']) || isset($_POST['indirizzo']) || isset($_POST['telefono']) || isset($_POST['email']) || isset($_POST['numeroCarta']) || isset($_POST['username']) || isset($_POST['password'])){
        if(!isset($_POST['nome'])){
            if(!isset($_POST['cognome'])){
                if(!isset($_POST['codFisc'])){
                    if(!isset($_POST['dataDiNascita'])){
                        if(!isset($_POST['indirizzo'])){
                            if(!isset($_POST['telefono'])){
                                if(!isset($_POST['email'])){
                                    if(!isset($_POST['numeroCarta'])){
                                        if(!isset($_POST['username'])){
                                            return "password";
                                        }
                                        return "username";
                                    }
                                    return "numeroCarta";
                                }
                                return "email";
                            }
                            return "telefono";
                        }
                        return "indirizzo";
                    }
                    return "dataDiNascita";
                }
                return "codFisc";
            }
            return "cognome";
        }
        return "nome";
    }
    else{
        return "null";       //Questo significa che ho aperto la pagina modificaDatiUtente.php senza esser passato prima per datiPersonali.php
    }
}


// Funzione per capire che bottone ha premuto l'utente in visualizzaDisponibilita.php

function individuaBottoneCamereDisponibili(){
    $listaIdCamere = getIdCamere();

    $i = 0;
    $trovato = "False";

    while($i < $listaIdCamere->length && $trovato == "False"){
        $idCamera = $listaIdCamere->item($i)->textContent;
        if(isset($_POST[$idCamera])){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }

    return $idCamera;
}


// Funzione per capire se la prenotazione dell'attività si può effettuare perchè disponibile

// function checkPrenotazione($idAttivita,$dataAttivita,$oraInizio,$oraFine){
//     $oraInizio=explode(':', $oraInizio);
//     $oraFine=explode(':', $oraFine);



//     $temp = $_SESSION['soggiornoAttivo'];

//     $xmlStringAttivita= "";

//     foreach(file("../XML/Attivita.xml") as $node){
//         $xmlStringAttivita .= trim($node);

//     }

//     $docAttivita = new DOMDocument();
//     $docAttivita->loadXML($xmlStringAttivita);

//     $xpathAttivita = new DOMXPath($docAttivita);

//     $attivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']");
//     $attivita = $attivita->item(0);
//     $oraApertura=$attivita->getElementsByTagName("oraApertura")->item(0)->textContent;
//     $oraInizioMinima=explode(':', $oraApertura);
//     $oraChiusura=$attivita->getElementsByTagName("oraChiusura")->item(0)->textContent;
//     $oraFineMassima=explode(':', $oraChiusura);
    
//     if(($oraInizioMinima[0]<$oraInizio[0])&&($oraFineMassima[0]>$oraFine[0])&&($temp['dataArrivo']<=$dataAttivita)&&($temp['dataPartenza']>=$dataAttivita)){
//         return "True";
//     }else{
//         return "False";
//     }

// }

function individuaBottoneidAttivita(){

    $listaIdAttivita = getIdAttivita();

    $i = 0;
    $trovato = "False";

    while($i < $listaIdAttivita->length && $trovato == "False"){
        $idAttivita = $listaIdAttivita->item($i)->textContent;
        if(isset($_POST[$idAttivita])){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }

    return $idAttivita;
}


















?>

