<?php

    // FILE CHE CONTIENE TUTTE E SOLO LE FUNZIONI PHP PER MODIFICARE I FILE XML

function modificaDatiUtente ($datoDaModificare , $arrayValore){
    if($datoDaModificare != "codFisc" && $datoDaModificare != "username" && $datoDaModificare != "password"){
        // DA IMPLEMENTARE
    }
    else{
        // DA IMPLEMENTARE (controlli per codFisc , username e password vecchia)
    }
}



// Funzione per assegnare i crediti giornalieri ad un utente quando fa il login in login.php

function modificaDataCreditiGiornalieri($codFisc){
    $xmlString = "";
    foreach(file("../XML/Clienti.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;         //Con questa impostazione il file XML modificato non verrà salvato in un'unica riga

    $xpathClienti = new DOMXPath($doc);


    $utente = $xpathClienti->query("/listaClienti/cliente[@codFisc = '$codFisc']");
    $utente = $utente->item(0);

    $dataAssegnazioneCrediti = $utente->getElementsByTagName("dataAssegnazioneCrediti")->item(0);
    $sommaGiudizi = $utente->getElementsByTagName("sommaGiudizi")->item(0)->textContent;
    $creditiUtente = $utente->getElementsByTagName("crediti")->item(0);

    $testoDataAssegnazioneCrediti = $dataAssegnazioneCrediti->textContent;
    $testoCreditiUtente = $creditiUtente->textContent;

    $temp1 = new DateTime($testoDataAssegnazioneCrediti);
    $temp2 = new DateTime(date('Y-m-d'));

    $difference = $temp1->diff($temp2)->format("%a");
    
    $creditiDaAssegnare = $difference * $sommaGiudizi;
    $creditiTotali = $testoCreditiUtente + $creditiDaAssegnare;

    $creditiUtente->nodeValue= "";
    $creditiUtente->appendChild($doc->createTextNode($creditiTotali));    

    $dataAssegnazioneCrediti->nodeValue= "";
    $dataAssegnazioneCrediti->appendChild($doc->createTextNode(date('Y-m-d')));

    $doc->save("../XML/Clienti.xml");
}                




// Funzione per modificare lo stato di un soggiorno di un cliente

function modificaStatoSoggiorno ($idPrenotazione , $nuovoStato){
    $xmlString = "";
    foreach(file("../XML/Camere.xml") as $node){
        $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathCamere = new DOMXPath($doc);

    $prenotazione = $xpathCamere->query("/listaCamere/Camera/listaPrenotazioni/prenotazione[idPrenotazione='$idPrenotazione']");
    $prenotazione = $prenotazione->item(0);

    $statoSoggiorno = $prenotazione->getElementsByTagName("statoSoggiorno")->item(0);    
    
    $statoSoggiorno->nodeValue = "";
    $statoSoggiorno->appendChild($doc->createTextNode($nuovoStato));

    $doc->save("../XML/Camere.xml");

    if($nuovoStato == "Terminato"){
        $dataInizio = $prenotazione->getElementsByTagName("dataArrivo")->item(0)->textContent;
        $dataFine = $prenotazione->getElementsByTagName("dataPartenza")->item(0)->textContent;
        $codFiscCliente = $prenotazione->getElementsByTagName("codFisc")->item(0)->textContent;

        $temp1 = new DateTime($dataInizio);
        $temp2 = new DateTime($dataFine);

        $difference = $temp1->diff($temp2)->format("%a");
        $creditiDaAssegnare = $difference * 25;

        modificaCreditiCliente($codFiscCliente , $creditiDaAssegnare);
    }
    else{
        if($nuovoStato == "Pagamento rifiutato"){
            $creditiUsati = $prenotazione->getElementsByTagName("creditiUsati")->item(0)->textContent;
            $codFiscCliente = $prenotazione->getElementsByTagName("codFisc")->item(0)->textContent;

            modificaCreditiCliente($codFiscCliente , (int)$creditiUsati);
        }
    }
}





//Funzione per modificare i crediti di un cliente 
//Se creditiDaSommare è un numero negativo allora significa che si vogliono togliere dei crediti (questo accade quando il cliente fa un pagamento con crediti)

function modificaCreditiCliente($codFiscCliente , $creditiDaSommare){
    $xmlString = "";
    foreach(file("../XML/Clienti.xml") as $node){
        $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathClienti = new DOMXPath($doc);

    $creditiCliente = $xpathClienti->query("/listaClienti/cliente[@codFisc = '$codFiscCliente']/crediti");
    $creditiCliente = $creditiCliente->item(0);

    $testoCreditiCliente = $creditiCliente->textContent;

    $nuoviCrediti = $testoCreditiCliente + $creditiDaSommare;
    
    $creditiCliente->nodeValue = "";
    $creditiCliente->appendChild($doc->createTextNode($nuoviCrediti));

    $doc->save('../XML/Clienti.xml');
}



?>
