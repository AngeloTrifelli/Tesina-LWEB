<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');
    require_once('funzioniDeletePHP.php');

    // FILE CHE CONTIENE TUTTE E SOLO LE FUNZIONI PHP PER MODIFICARE I FILE XML


function modificaDatiUtente ($codFiscUtente, $datoDaModificare , $valore){
    $xmlString = "";
    foreach(file("../XML/Clienti.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathClienti = new DOMXPath($doc);
    $cliente = $xpathClienti->query("/listaClienti/cliente[@codFisc = '$codFiscUtente']");   
    $cliente = $cliente->item(0);

    if($datoDaModificare != "codFisc" && $datoDaModificare != "username" && $datoDaModificare != "password"){
        $nodoDato = $cliente->getElementsByTagName($datoDaModificare)->item(0);
        $nodoDato->nodeValue = "";
        $nodoDato->appendChild($doc->createTextNode($valore));

        $doc->save("../XML/Clienti.xml");
        return "success";
    }
    else{
        if($datoDaModificare == "codFisc"){
            $duplicato = $xpathClienti->query("/listaClienti/cliente[@codFisc = '$valore']");
            if($duplicato->length == 1 && $valore != $codFiscUtente){
                return "insuccess";
            }
            else{
                $cliente->setAttribute('codFisc' , $valore);
                $doc->save("../XML/Clienti.xml");
                return "success";
            }
        }

        if($datoDaModificare == "username"){
            $duplicato = $xpathClienti->query("/listaClienti/cliente/credenziali[username='$valore']");
            if($duplicato->length == 1){
                return "insuccess";
            }
            else{
                $nodoDato = $cliente->getElementsByTagName($datoDaModificare)->item(0);
                $nodoDato->nodeValue = "";
                $nodoDato->appendChild($doc->createTextNode($valore));
                $doc->save("../XML/Clienti.xml");
                return "success";
            }
        }

        if($datoDaModificare == "password"){
            $oldPassword = $valore['oldPassword'];
            $test = $xpathClienti->query("/listaClienti/cliente/credenziali[password='$oldPassword']");
            if($test->length == 1){
                $nodoDato = $cliente->getElementsByTagName($datoDaModificare)->item(0);
                $nodoDato->nodeValue = "";
                $nodoDato->appendChild($doc->createTextNode($valore['newPassword']));
                $doc->save("../XML/Clienti.xml");
                return "success";
            }
            else{
                return "insuccess";
            }
        }
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

//Funzione che abilita o disabilita una categoria in base al parametro passato

function modificaStatoCategoria($nome,$nuovoStato){
    $xmlString = "";
    foreach(file("../XML/Categorie.xml") as $node){
        $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathCategorie = new DOMXPath($doc);

    $categoria = $xpathCategorie->query("/listaCategorie/categoria[nome='$nome']");
    $categoria = $categoria->item(0);

    $statoCategoria = $categoria->getElementsByTagName("stato")->item(0);    
    
    $statoCategoria->nodeValue = "";
    $statoCategoria->appendChild($doc->createTextNode($nuovoStato));

    $doc->save("../XML/Categorie.xml");
}

//Funzione che modifica gli orari di apertura e di chiusura del ristorante

function modificaOrariRistorante($nuovaOraAperturaPranzo,$nuovaOraChiusuraPranzo,$nuovaOraAperturaCena,$nuovaOraChiusuraCena){

    $xmlString = "";
    foreach(file("../XML/Ristorante.xml") as $node){
        $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true; 

    $listaRistoranti = $doc->documentElement->childNodes;

    $ristorante = $listaRistoranti->item(0);

    $oraAperturaPranzo = $ristorante->getElementsByTagName("oraAperturaPranzo")->item(0);    
    $oraAperturaPranzo->nodeValue = "";
    $oraAperturaPranzo->appendChild($doc->createTextNode($nuovaOraAperturaPranzo.":00"));

    $oraChiusuraPranzo = $ristorante->getElementsByTagName("oraChiusuraPranzo")->item(0);    
    $oraChiusuraPranzo->nodeValue = "";
    $oraChiusuraPranzo->appendChild($doc->createTextNode($nuovaOraChiusuraPranzo.":00"));

    $oraAperturaCena = $ristorante->getElementsByTagName("oraAperturaCena")->item(0);    
    $oraAperturaCena->nodeValue = "";
    $oraAperturaCena->appendChild($doc->createTextNode($nuovaOraAperturaCena.":00"));

    $oraChiusuraCena = $ristorante->getElementsByTagName("oraChiusuraCena")->item(0);    
    $oraChiusuraCena->nodeValue = "";
    $oraChiusuraCena->appendChild($doc->createTextNode($nuovaOraChiusuraCena.":00"));

    $doc->save("../XML/Ristorante.xml");

}

function modificaOrariAttivita($idAttivita,$nuovaOraAperturaAttivita,$nuovaOraChiusuraAttivita){

    $xmlString = "";
    foreach(file("../XML/Attivita.xml") as $node){
        $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathAttivita = new DOMXPath($doc);

    $oraApertura = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/oraApertura");
    $oraApertura = $oraApertura->item(0);
    $oraApertura->nodeValue = "";
    $oraApertura->appendChild($doc->createTextNode($nuovaOraAperturaAttivita.":00"));

    $oraChiusura = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/oraChiusura");
    $oraChiusura = $oraChiusura->item(0);
    $oraChiusura->nodeValue = "";
    $oraChiusura->appendChild($doc->createTextNode($nuovaOraChiusuraAttivita.":00"));

    $doc->save("../XML/Attivita.xml");

}

// Funzione per modificare la data e/o l'ora di una prenotazione di un servizio in camera

function modificaDataOraPrenotazioneSC($idPrenotazione , $nuovaData , $nuovaOra){
    $xmlString = "";
    
    foreach(file("../XML/ServizioCamera.xml") as $node){
        $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathServizioCamera = new DOMXPath($doc);

    $prenotazione = $xpathServizioCamera->query("/listaPrenotazioni/prenotazione[@id='$idPrenotazione']");
    $prenotazione = $prenotazione->item(0);

    if($nuovaData != ""){
        $nodoData = $prenotazione->getElementsByTagName("data")->item(0);
        $nodoData->nodeValue = "";
        $nodoData->appendChild($doc->createTextNode($nuovaData));
    }

    if($nuovaOra != ""){
        $nodoOra = $prenotazione->getElementsByTagName("ora")->item(0);
        $nodoOra->nodeValue = "";
        $nodoOra->appendChild($doc->createTextNode($nuovaOra.":00"));
    }

    $doc->save("../XML/ServizioCamera.xml");
}



// Funzione per modificare i dati di una prenotazione al tavolo

function modificaPrenotazioneTavolo($idPrenotazione , $nuovaData , $nuovaLocazione , $nuovoPasto ,  $nuovoOrario){
    $xmlStringTavoli = "";
    foreach(file("../XML/Tavoli.xml") as $node){
        $xmlStringTavoli .=trim($node);
    }
    $docTavoli = new DOMDocument();
    $docTavoli->loadXML($xmlStringTavoli);
    $docTavoli->formatOutput = true;

    $xpathTavoli = new DOMXPath($docTavoli);

    $prenotazione = getPrenotazioneTavolo($idPrenotazione);
    
    if($prenotazione['locazioneTavolo'] == $nuovaLocazione){
        $result = verificaDisponibilitaTavolo($prenotazione['numeroTavolo'] , $idPrenotazione ,$nuovaData , $nuovoPasto);
        if($result == "True"){
            $numeroTavolo = $prenotazione['numeroTavolo'];
            $nodoPrenotazione = $xpathTavoli->query("/listaTavoli/tavolo[@numero='$numeroTavolo']/listaPrenotazioni/prenotazione[idPrenotazione='$idPrenotazione']");
            $nodoPrenotazione = $nodoPrenotazione->item(0);

            $vecchiaData = $nodoPrenotazione->getElementsByTagName("data")->item(0);
            $vecchiaData->nodeValue ="";
            $vecchiaData->appendChild($docTavoli->createTextNode($nuovaData));

            $vecchioOrario = $nodoPrenotazione->getElementsByTagName("ora")->item(0);
            $vecchioOrario->nodeValue="";
            $vecchioOrario->appendChild($docTavoli->createTextNode($nuovoOrario));

            $docTavoli->save("../XML/Tavoli.xml");
            return "success";
        }
        else{
            $result = cercaTavoloDisponibile($nuovaData , $nuovaLocazione , $nuovoPasto , $nuovoOrario , $prenotazione['codFiscCliente']);
            if($result == "success"){
                rimuoviPrenotazioneTavolo($idPrenotazione);
                return $result;
            }
            else{
                return "insuccess";
            }
        }
    }
    else{
        $result = cercaTavoloDisponibile($nuovaData , $nuovaLocazione , $nuovoPasto , $nuovoOrario , $prenotazione['codFiscCliente']);
        if($result == "success"){
            rimuoviPrenotazioneTavolo($idPrenotazione);
            return $result;
        }
        else{
            return "insuccess";
        }
    }
}




?>
