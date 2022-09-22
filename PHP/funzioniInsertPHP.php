<?php
    require_once("funzioniModificaPHP.php");

// FILE CHE CONTIENE TUTTE E SOLO LE FUNZIONI PER INSERIRE DATI NEI FILE XML


//Funzione che crea un nuovo utente all'interno del file Clienti.xml
function nuovoCliente(){
    session_start();

    $xmlStringClienti = "";

    foreach(file("../XML/Clienti.xml") as $node){

        $xmlStringClienti .= trim($node);

    }
            
            $docClienti = new DOMDocument();
            $docClienti->loadXML($xmlStringClienti);
            $docClienti->formatOutput = true;

                $anno = mb_substr($_SESSION['dataNascita'], 0 , 4);
                $mese = mb_substr($_SESSION['dataNascita'], 5 , 2);
                $giorno = mb_substr($_SESSION['dataNascita'], 8 , 2);

                $currentDate= date('Y-m-d');

                $nuovoCliente = $docClienti->createElement("cliente");
                $nuovoCliente->setAttribute("codFisc", $_SESSION['codFisc']);
                $listaClienti = $docClienti->documentElement;
                $listaClienti->appendChild($nuovoCliente);

                $nuovoNome = $docClienti->createElement("nome", $_SESSION['nome']);
                $nuovoCliente->appendChild($nuovoNome);

                $nuovoCognome = $docClienti->createElement("cognome", $_SESSION['cognome']);
                $nuovoCliente->appendChild($nuovoCognome);

                $nuovaDataNascita = $docClienti->createElement("dataDiNascita", $anno."-".$mese."-".$giorno);
                $nuovoCliente->appendChild($nuovaDataNascita);

                $nuovoIndirizzo = $docClienti->createElement("indirizzo", $_SESSION['indirizzo']);
                $nuovoCliente->appendChild($nuovoIndirizzo);

                $nuovoTelefono = $docClienti->createElement("telefono", $_SESSION['telefono']);
                $nuovoCliente->appendChild($nuovoTelefono);

                $nuovaEmail = $docClienti->createElement("email", $_SESSION['email']);
                $nuovoCliente->appendChild($nuovaEmail);

                $nuovoNumCarta = $docClienti->createElement("numeroCarta", $_SESSION['numeroCarta']);
                $nuovoCliente->appendChild($nuovoNumCarta);

                $nuoveCredenziali = $docClienti->createElement("credenziali");
                $nuovoCliente->appendChild($nuoveCredenziali);

                $nuovoUsername = $docClienti->createElement("username", $_POST['username']);
                $nuoveCredenziali->appendChild($nuovoUsername);

                $nuovaPassword = $docClienti->createElement("password", md5($_POST['password']));
                $nuoveCredenziali->appendChild($nuovaPassword);

                $nuoviCrediti = $docClienti->createElement("crediti", 0);
                $nuovoCliente->appendChild($nuoviCrediti);

                $nuovaDataAssegnazioneCrediti = $docClienti->createElement("dataAssegnazioneCrediti", $currentDate);
                $nuovoCliente->appendChild($nuovaDataAssegnazioneCrediti);

                $nuovaSommaGiudizi= $docClienti->createElement("sommaGiudizi", 0);
                $nuovoCliente->appendChild($nuovaSommaGiudizi);

                $docClienti->save("../XML/Clienti.xml");

}


//Funzione per inserire una nuova prenotazione di una camera 

function inserisciPrenotazioneCamera($idCamera ,$codFiscCliente , $creditiUsati, $dataArrivo , $dataPartenza){
    $xmlString = "";
    foreach(file("../XML/Camere.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathCamere = new DOMXPath($doc);

    $camera = $xpathCamere->query("/listaCamere/Camera[@numero='$idCamera']");
    $camera = $camera->item(0);

    $listaPrenotazioni = $camera->getElementsByTagName("listaPrenotazioni")->item(0);
    $ultimaPrenotazione = $listaPrenotazioni->lastChild;
    if(is_null($ultimaPrenotazione)){
        $idNuovaPrenotazione = $idCamera."-PC1";
    }
    else{
        $idUltimaPrenotazione = $ultimaPrenotazione->getElementsByTagName('idPrenotazione')->item(0)->textContent;

        $pieces = explode("-",$idUltimaPrenotazione);

        $nuovoNumero = substr($pieces[1] , 2) + 1;
        $idNuovaPrenotazione = $idCamera."-PC".$nuovoNumero;    
    }
    

    $nuovaPrenotazione = $doc->createElement("prenotazione");
    $listaPrenotazioni->appendChild($nuovaPrenotazione);

    $nuovoID = $doc->createElement("idPrenotazione" , $idNuovaPrenotazione);
    $nuovaPrenotazione->appendChild($nuovoID);

    $nuovoCodFisc = $doc->createElement("codFisc" , $codFiscCliente);
    $nuovaPrenotazione->appendChild($nuovoCodFisc);

    $nuovoStato = $doc->createElement("statoSoggiorno" , "Pagamento sospeso");
    $nuovaPrenotazione->appendChild($nuovoStato);

    $nuoviCrediti = $doc->createElement("creditiUsati" , $creditiUsati);
    $nuovaPrenotazione->appendChild($nuoviCrediti);

    $nuovaDataArrivo = $doc->createElement("dataArrivo" , $dataArrivo);
    $nuovaPrenotazione->appendChild($nuovaDataArrivo);

    $nuovaDataPartenza = $doc->createElement("dataPartenza" , $dataPartenza);
    $nuovaPrenotazione->appendChild($nuovaDataPartenza);


    modificaCreditiCliente($codFiscCliente, -$creditiUsati);    

    $doc->save("../XML/Camere.xml");
}

//Funzione  per aggiungere una prenotazione alle attività

function aggiungiPrenotazioneAttivita($idAttivita,$codFisc,$data,$oraInizio,$oraFine,$prezzoTotale,$creditiInseriti){


    $xmlStringAttivita= "";

    foreach(file("../XML/Attivita.xml") as $node){

        $xmlStringAttivita .= trim($node);

    }

    $docAttivita = new DOMDocument();
    $docAttivita->loadXML($xmlStringAttivita);
    $docAttivita->formatOutput = true;

    $xpathAttivita = new DOMXPath($docAttivita);

    $attivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']");
    $attivita = $attivita->item(0);

    $listaPrenotazioni=$attivita->getElementsByTagName("listaPrenotazioni")->item(0);
    $prenotazioni=$attivita->getElementsByTagName("prenotazione");
    $numPrenotazioni=count($prenotazioni);
    

    $nuovaPrenotazione = $docAttivita->createElement("prenotazione");
    $listaPrenotazioni->appendChild($nuovaPrenotazione);

    $nuovoIdPrenotazione = $docAttivita->createElement("idPrenotazione", $idAttivita."-PA".($numPrenotazioni+1));
    $nuovaPrenotazione->appendChild($nuovoIdPrenotazione);

    $nuovoCodFisc= $docAttivita->createElement("codFisc", $codFisc);
    $nuovaPrenotazione->appendChild($nuovoCodFisc);

    $nuovaData= $docAttivita->createElement("data", $data);
    $nuovaPrenotazione->appendChild($nuovaData);

    $nuovaOraInizio= $docAttivita->createElement("oraInizio", $oraInizio.":00");
    $nuovaPrenotazione->appendChild($nuovaOraInizio);

    $nuovaOraFine= $docAttivita->createElement("oraFine", $oraFine.":00");
    $nuovaPrenotazione->appendChild($nuovaOraFine);

    $nuovoPrezzoTotale= $docAttivita->createElement("prezzoTotale", $prezzoTotale);
    $nuovaPrenotazione->appendChild($nuovoPrezzoTotale);

    modificaCreditiCliente($codFisc , -$creditiInseriti);
    $nuovoCreditiUsati= $docAttivita->createElement("creditiUsati", $creditiInseriti);
    $nuovaPrenotazione->appendChild($nuovoCreditiUsati);

    $docAttivita->save("../XML/Attivita.xml");

}











?>