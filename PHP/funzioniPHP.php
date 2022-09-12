<?php
    require_once('funzioniModificaPHP.php');


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




















?>