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


?>
