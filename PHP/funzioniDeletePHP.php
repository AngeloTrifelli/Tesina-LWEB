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

//Funzione per eliminare una prenotazione di una attivita

function rimuoviPrenotazioneAttivita($idPrenotazioneAttivita){
   
    $idAttivita=substr($idPrenotazioneAttivita,0,2);
    $xmlStringAttivita= "";
    

    foreach(file("../XML/Attivita.xml") as $node){

       $xmlStringAttivita .= trim($node);

   }

   $docAttivita = new DOMDocument();
   $docAttivita->loadXML($xmlStringAttivita);
   $docAttivita->formatOutput = true;

   $xpathAttivita = new DOMXPath($docAttivita);

   $attivita=$xpathAttivita->query("/listaAttivita/attivita[@id='$idAttivita']");
   $attivita=$attivita->item(0);
   $listaPrenotazioni=$attivita->lastChild;


   $prenotazioneDaEliminare = $xpathAttivita->query("//prenotazione[idPrenotazione='$idPrenotazioneAttivita']");
   $prenotazioneDaEliminare = $prenotazioneDaEliminare->item(0);

   $listaPrenotazioni->removeChild($prenotazioneDaEliminare);
   

    $docAttivita->save("../XML/Attivita.xml");
    
}

function rimuoviPortataDalMenu($descrizionePortata){
  
    $xmlStringRistorante= "";
    

    foreach(file("../XML/Ristorante.xml") as $node){

       $xmlStringRistorante .= trim($node);

   }

   $docRistorante = new DOMDocument();
   $docRistorante->loadXML($xmlStringRistorante);
   $docRistorante->formatOutput = true;

   $xpathRistorante = new DOMXPath($docRistorante);

   $ristorante=$xpathRistorante->query("/ristoranti/ristorante");
   $ristorante=$ristorante->item(0);
   $menu=$ristorante->lastChild;

   $portataDaEliminare=$xpathRistorante->query("/ristoranti/ristorante/menu/portata[descrizione = '$descrizionePortata']");
   $portataDaEliminare=$portataDaEliminare->item(0);

   $menu->removeChild($portataDaEliminare);

   $docRistorante->save("../XML/Ristorante.xml");
}

function rimuoviFAQ($idFaq){

    $xmlString= "";
    

    foreach(file("../XML/FAQs.xml") as $node){

       $xmlString .= trim($node);

   }

   $doc = new DOMDocument();
   $doc->loadXML($xmlString);
   $doc->formatOutput = true;

   $xpathFaq = new DOMXPath($doc);

   $faqDaEliminare=$xpathFaq->query("/listaFAQ/FAQ[@id='$idFaq']");
   $faqDaEliminare=$faqDaEliminare->item(0);
   $listaFaq=$doc->documentElement;

   $listaFaq->removeChild($faqDaEliminare);
   

    $doc->save("../XML/FAQs.xml");
}

?>