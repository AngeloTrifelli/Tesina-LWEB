<?php
    require_once('funzioniModificaPHP.php');

// Funzione per eliminare la prenotazione di un servizio al tavolo

function rimuoviPrenotazioneTavolo($idPrenotazione){
    $xmlStringTavoli = "";
    foreach(file("../XML/Tavoli.xml") as $node){
        $xmlStringTavoli .=trim($node);
    }
    $docTavoli = new DOMDocument();
    $docTavoli->loadXML($xmlStringTavoli);   
    $docTavoli->formatOutput = true;
    
    $xpathTavoli = new DOMXPath($docTavoli);

    $pieces = explode("-", $idPrenotazione);
    $idTavolo = $pieces[0];

    $tavolo = $xpathTavoli->query("/listaTavoli/tavolo[@numero='$idTavolo']");
    $tavolo = $tavolo->item(0);
    $listaPrenotazioni = $tavolo->lastChild;
    
    $prenotazioneDaEliminare = $xpathTavoli->query("//prenotazione[idPrenotazione='$idPrenotazione']");
    $prenotazioneDaEliminare = $prenotazioneDaEliminare->item(0);

    $listaPrenotazioni->removeChild($prenotazioneDaEliminare);

    $docTavoli->save("../XML/Tavoli.xml");
}


//Funzione per eliminare la prenotazione di un servizio in camera

function rimuoviPrenotazioneServizioCamera($idPrenotazione){
    $xmlString = "";
    foreach(file("../XML/ServizioCamera.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathSC = new DOMXPath($doc);

    $prenotazioneDaEliminare = $xpathSC->query("/listaPrenotazioni/prenotazione[@id='$idPrenotazione']");
    $prenotazioneDaEliminare = $prenotazioneDaEliminare->item(0);

    $listaPrenotazioni = $doc->documentElement;

    $listaPrenotazioni->removeChild($prenotazioneDaEliminare);

    $doc->save("../XML/ServizioCamera.xml");
}

// Funzione per rimuovere una portata da una prenotazione di un servizio in camera
// La portata viene rimossa del tutto solo se la nuova quantita è pari a 0. Altrimenti, viene semplicemente decrementata la quantita

function rimuoviPortataPrenotazioneSC($idPrenotazione, $nomePortataScelta , $quantitaDaRimuovere){
    $xmlString = "";
    foreach(file("../XML/ServizioCamera.xml") as $node ){
        $xmlString .= trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathSC = new DOMXPath($doc);

    $prenotazioneSC = $xpathSC->query("/listaPrenotazioni/prenotazione[@id='$idPrenotazione']");
    $prenotazioneSC = $prenotazioneSC->item(0);

    $portata = $xpathSC->query("/listaPrenotazioni/prenotazione[@id='$idPrenotazione']/listaPortate/portata[nome='$nomePortataScelta']");
    $portata = $portata->item(0);

    $prezzoPortata = $portata->getElementsByTagName("prezzo")->item(0)->textContent;
    $vecchiaQuantita = $portata->getElementsByTagName("quantita")->item(0);
    $nuovaQuantita = $vecchiaQuantita->textContent - $quantitaDaRimuovere;

    if($nuovaQuantita != 0){
        $vecchiaQuantita->nodeValue = "";
        $vecchiaQuantita->appendChild($doc->createTextNode($nuovaQuantita));
    }
    else{
        $listaPortate = $prenotazioneSC->getElementsByTagName("listaPortate")->item(0);
        $listaPortate->removeChild($portata);
    }

    $prezzoDaSottrarre = $prezzoPortata * $quantitaDaRimuovere;
    $vecchioPrezzoTot = $prenotazioneSC->getElementsByTagName("prezzoTotale")->item(0);

    $nuovoPrezzoTot = $vecchioPrezzoTot->textContent - $prezzoDaSottrarre;

    $creditiUsati = $prenotazioneSC->getElementsByTagName("creditiUsati")->item(0);

    $scontoCrediti = $creditiUsati->textContent / 5;
    if($scontoCrediti > $nuovoPrezzoTot){
        $nuoviCreditiUsati = $nuovoPrezzoTot * 5;
        $creditiDaRimborsare = $creditiUsati->textContent - $nuoviCreditiUsati;

        $codFiscCliente = $prenotazioneSC->getElementsByTagName("codFisc")->item(0)->textContent;
        modificaCreditiCliente($codFiscCliente , $creditiDaRimborsare);

        $creditiUsati->nodeValue = "";
        $creditiUsati->appendChild($doc->createTextNode($nuoviCreditiUsati));
    }

    $vecchioPrezzoTot->nodeValue="";
    $vecchioPrezzoTot->appendChild($doc->createTextNode($nuovoPrezzoTot));

    $doc->save("../XML/ServizioCamera.xml");
}



?>