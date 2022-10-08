<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');
    require_once('funzioniDeletePHP.php');

    // FILE CHE CONTIENE TUTTE E SOLO LE FUNZIONI PHP PER MODIFICARE I FILE XML
    

function modificaDatiUtente ($codFiscUtente, $datoDaModificare , $valore){
    if(!isset($_SESSION)){ 
        session_start(); 
    } 
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
                modificaCodiciFiscali($valore , $codFiscUtente);
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
            if($_SESSION['loginType'] == "Cliente"){
                $oldPassword = $valore['oldPassword'];
                $test = $xpathClienti->query("/listaClienti/cliente[@codFisc='$codFiscUtente']/credenziali[password='$oldPassword']");
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
            else{
                $nodoDato = $cliente->getElementsByTagName($datoDaModificare)->item(0);
                $nodoDato->nodeValue = "";
                $nodoDato->appendChild($doc->createTextNode($valore));
                $doc->save("../XML/Clienti.xml");
                return "success";
            }
        }
    }
}


function modificaCredenzialiStaff($vecchioUsername ,  $nuovoUsername , $nuovaPassword , $tipoStaff){
    $xmlString = "";
    if($tipoStaff == "Concierge"){
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
    $doc->formatOutput = true;

    $listaStaff = $doc->documentElement->childNodes;

    $i=0;
    $trovato = "False";

    while($i < $listaStaff->length && $trovato == "False"){
        $staff = $listaStaff->item($i);
        $username = $staff->firstChild;
        $password = $staff->lastChild;

        if($username->textContent == $vecchioUsername){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }

    if($nuovoUsername != ""){
        $username->nodeValue = "";
        $username->appendChild($doc->createTextNode($nuovoUsername));
    }

    if($nuovaPassword != ""){
        $password->nodeValue = "";
        $password->appendChild($doc->createTextNode($nuovaPassword));
    }

    if($tipoStaff == "Concierge"){
        $doc->save("../XML/Concierge.xml");
    }
    else{
        $doc->save("../XML/Amministratori.xml");
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




function modificaAttivita($idAttivita,$nome,$linkImmagine,$descrizione,$prezzoOrario){

    $xmlString = "";
    foreach(file("../XML/Attivita.xml") as $node){
        $xmlString .= trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathAttivita = new DOMXPath($doc);

    if($nome!=""){
        $nomeAttivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/nome");
        $nomeAttivita = $nomeAttivita->item(0);
        $nomeAttivita->nodeValue = "";
        $nomeAttivita->appendChild($doc->createTextNode($nome));
    }

    if($linkImmagine!=""){
        $linkImmagineAttivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/linkImmagine");
        $linkImmagineAttivita = $linkImmagineAttivita->item(0);
        $linkImmagineAttivita->nodeValue = "";
        $linkImmagineAttivita->appendChild($doc->createTextNode($linkImmagine));
    }

    if($descrizione!=""){
        $descrizioneAttivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/descrizione");
        $descrizioneAttivita = $descrizioneAttivita->item(0);
        $descrizioneAttivita->nodeValue = "";
        $descrizioneAttivita->appendChild($doc->createTextNode($descrizione));
    }

    if($prezzoOrario!=""){
        $prezzoOrarioAttivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/prezzoOrario");
        $prezzoOrarioAttivita = $prezzoOrarioAttivita->item(0);
        $prezzoOrarioAttivita->nodeValue = "";
        $prezzoOrarioAttivita->appendChild($doc->createTextNode($prezzoOrario));
    }

    $doc->save("../XML/Attivita.xml"); 
}

function modificaPrenotazioneAttivita($idPrenotazione,$data,$oraInizio,$oraFine){
    $xmlString = "";
    foreach(file("../XML/Attivita.xml") as $node){
        $xmlString .= trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathAttivita = new DOMXPath($doc);

    $idAttivita=substr($idPrenotazione,0,2);

    if($data!=""){
        $dataPrenotazioneAttivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/listaPrenotazioni/prenotazione[idPrenotazione = '$idPrenotazione']/data");
        $dataPrenotazioneAttivita = $dataPrenotazioneAttivita->item(0);
        $dataPrenotazioneAttivita->nodeValue = "";
        $dataPrenotazioneAttivita->appendChild($doc->createTextNode($data));
        }
    if($oraInizio!=""){
        $oraInizioPrenotazioneAttivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/listaPrenotazioni/prenotazione[idPrenotazione = '$idPrenotazione']/oraInizio");
        $oraInizioPrenotazioneAttivita = $oraInizioPrenotazioneAttivita->item(0);
        
        $oraInizioPrenotazioneAttivita->nodeValue = "";
        $oraInizioPrenotazioneAttivita->appendChild($doc->createTextNode($oraInizio.":00"));
        }
    if($oraFine!=""){
        $oraFinePrenotazioneAttivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/listaPrenotazioni/prenotazione[idPrenotazione = '$idPrenotazione']/oraFine");
        $oraFinePrenotazioneAttivita = $oraFinePrenotazioneAttivita->item(0);
        $oraFinePrenotazioneAttivita->nodeValue = "";
        $oraFinePrenotazioneAttivita->appendChild($doc->createTextNode($oraFine.":00"));
        }

    if($oraInizio!="" || $oraFine!=""){
        $prezzoOrario=$xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/prezzoOrario");
        $prezzoOrario=$prezzoOrario->item(0)->textContent;
        settype($prezzoOrario,"integer");
        $nuovoPrezzoTotale=0;
        if($oraInizio!="" && $oraFine!=""){
            settype($oraInizio,"integer");
            settype($oraFine,"integer");
    
            $oraDiAttivita=$oraFine-$oraInizio;
    
            $nuovoPrezzoTotale=$prezzoOrario*$oraDiAttivita;
        }
        if($oraInizio!="" && $oraFine==""){
            $oraFinePrenotazioneAttivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/listaPrenotazioni/prenotazione[idPrenotazione = '$idPrenotazione']/oraFine");
            $oraFinePrenotazioneAttivita = $oraFinePrenotazioneAttivita->item(0)->textContent;
            $oraFinePrenotazioneAttivita =substr($oraFinePrenotazioneAttivita,0,5);
            settype($oraInizio,"integer");
            settype($oraFinePrenotazioneAttivita,"integer");

            $oraDiAttivita=$oraFinePrenotazioneAttivita-$oraInizio;
    
            $nuovoPrezzoTotale=$prezzoOrario*$oraDiAttivita;
        }
        if($oraInizio=="" && $oraFine!=""){
            $oraInizioPrenotazioneAttivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/listaPrenotazioni/prenotazione[idPrenotazione = '$idPrenotazione']/oraInizio");
            $oraInizioPrenotazioneAttivita = $oraInizioPrenotazioneAttivita->item(0)->textContent;
            $oraInizioPrenotazioneAttivita =substr($oraInizioPrenotazioneAttivita,0,5);
            settype($oraInizioPrenotazioneAttivita,"integer");
            settype($oraFine,"integer");

            $oraDiAttivita=$oraFine-$oraInizioPrenotazioneAttivita;
    
            $nuovoPrezzoTotale=$prezzoOrario*$oraDiAttivita;
        }
        $prezzoTotalePrenotazioneAttivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/listaPrenotazioni/prenotazione[idPrenotazione = '$idPrenotazione']/prezzoTotale");
        $prezzoTotalePrenotazioneAttivita = $prezzoTotalePrenotazioneAttivita->item(0);
        $vecchioPrezzoTotale=$prezzoTotalePrenotazioneAttivita->textContent;
        settype($vecchioPrezzoTotale,"integer");
        $creditiUsati = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/listaPrenotazioni/prenotazione[idPrenotazione = '$idPrenotazione']/creditiUsati");
        $vecchiCreditiUsati = $creditiUsati->item(0)->textContent;
        settype($vecchiCreditiUsati ,"integer");
        $scontoCrediti=$vecchiCreditiUsati/5;
        if($nuovoPrezzoTotale<$scontoCrediti){
            $nuoviCreditiUsati= $nuovoPrezzoTotale*5;
            $creditiDaRimborsare=$vecchiCreditiUsati-$nuoviCreditiUsati;

            $codFiscCliente= $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']/listaPrenotazioni/prenotazione[idPrenotazione = '$idPrenotazione']/codFisc");
            $codFiscCliente=$codFiscCliente->item(0)->textContent;
            modificaCreditiCliente($codFiscCliente,$creditiDaRimborsare);

            $creditiUsati->item(0)->nodeValue = "";
            $creditiUsati->item(0)->appendChild($doc->createTextNode($nuoviCreditiUsati));
        
        }
        
        $prezzoTotalePrenotazioneAttivita->nodeValue = "";
        $prezzoTotalePrenotazioneAttivita->appendChild($doc->createTextNode($nuovoPrezzoTotale));

    }

    
        $doc->save("../XML/Attivita.xml"); 
}

function modificaPortata($nuovoNomePortata,$nuovoPrezzo){
    $xmlString = "";
    foreach(file("../XML/Ristorante.xml") as $node){
        $xmlString .= trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathRistorante = new DOMXPath($doc);
    $descrizionePortata=$_SESSION['portataDaModificare'];
    $descrizionePortata=$descrizionePortata['nomeSeparato'];
  
    if($nuovoNomePortata!="" && $nuovoPrezzo==""){
        $nomePortata = $xpathRistorante->query("/ristoranti/ristorante/menu/portata[descrizione = '$descrizionePortata']/descrizione");
        $nomePortata = $nomePortata->item(0);
        $nomePortata->nodeValue = "";
        $nomePortata->appendChild($doc->createTextNode($nuovoNomePortata));
    }

    if($nuovoPrezzo!="" && $nuovoNomePortata==""){
        settype($nuovoPrezzo,"integer");
        $prezzoPortata = $xpathRistorante->query("/ristoranti/ristorante/menu/portata[descrizione = '$descrizionePortata']/prezzo");
        $prezzoPortata = $prezzoPortata->item(0);
        $prezzoPortata->nodeValue = "";
        $prezzoPortata->appendChild($doc->createTextNode($nuovoPrezzo));
    }

    if($nuovoNomePortata!="" && $nuovoPrezzo!=""){
        $nomePortata = $xpathRistorante->query("/ristoranti/ristorante/menu/portata[descrizione = '$descrizionePortata']/descrizione");
        $nomePortata = $nomePortata->item(0);
        $nomePortata->nodeValue = "";
        $nomePortata->appendChild($doc->createTextNode($nuovoNomePortata));

        settype($nuovoPrezzo,"integer");
        $prezzoPortata = $xpathRistorante->query("/ristoranti/ristorante/menu/portata[descrizione = '$nuovoNomePortata']/prezzo");
        $prezzoPortata = $prezzoPortata->item(0);
        $prezzoPortata->nodeValue = "";
        $prezzoPortata->appendChild($doc->createTextNode($nuovoPrezzo));
    }

        $doc->save("../XML/Ristorante.xml"); 
}

function modificaOrariUpdateRistorante($oraInizio,$oraFine){
    $xmlString = "";
    foreach(file("../XML/Ristorante.xml") as $node){
        $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathRistorante = new DOMXPath($doc);
    $oraInizioUpdate = $xpathRistorante->query("/ristoranti/ristorante/oraInizioUpdate");
    $oraInizioUpdate=$oraInizioUpdate->item(0);
    $oraInizioUpdate->nodeValue="";
    $oraInizioUpdate->appendChild($doc->createTextNode($oraInizio.":00"));

    $oraFineUpdate = $xpathRistorante->query("/ristoranti/ristorante/oraFineUpdate");
    $oraFineUpdate=$oraFineUpdate->item(0);
    $oraFineUpdate->nodeValue="";
    $oraFineUpdate->appendChild($doc->createTextNode($oraFine.":00"));

    $doc->save("../XML/Ristorante.xml"); 

}




//Funzione per modificare l'attributo faq di una domanda

function modificaAttributoFaqDomanda($idDomanda , $nuovoAttributo){
    $xmlString = "";
    foreach(file("../XML/Domande.xml") as $node){
        $xmlString .=trim($node);
    }
    $docDomande = new DOMDocument();
    $docDomande->loadXML($xmlString); 
    $docDomande->formatOutput = true;

    $xpathDomande = new DOMXPath($docDomande);

    $domanda = $xpathDomande->query("/listaDomande/domanda[@id='$idDomanda']");
    $domanda = $domanda->item(0);

    if($nuovoAttributo == "true"){
        $domanda->setAttribute("faq", $nuovoAttributo);    
    }
    else{
        $domanda->removeAttribute("faq");
    }

    $docDomande->save("../XML/Domande.xml");
}

//Funzione per modificare la somma delle valutazioni di un cliente 

function modificaSommaGiudiziCliente ($codFiscCliente , $votoDaSommare ){
    $xmlString = "";
    foreach(file("../XML/Clienti.xml") as $node){
        $xmlString .= trim($node);
    }

    $docClienti = new DOMDocument();
    $docClienti->loadXML($xmlString);
    $docClienti->formatOutput = true;

    $xpathClienti = new DOMXPath($docClienti);

    $cliente = $xpathClienti->query("/listaClienti/cliente[@codFisc='$codFiscCliente']");
    $cliente = $cliente->item(0);

    $elemSommaGiudizi = $cliente->getElementsByTagName("sommaGiudizi")->item(0);

    $vecchiaSommaGiudizi = $elemSommaGiudizi->textContent;
    $nuovaSommaGiudizi = $vecchiaSommaGiudizi + $votoDaSommare;

    $elemSommaGiudizi->nodeValue = "";
    $elemSommaGiudizi->appendChild($docClienti->createTextNode($nuovaSommaGiudizi));
     
    $docClienti->save("../XML/Clienti.xml");
}



// Funzione per modificare il tag utilita o il tag accordo di una recensione 

function modificaValutazioneRecensione($idRecensione , $tipoVoto , $votoDaSommare){
    $xmlString = "";
    foreach(file("../XML/Recensioni.xml") as $node){
        $xmlString .= trim($node);
    }

    $docRecensioni = new DOMDocument();
    $docRecensioni->loadXML($xmlString);
    $docRecensioni->formatOutput = true;

    $xpathRecensioni = new DOMXPath($docRecensioni);

    $recensione = $xpathRecensioni->query("/listaRecensioni/recensione[@id='$idRecensione']");
    $recensione = $recensione->item(0);

    $elemVoto = $recensione->getElementsByTagName($tipoVoto)->item(0);
    
    $vecchioVoto = $elemVoto->textContent;
    $nuovoVoto = $vecchioVoto + $votoDaSommare;

    $elemVoto->nodeValue = "";
    $elemVoto->appendChild($docRecensioni->createTextNode($nuovoVoto));

    $codFiscAutore = $recensione->getElementsByTagName("codFiscAutore")->item(0)->textContent;
    modificaSommaGiudiziCliente($codFiscAutore , $votoDaSommare);

    $docRecensioni->save("../XML/Recensioni.xml");
}


//Funzione per modificare il tag utilita o il tag accordo di un commento 

function modificaValutazioneCommento($idCommento , $tipoVoto , $votoDaSommare){
    $xmlString = "";
    foreach(file("../XML/Commenti.xml") as $node){
        $xmlString .= trim($node);
    }

    $docCommenti = new DOMDocument();
    $docCommenti->loadXML($xmlString);
    $docCommenti->formatOutput = true;

    $xpathCommenti = new DOMXPath($docCommenti);

    $commento = $xpathCommenti->query("/listaCommenti/commento[@id='$idCommento']");
    $commento = $commento->item(0);

    $elemVoto = $commento->getElementsByTagName($tipoVoto)->item(0);
    
    $vecchioVoto = $elemVoto->textContent;
    $nuovoVoto = $vecchioVoto + $votoDaSommare;

    $elemVoto->nodeValue = "";
    $elemVoto->appendChild($docCommenti->createTextNode($nuovoVoto));

    $codFiscAutore = $commento->getElementsByTagName("codFiscAutore")->item(0)->textContent;
    modificaSommaGiudiziCliente($codFiscAutore , $votoDaSommare);   

    $docCommenti->save("../XML/Commenti.xml");
}


//Funzione per modificare il tag utilita o il tag accordo di una risposta ad un commento

function modificaValutazioneRispostaCommento($idRisposta , $tipoVoto , $votoDaSommare){
    $xmlString = "";
    foreach(file("../XML/Commenti.xml") as $node){
        $xmlString .= trim($node);
    }

    $docCommenti = new DOMDocument();
    $docCommenti->loadXML($xmlString);
    $docCommenti->formatOutput = true;

    $xpathRecensioni = new DOMXPath($docCommenti);

    $risposta = $xpathRecensioni->query("/listaCommenti/commento/listaRisposte/risposta[idRisposta='$idRisposta']");
    $risposta = $risposta->item(0);

    $elemVoto = $risposta->getElementsByTagName($tipoVoto)->item(0);
    
    $vecchioVoto = $elemVoto->textContent;
    $nuovoVoto = $vecchioVoto + $votoDaSommare;

    $elemVoto->nodeValue = "";
    $elemVoto->appendChild($docCommenti->createTextNode($nuovoVoto));

    $codFiscAutore = $risposta->getElementsByTagName("codFiscAutoreRisposta")->item(0)->textContent;
    modificaSommaGiudiziCliente($codFiscAutore , $votoDaSommare);   

    $docCommenti->save("../XML/Commenti.xml");
}


function modificaValutazione($idOggetto , $tipoOggetto , $codFiscCliente , $nuovoVotoUtilita , $nuovoVotoAccordo){
    $xmlString = "";
    foreach(file("../XML/Valutazioni.xml") as $node){
        $xmlString .= trim($node);
    }

    $docValutazioni = new DOMDocument();
    $docValutazioni->loadXML($xmlString);
    $docValutazioni->formatOutput = true;

    $xpathValutazioni = new DOMXPath($docValutazioni);

    $valutazioneDaModificare = $xpathValutazioni->query("/listaValutazioni/valutazione[idOggettoValutato='$idOggetto' and codFisc='$codFiscCliente']");
    $valutazioneDaModificare = $valutazioneDaModificare->item(0);

    $vecchioVotoUtilita = $valutazioneDaModificare->getElementsByTagName("votoUtilita")->item(0);
    $vecchioVotoAccordo = $valutazioneDaModificare->getElementsByTagName("votoAccordo")->item(0);    

    if($nuovoVotoUtilita == 0 && $nuovoVotoAccordo == 0){    
        $codFiscCliente = $valutazioneDaModificare->getElementsByTagName("codFisc")->item(0)->textContent;    
        if($tipoOggetto == "Recensione"){            
            modificaValutazioneRecensione($idOggetto , "utilita" , -$vecchioVotoUtilita->textContent);
            modificaValutazioneRecensione($idOggetto , "accordo" , -$vecchioVotoAccordo->textContent);                               
        }
        elseif($tipoOggetto == "Commento"){
            modificaValutazioneCommento($idOggetto , "utilita" , -$vecchioVotoUtilita->textContent);
            modificaValutazioneCommento($idOggetto , "accordo" , -$vecchioVotoAccordo->textContent);
        }
        else{
            modificaValutazioneRispostaCommento($idOggetto , "utilitaRisposta", -$vecchioVotoUtilita->textContent);
            modificaValutazioneRispostaCommento($idOggetto , "accordoRisposta" , -$vecchioVotoAccordo->textContent);
        }

        rimuoviValutazione($idOggetto , $codFiscCliente); 
    }
    else{
        $diffUtilita = $nuovoVotoUtilita - $vecchioVotoUtilita->textContent;
        if($diffUtilita != 0){
            $vecchioVotoUtilita->nodeValue = "";
            $vecchioVotoUtilita->appendChild($docValutazioni->createTextNode($nuovoVotoUtilita));

            if($tipoOggetto == "Recensione"){            
                modificaValutazioneRecensione($idOggetto , "utilita" , $diffUtilita);                                               
            }
            elseif($tipoOggetto == "Commento"){
                modificaValutazioneCommento($idOggetto , "utilita" , $diffUtilita);                
            }
            else{
                modificaValutazioneRispostaCommento($idOggetto , "utilitaRisposta", $diffUtilita);                
            }
        }

        $diffAccordo = $nuovoVotoAccordo - $vecchioVotoAccordo->textContent;
        if($diffAccordo != 0){
            $vecchioVotoAccordo->nodeValue = "";
            $vecchioVotoAccordo->appendChild($docValutazioni->createTextNode($nuovoVotoAccordo));

            if($tipoOggetto == "Recensione"){            
                modificaValutazioneRecensione($idOggetto , "accordo" , $diffAccordo);                                               
            }
            elseif($tipoOggetto == "Commento"){
                modificaValutazioneCommento($idOggetto , "accordo" , $diffAccordo);                
            }
            else{
                modificaValutazioneRispostaCommento($idOggetto , "accordoRisposta", $diffAccordo);                
            }
        }

        $docValutazioni->save("../XML/Valutazioni.xml");

    }   

    


    
}



//Funzione per modificare la risposta di una faq

function modificaFaq($idFaq,$nuovaRisposta){
    $xmlString = "";
    foreach(file("../XML/FAQs.xml") as $node){
        $xmlString .=trim($node);
    }
    $docFaq = new DOMDocument();
    $docFaq->loadXML($xmlString); 
    $docFaq->formatOutput = true;

    $xpathFaq = new DOMXPath($docFaq);

    $testoRispostaFaq = $xpathFaq->query("/listaFAQ/FAQ[@id='$idFaq']/testoRisposta");
    $testoRispostaFaq = $testoRispostaFaq->item(0);
    $testoRispostaFaq->nodeValue="";
    $testoRispostaFaq->appendChild($docFaq->createTextNode($nuovaRisposta));

    $docFaq->save("../XML/FAQs.xml");
}


function modificaCodFiscPrenotazioniAttivita($nuovoCodFisc , $vecchioCodFisc){
    $xmlString = "";
    
    foreach(file("../XML/Attivita.xml") as $node){
        $xmlString .= trim($node);
    }

    $docAttivita = new DOMDocument();
    $docAttivita->loadXML($xmlString);
    $docAttivita->formatOutput = true;

    $xpathAttivita = new DOMXPath($docAttivita);

    $listaPrenotazioni = $xpathAttivita->query("/listaAttivita/attivita/listaPrenotazioni/prenotazione[codFisc='$vecchioCodFisc']");

    for($i=0 ; $i < $listaPrenotazioni->length ; $i++){
        $prenotazione = $listaPrenotazioni->item($i);
        $elemCodFisc = $prenotazione->getElementsByTagName("codFisc")->item(0);

        $elemCodFisc->nodeValue = "";
        $elemCodFisc->appendChild($docAttivita->createTextNode($nuovoCodFisc));
    }
    $docAttivita->save("../XML/Attivita.xml");
}

function modificaCodFiscPrenotazioniCamere($nuovoCodFisc , $vecchioCodFisc){
    $xmlString = "";

    foreach(file("../XML/Camere.xml") as $node){
        $xmlString .= trim($node);
    }

    $docCamere = new DOMDocument();
    $docCamere->loadXML($xmlString);
    $docCamere->formatOutput = true;

    $xpathCamere = new DOMXPath($docCamere);
    $listaPrenotazioni = $xpathCamere->query("/listaCamere/Camera/listaPrenotazioni/prenotazione[codFisc='$vecchioCodFisc']");

    for($i=0 ; $i < $listaPrenotazioni->length ; $i++){
        $prenotazione = $listaPrenotazioni->item($i);
        $elemCodFisc = $prenotazione->getElementsByTagName("codFisc")->item(0);

        $elemCodFisc->nodeValue = "";
        $elemCodFisc->appendChild($docCamere->createTextNode($nuovoCodFisc));
    }
    $docCamere->save("../XML/Camere.xml");
}

function modificaCodFiscCategorie($nuovoCodFisc , $vecchioCodFisc){
    $xmlString = "";

    foreach(file("../XML/Categorie.xml") as $node){
        $xmlString .= trim($node);
    }

    $docCategorie = new DOMDocument();
    $docCategorie->loadXML($xmlString);
    $docCategorie->formatOutput = true;

    $xpathCategorie = new DOMXPath($docCategorie);

    $listaUtenti = $xpathCategorie->query("/listaCategorie/categoria/listaUtenti/utente[codFisc='$vecchioCodFisc']");

    for($i=0 ; $i < $listaUtenti->length ; $i++){
        $utente = $listaUtenti->item($i);
        $elemCodFisc = $utente->firstChild;

        $elemCodFisc->nodeValue = "";
        $elemCodFisc->appendChild($docCategorie->createTextNode($nuovoCodFisc));        
    }
    $docCategorie->save("../XML/Categorie.xml");
}

function modificaCodFiscCommenti($nuovoCodFisc , $vecchioCodFisc){
    $xmlString = "";

    foreach(file("../XML/Commenti.xml") as $node){
        $xmlString .= trim($node);
    }

    $docCommenti = new DOMDocument();
    $docCommenti->loadXML($xmlString);
    $docCommenti->formatOutput = true;

    $xpathCommenti = new DOMXPath($docCommenti);

    $listaCommenti = $xpathCommenti->query("/listaCommenti/commento[codFiscAutore='$vecchioCodFisc']");
    for($i=0 ; $i < $listaCommenti->length ; $i++){
        $commento = $listaCommenti->item($i);
        $elemCodFisc = $commento->getElementsByTagName("codFiscAutore")->item(0);

        $elemCodFisc->nodeValue = "";
        $elemCodFisc->appendChild($docCommenti->createTextNode($nuovoCodFisc));        
    }

    $listaRisposteCommento = $xpathCommenti->query("/listaCommenti/commento/listaRisposte/risposta[codFiscAutoreRisposta='$vecchioCodFisc']");
    for($i=0 ; $i < $listaRisposteCommento->length ; $i++){
        $rispostaCommento = $listaRisposteCommento->item($i);
        $elemCodFisc = $rispostaCommento->getElementsByTagName("codFiscAutoreRisposta")->item(0);

        $elemCodFisc->nodeValue = "";
        $elemCodFisc->appendChild($docCommenti->createTextNode($nuovoCodFisc));        
    }

    $docCommenti->save("../XML/Commenti.xml");
}


function modificaCodFiscDomande($nuovoCodFisc , $vecchioCodFisc){
    $xmlString = "";

    foreach(file("../XML/Domande.xml") as $node){
        $xmlString .= trim($node);
    }

    $docDomande = new DOMDocument();
    $docDomande->loadXML($xmlString);
    $docDomande->formatOutput = true;

    $xpathDomande = new DOMXPath($docDomande);

    $listaDomande = $xpathDomande->query("/listaDomande/domanda[codFiscAutore='$vecchioCodFisc']");
    for($i=0 ; $i < $listaDomande->length ; $i++){
        $domanda = $listaDomande->item($i);
        $elemCodFisc = $domanda->getElementsByTagName("codFiscAutore")->item(0);

        $elemCodFisc->nodeValue = "";
        $elemCodFisc->appendChild($docDomande->createTextNode($nuovoCodFisc));        
    }

    $listaRisposteDomande = $xpathDomande->query("/listaDomande/domanda/listaRisposte/risposta[codFiscAutoreRisposta='$vecchioCodFisc']");
    for($i=0 ; $i < $listaRisposteDomande->length ; $i++){
        $rispostaDomanda = $listaRisposteDomande->item($i);
        $elemCodFisc = $rispostaDomanda->getElementsByTagName("codFiscAutoreRisposta")->item(0);

        $elemCodFisc->nodeValue = "";
        $elemCodFisc->appendChild($docDomande->createTextNode($nuovoCodFisc));        
    }
    $docDomande->save("../XML/Domande.xml");
}


function modificaCodFiscRecensioni($nuovoCodFisc , $vecchioCodFisc){
    $xmlString = "";

    foreach(file("../XML/Recensioni.xml") as $node){
        $xmlString .= trim($node);
    }

    $docRecensioni = new DOMDocument();
    $docRecensioni->loadXML($xmlString);
    $docRecensioni->formatOutput = true;

    $xpathRecensioni = new DOMXPath($docRecensioni);

    $listaRecensioni = $xpathRecensioni->query("/listaRecensioni/recensione[codFiscAutore='$vecchioCodFisc']");
    for($i=0 ; $i < $listaRecensioni->length ; $i++){
        $recensione = $listaRecensioni->item($i);
        $elemCodFisc = $recensione->getElementsByTagName("codFiscAutore")->item(0);

        $elemCodFisc->nodeValue = "";
        $elemCodFisc->appendChild($docRecensioni->createTextNode($nuovoCodFisc));        
    }
    $docRecensioni->save("../XML/Recensioni.xml");
}


function modificaCodFiscPrenotazioniSC($nuovoCodFisc , $vecchioCodFisc){
    $xmlString = "";

    foreach(file("../XML/ServizioCamera.xml") as $node){
        $xmlString .= trim($node);
    }

    $docSC = new DOMDocument();
    $docSC->loadXML($xmlString);
    $docSC->formatOutput = true;

    $xpathSC = new DOMXPath($docSC);

    $listaPrenotazioni = $xpathSC->query("/listaPrenotazioni/prenotazione[codFisc='$vecchioCodFisc']");
    for($i=0 ; $i < $listaPrenotazioni->length ; $i++){
        $prenotazione = $listaPrenotazioni->item($i);
        $elemCodFisc = $prenotazione->getElementsByTagName("codFisc")->item(0);

        $elemCodFisc->nodeValue = "";
        $elemCodFisc->appendChild($docSC->createTextNode($nuovoCodFisc));
    }
    $docSC->save("../XML/ServizioCamera.xml");
}



function modificaCodFiscPrenotazioniTavolo ($nuovoCodFisc , $vecchioCodFisc){
    $xmlString = "";

    foreach(file("../XML/Tavoli.xml") as $node){
        $xmlString .= trim($node);
    }

    $docTavoli = new DOMDocument();
    $docTavoli->loadXML($xmlString);
    $docTavoli->formatOutput = true;

    $xpathTavoli = new DOMXPath($docTavoli);

    $listaPrenotazioni = $xpathTavoli->query("/listaTavoli/tavolo/listaPrenotazioni/prenotazione[codFisc='$vecchioCodFisc']");
    for($i=0 ; $i < $listaPrenotazioni->length ; $i++){
        $prenotazione = $listaPrenotazioni->item($i);
        $elemCodFisc = $prenotazione->getElementsByTagName("codFisc")->item(0);

        $elemCodFisc->nodeValue = "";
        $elemCodFisc->appendChild($docTavoli->createTextNode($nuovoCodFisc));
    }

    $docTavoli->save("../XML/Tavoli.xml");
}


function modificaCodFiscValutazioni($nuovoCodFisc , $vecchioCodFisc){
    $xmlString = "";

    foreach(file("../XML/Valutazioni.xml") as $node){
        $xmlString .= trim($node);
    }

    $docValutazioni = new DOMDocument();
    $docValutazioni->loadXML($xmlString);
    $docValutazioni->formatOutput = true;

    $xpathValutazioni = new DOMXPath($docValutazioni);

    $listaValutazioni = $xpathValutazioni->query("/listaValutazioni/valutazione[codFisc='$vecchioCodFisc']");
    for($i=0 ; $i < $listaValutazioni->length ; $i++){
        $valutazione = $listaValutazioni->item($i);
        $elemCodFisc = $valutazione->getElementsByTagName("codFisc")->item(0);

        $elemCodFisc->nodeValue = "";
        $elemCodFisc->appendChild($docValutazioni->createTextNode($nuovoCodFisc));
    }

    $docValutazioni->save("../XML/Valutazioni.xml");
}



//Funzione per aggiornare i codici fiscali di ogni file quando un utente modifica il suo codice fiscale 

function modificaCodiciFiscali($nuovoCodFisc , $vecchioCodFisc){    
    modificaCodFiscPrenotazioniAttivita($nuovoCodFisc , $vecchioCodFisc);    
    modificaCodFiscPrenotazioniCamere($nuovoCodFisc , $vecchioCodFisc);
    modificaCodFiscCategorie($nuovoCodFisc , $vecchioCodFisc);
    modificaCodFiscCommenti($nuovoCodFisc , $vecchioCodFisc);
    modificaCodFiscDomande($nuovoCodFisc , $vecchioCodFisc);
    modificaCodFiscRecensioni($nuovoCodFisc , $vecchioCodFisc);
    modificaCodFiscPrenotazioniSC($nuovoCodFisc , $vecchioCodFisc);
    modificaCodFiscPrenotazioniTavolo($nuovoCodFisc , $vecchioCodFisc);
    modificaCodFiscValutazioni($nuovoCodFisc , $vecchioCodFisc); 
}


?>
