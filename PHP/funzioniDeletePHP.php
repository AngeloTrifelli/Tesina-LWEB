<?php


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

?>