<?php
// FILE CHE CONTIENE TUTTE E SOLO LE FUNZIONI PER ESTRARRE DATI DA I FILE XML

// Funzione per verificare se il cliente che ha effettuato il login ha un soggiorno attivo al momento (cioè con pagamento approvato)

function getSoggiornoAttivo($codFiscCliente){
    $xmlString = "";
    foreach(file("../XML/Camere.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathCamere = new DOMXPath($doc);

    $idPrenotazione = $xpathCamere->query("/listaCamere/Camera/listaPrenotazioni/prenotazione[codFisc='$codFiscCliente' and statoSoggiorno='Approvato']/idPrenotazione");
    if($idPrenotazione->length == 1){
        return $idPrenotazione->item(0)->textContent;
    }
    else{
        return "null";      //Questo significa che non è stato trovato nessun soggiorno attivo
    }
}






?>