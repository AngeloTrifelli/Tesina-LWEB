<?php
    require_once('funzioniGetPHP.php');
    require_once("funzioniModificaPHP.php");          

// FILE CHE CONTIENE TUTTE E SOLO LE FUNZIONI PER INSERIRE DATI NEI FILE XML


//Funzione che crea un nuovo utente all'interno del file Clienti.xml
function inserisciNuovoCliente(){    
    $xmlStringClienti = "";

    foreach(file("../XML/Clienti.xml") as $node){
        $xmlStringClienti .= trim($node);
    }
            
    $docClienti = new DOMDocument();
    $docClienti->loadXML($xmlStringClienti);
    $docClienti->formatOutput = true;

    $anno = mb_substr($_POST['dataNascita'], 0 , 4);
    $mese = mb_substr($_POST['dataNascita'], 5 , 2);
    $giorno = mb_substr($_POST['dataNascita'], 8 , 2);

    $currentDate= date('Y-m-d');

    $nuovoCliente = $docClienti->createElement("cliente");
    $nuovoCliente->setAttribute("codFisc", $_POST['codFisc']);
    $listaClienti = $docClienti->documentElement;
    $listaClienti->appendChild($nuovoCliente);

    $nuovoNome = $docClienti->createElement("nome", $_POST['nome']);
    $nuovoCliente->appendChild($nuovoNome);

    $nuovoCognome = $docClienti->createElement("cognome", $_POST['cognome']);
    $nuovoCliente->appendChild($nuovoCognome);

    $nuovaDataNascita = $docClienti->createElement("dataDiNascita", $anno."-".$mese."-".$giorno);
    $nuovoCliente->appendChild($nuovaDataNascita);

    $nuovoIndirizzo = $docClienti->createElement("indirizzo", $_POST['indirizzo']);
    $nuovoCliente->appendChild($nuovoIndirizzo);

    $nuovoTelefono = $docClienti->createElement("telefono", $_POST['telefono']);
    $nuovoCliente->appendChild($nuovoTelefono);

    $nuovaEmail = $docClienti->createElement("email", $_POST['email']);
    $nuovoCliente->appendChild($nuovaEmail);

    $nuovoNumCarta = $docClienti->createElement("numeroCarta", $_POST['numeroCarta']);
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

    aggiungiCategoriaAdUtente($_POST['codFisc'] , "Registrazione cliente");

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
    aggiungiCategoriaAdUtente($codFiscCliente , "Prenotazione soggiorno");

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
    $ultimaPrenotazione = $listaPrenotazioni->lastChild;

    if(is_null($ultimaPrenotazione)){
        $idNuovaPrenotazione = $idAttivita."-PA1";
    }
    else{
        $idUltimaPrenotazione = $ultimaPrenotazione->getElementsByTagName('idPrenotazione')->item(0)->textContent;

        $pieces = explode("-",$idUltimaPrenotazione);

        $nuovoNumero = substr($pieces[1] , 2) + 1;
        $idNuovaPrenotazione = $idAttivita."-PA".$nuovoNumero;    
    }    

    $nuovaPrenotazione = $docAttivita->createElement("prenotazione");
    $listaPrenotazioni->appendChild($nuovaPrenotazione);

    $nuovoIdPrenotazione = $docAttivita->createElement("idPrenotazione", $idNuovaPrenotazione);
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

    aggiungiCategoriaAdUtente($codFisc , "Prenotazione attivita");

    $docAttivita->save("../XML/Attivita.xml");

}

//Funzione che inserisce una nuova categoria nel file Categorie.xml

function aggiungiCategoriaNuova($categoriaDaAggiungere){

    $xmlStringCategoria= "";

    foreach(file("../XML/Categorie.xml") as $node){

        $xmlStringCategoria .= trim($node);

    }

    $docCategoria = new DOMDocument();
    $docCategoria->loadXML($xmlStringCategoria);
    $docCategoria->formatOutput = true;

    $nuovaCategoria = $docCategoria->createElement("categoria");
    $listaCategorie= $docCategoria->documentElement;
    $listaCategorie->appendChild($nuovaCategoria);

    $nuovoNome = $docCategoria->createElement("nome", $categoriaDaAggiungere['nome']);
    $nuovaCategoria->appendChild($nuovoNome);

    $nuovoStato = $docCategoria->createElement("stato", "Attiva");
    $nuovaCategoria->appendChild($nuovoStato);

    $nuovaListaAzioni = $docCategoria->createElement("listaAzioni");
    $nuovaCategoria->appendChild($nuovaListaAzioni);

    if($categoriaDaAggiungere['prenotazioneTavolo']!=""){
        $nuovaAzioneUtente = $docCategoria->createElement("azioneUtente");
        $nuovaListaAzioni->appendChild($nuovaAzioneUtente);
        $nuovaNomeAzione = $docCategoria->createElement("nomeAzione", "Prenotazione tavolo");
        $nuovaAzioneUtente->appendChild($nuovaNomeAzione);
    }
    if($categoriaDaAggiungere['prenotazioneServizioInCamera']!=""){
        $nuovaAzioneUtente = $docCategoria->createElement("azioneUtente");
        $nuovaListaAzioni->appendChild($nuovaAzioneUtente);
        $nuovaNomeAzione = $docCategoria->createElement("nomeAzione", "Prenotazione servizio in camera");
        $nuovaAzioneUtente->appendChild($nuovaNomeAzione);
    }
    if($categoriaDaAggiungere['attivita']!=""){
        $nuovaAzioneUtente = $docCategoria->createElement("azioneUtente");
        $nuovaListaAzioni->appendChild($nuovaAzioneUtente);
        $nuovaNomeAzione = $docCategoria->createElement("nomeAzione", "Prenotazione attivita");
        $nuovaAzioneUtente->appendChild($nuovaNomeAzione);
    }
    if($categoriaDaAggiungere['prenotazioneSoggiorno']!=""){
        $nuovaAzioneUtente = $docCategoria->createElement("azioneUtente");
        $nuovaListaAzioni->appendChild($nuovaAzioneUtente);
        $nuovaNomeAzione = $docCategoria->createElement("nomeAzione", "Prenotazione soggiorno");
        $nuovaAzioneUtente->appendChild($nuovaNomeAzione);
    }
    if($categoriaDaAggiungere['registrazione']!=""){
        $nuovaAzioneUtente = $docCategoria->createElement("azioneUtente");
        $nuovaListaAzioni->appendChild($nuovaAzioneUtente);
        $nuovaNomeAzione = $docCategoria->createElement("nomeAzione", "Registrazione cliente");
        $nuovaAzioneUtente->appendChild($nuovaNomeAzione);
    }

    $nuovaListaUtenti = $docCategoria->createElement("listaUtenti");
    $nuovaCategoria->appendChild($nuovaListaUtenti);

    $docCategoria->save("../XML/Categorie.xml");

}



// Funzione per inserire una prenotazione ad un determinato tavolo

function inserisciPrenotazioneTavolo($numeroTavolo , $codFiscCliente , $data , $ora){
    $xmlString= "";

    foreach(file("../XML/Tavoli.xml") as $node){
        $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathTavoli = new DOMXPath($doc);

    $tavolo = $xpathTavoli->query("/listaTavoli/tavolo[@numero = '$numeroTavolo']");
    $tavolo = $tavolo->item(0);

    $listaPrenotazioni=$tavolo->getElementsByTagName("listaPrenotazioni")->item(0);
    $tempListaPrenotazioni=$xpathTavoli->query("/listaTavoli/tavolo[@numero = '$numeroTavolo']/listaPrenotazioni");
    if(count($tempListaPrenotazioni) ==0){
        $idNuovaPrenotazione = $numeroTavolo."-PT1";
    }else{
        $numPrenotazioni= (count($tempListaPrenotazioni))-1;
        $prenotazione= $xpathTavoli->query("/listaTavoli/tavolo[@numero = '$numeroTavolo']/listaPrenotazioni/prenotazione");
        $ultimaPrenotazione= $prenotazione->item($numPrenotazioni);
        $idUltimaPrenotazione = $ultimaPrenotazione->getElementsByTagName('idPrenotazione')->item(0)->textContent;
        $nuovoNumero=substr($idUltimaPrenotazione,5,1);
        settype($nuovoNumero,"integer");
        $idNuovaPrenotazione = $numeroTavolo."-PT".($nuovoNumero+1);    
    }

    $nuovaPrenotazione = $doc->createElement("prenotazione");
    $listaPrenotazioni->appendChild($nuovaPrenotazione);

    $nuovoIdPrenotazione = $doc->createElement("idPrenotazione" , $idNuovaPrenotazione);
    $nuovaPrenotazione->appendChild($nuovoIdPrenotazione);

    $nuovoCodFisc = $doc->createElement("codFisc" , $codFiscCliente);
    $nuovaPrenotazione->appendChild($nuovoCodFisc);

    $nuovaDataPrenotazione = $doc->createElement("data" , $data);
    $nuovaPrenotazione->appendChild($nuovaDataPrenotazione);

    $nuovaOraPrenotazione = $doc->createElement("ora" , $ora);
    $nuovaPrenotazione->appendChild($nuovaOraPrenotazione);

    aggiungiCategoriaAdUtente($codFiscCliente , "Prenotazione tavolo");

    $doc->save("../XML/Tavoli.xml");
}

// Funzione per inserire la prenotazione di un servizio in camera in dettagliPrenotazioneSC.php

function inserisciPrenotazioneServizioCamera($portateScelte , $codFiscCliente   ,$data , $ora , $richieste , $prezzoTotale , $creditiUsati){
    $xmlString = "";
    foreach(file("../XML/ServizioCamera.xml") as $node ){
        $xmlString .= trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathPrenotazioniSC = new DOMXPath($doc);

    $elemRadice = $doc->documentElement;
    $listaPrenotazioni = $elemRadice->childNodes;
    if(count($listaPrenotazioni) ==0){
        $idNuovaPrenotazione ="PSC1";
    }else{
        $numPrenotazioni= count($listaPrenotazioni)-1;
        $prenotazione=$xpathPrenotazioniSC->query("/listaPrenotazioni/prenotazione");
        $ultimaPrenotazione= $prenotazione->item($numPrenotazioni);
        $idUltimaPrenotazione = $ultimaPrenotazione->getAttribute("id");
        $numeroVecchio= substr($idUltimaPrenotazione,3,1);
        settype($numeroVecchio,"integer");
        $nuovoNumero= $numeroVecchio+1;
        $idNuovaPrenotazione ="PSC".$nuovoNumero;    
    }

    $nuovaPrenotazione = $doc->createElement("prenotazione");
    $nuovaPrenotazione->setAttribute("id" , $idNuovaPrenotazione );
    $elemRadice->appendChild($nuovaPrenotazione);

    $nuovoCodFisc = $doc->createElement("codFisc" , $codFiscCliente);
    $nuovaPrenotazione->appendChild($nuovoCodFisc);

    $nuovaData = $doc->createElement("data" , $data);
    $nuovaPrenotazione->appendChild($nuovaData);

    $nuovaOra = $doc->createElement("ora" , $ora);
    $nuovaPrenotazione->appendChild($nuovaOra);

    $nuoveRichieste = $doc->createElement("richieste" , $richieste);
    $nuovaPrenotazione->appendChild($nuoveRichieste);

    $nuovoPrezzoTotale = $doc->createElement("prezzoTotale" , $prezzoTotale);
    $nuovaPrenotazione->appendChild($nuovoPrezzoTotale);

    modificaCreditiCliente($codFiscCliente , -$creditiUsati);

    $nuoviCreditiUsati = $doc->createElement("creditiUsati" , $creditiUsati);
    $nuovaPrenotazione->appendChild($nuoviCreditiUsati);

    $nuovaListaPortate = $doc->createElement("listaPortate");
    $nuovaPrenotazione->appendChild($nuovaListaPortate);

    for($i=0 ; $i < count($portateScelte) ; $i++){
        $portata = $portateScelte[$i];
        $nuovaPortata = $doc->createElement("portata");
        $nuovaListaPortate->appendChild($nuovaPortata);

        $nuovoNome = $doc->createElement("nome" , $portata['descrizione']);
        $nuovaPortata->appendChild($nuovoNome);

        $nuovoPrezzo = $doc->createElement("prezzo", $portata['prezzo']);
        $nuovaPortata->appendChild($nuovoPrezzo);

        $nuovaQuantita =$doc->createElement("quantita" , $portata['quantita']);
        $nuovaPortata->appendChild($nuovaQuantita);
    }

    aggiungiCategoriaAdUtente($codFiscCliente , "Prenotazione servizio in camera");

    $doc->save("../XML/ServizioCamera.xml");
}

// Funzione per inserire una portata all'interno di una prenotazione già esistente

function aggiungiPortataPrenotazioneSC($idPrenotazione ,$nomePortata , $quantitaSelezionata){
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
    
    $portata = $xpathSC->query("/listaPrenotazioni/prenotazione[@id='$idPrenotazione']/listaPortate/portata[nome='$nomePortata']");
    if($portata->length == 1){
        $portata = $portata->item(0);
        $prezzoPortata = $portata->getElementsByTagName("prezzo")->item(0)->textContent;
        $vecchiaQuantita = $portata->getElementsByTagName("quantita")->item(0);

        $nuovaQuantita = $vecchiaQuantita->textContent + $quantitaSelezionata;

        $vecchiaQuantita->nodeValue="";
        $vecchiaQuantita->appendChild($doc->createTextNode($nuovaQuantita));
    }
    else{
        $prezzoPortata = getPrezzoPortata($nomePortata);        
        $listaPortate = $prenotazioneSC ->getElementsByTagName("listaPortate")->item(0);

        $nuovaPortata = $doc->createElement("portata");
        $listaPortate->appendChild($nuovaPortata);

        $nuovoNome = $doc->createElement("nome", $nomePortata);
        $nuovaPortata->appendChild($nuovoNome);

        $nuovoPrezzo = $doc->createElement("prezzo", $prezzoPortata);
        $nuovaPortata->appendChild($nuovoPrezzo);

        $nuovaQuantita = $doc->createElement("quantita", $quantitaSelezionata);
        $nuovaPortata->appendChild($nuovaQuantita);            
    }

    $vecchioPrezzoTot = $prenotazioneSC->getElementsByTagName("prezzoTotale")->item(0);
    $nuovoPrezzoTot = ($vecchioPrezzoTot->textContent) + ($prezzoPortata * $quantitaSelezionata);

    $vecchioPrezzoTot->nodeValue="";
    $vecchioPrezzoTot->appendChild($doc->createTextNode($nuovoPrezzoTot));

    $doc->save("../XML/ServizioCamera.xml");
}

//Funzione che aggiunge una nuova portata in Ristorante.xml

function aggiungiPortataAlMenu($tipo,$nome,$prezzo){
    $xmlString = "";
    foreach(file("../XML/Ristorante.xml") as $node ){
        $xmlString .= trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathRistorante = new DOMXPath($doc);

    
    $menu = $xpathRistorante->query("/ristoranti/ristorante/menu");
    $menu = $menu->item(0);

    
    $nuovaPortata= $doc->createElement("portata");
    $menu->appendChild($nuovaPortata);

    $nuovaTipologiaPortata = $doc->createElement("tipologia" , $tipo);
    $nuovaPortata->appendChild($nuovaTipologiaPortata);

    $nuovaDescrizione = $doc->createElement("descrizione" , $nome);
    $nuovaPortata->appendChild($nuovaDescrizione);

    $nuovoPrezzo = $doc->createElement("prezzo" , $prezzo);
    $nuovaPortata->appendChild($nuovoPrezzo);

    $doc->save("../XML/Ristorante.xml");
}

// Funzione per associare una o piu categorie ad un cliente 

function aggiungiCategoriaAdUtente($codFiscCliente , $tipoAzione){
    $xmlString = "";
    foreach(file("../XML/Categorie.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $listaCategorie = $doc->documentElement->childNodes;
    for($i=0 ; $i < $listaCategorie->length ; $i++){
        $categoria = $listaCategorie->item($i);
        $listaAzioni = $categoria->getElementsByTagName("azioneUtente");

        $azionePresente = "False";
        $j=0;
        while($j < $listaAzioni->length && $azionePresente == "False"){
            $azione = $listaAzioni->item($j);
            $nomeAzione = $azione->firstChild->textContent;
            if($nomeAzione == $tipoAzione){
                $azionePresente = "True";
            }
            else{
                $j++;
            }
        }
        
        if($azionePresente == "True"){
            $listaClienti = $categoria->getElementsByTagName("utente");
            
            $clientePresente = "False";
            $k=0;

            while($k < $listaClienti->length && $clientePresente == "False"){
                $cliente = $listaClienti->item($k);
                $codFisc = $cliente->firstChild->textContent;

                if($codFisc == $codFiscCliente){
                    $clientePresente = "True";
                }
                else{
                    $k++;
                }
            }

            if($clientePresente == "False"){
                $nodoListaUtenti = $categoria->getElementsByTagName("listaUtenti")->item(0);

                $nuovoNodoUtente = $doc->createElement("utente");
                $nodoListaUtenti->appendChild($nuovoNodoUtente);

                $nuovoNodoCodFisc = $doc->createElement("codFisc", $codFiscCliente);
                $nuovoNodoUtente->appendChild($nuovoNodoCodFisc);
            }
            
        }
    }

    $doc->save("../XML/Categorie.xml");


}

// Funzione per inserire una nuova domanda 

function aggiungiDomanda($codFiscCliente , $categoriaDomanda , $testoDomanda){
    $xmlString = "";
    foreach(file("../XML/Domande.xml") as $node){
        $xmlString .=trim($node);
    }
    $docDomande = new DOMDocument();
    $docDomande->loadXML($xmlString);
    $docDomande->formatOutput = true;
    
    $datiCliente = getDatiCliente($codFiscCliente);
    $xpathDomande = new DOMXPath($docDomande);

    $elemRadice = $docDomande->documentElement;
    $listaDomande = $docDomande->documentElement->childNodes;
    if(count($listaDomande) ==0){
        $idNuovaDomanda ="D1";
    }else{
        $numDomande= count($listaDomande)-1;
        $domanda=$xpathDomande->query("/listaDomande/domanda");

        $ultimaDomanda=$domanda->item($numDomande);
        
        $idUltimaDomanda = $ultimaDomanda->getAttribute("id");
        $nuovoNumero= substr($idUltimaDomanda,1,1);
        settype($nuovoNumero,"integer");
        $idNuovaDomanda ="D".($nuovoNumero+1);    
    }

    $nuovaDomanda = $docDomande->createElement("domanda");
    $nuovaDomanda->setAttribute("id" , $idNuovaDomanda);
    $elemRadice->appendChild($nuovaDomanda);

    $nuovoNomeAutore = $docDomande->createElement("nomeAutore" , $datiCliente['nome']);
    $nuovaDomanda->appendChild($nuovoNomeAutore);

    $nuovoCognomeAutore = $docDomande->createElement("cognomeAutore" , $datiCliente['cognome']);
    $nuovaDomanda->appendChild($nuovoCognomeAutore);

    $nuovoCodFisc = $docDomande->createElement("codFiscAutore" , $codFiscCliente);
    $nuovaDomanda->appendChild($nuovoCodFisc);
    
    $nuovaCategoria = $docDomande->createElement("categoria" , $categoriaDomanda );
    $nuovaDomanda->appendChild($nuovaCategoria);    

    $nuovoTestoDomanda = $docDomande->createElement("testoDomanda" , $testoDomanda);
    $nuovaDomanda->appendChild($nuovoTestoDomanda);    

    $nuovaListaRisposte = $docDomande->createElement("listaRisposte");
    $nuovaDomanda->appendChild($nuovaListaRisposte);

    $docDomande->save("../XML/Domande.xml");
}

// Funzione per inserire una risposta ad una domanda

function aggiungiRispostaDomanda($idDomanda , $autoreRisposta , $codFiscCliente , $testoRisposta){
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

    $nodoListaRisposte = $domanda->getElementsByTagName("listaRisposte")->item(0);
    $listaRisposte = $domanda->getElementsByTagName("risposta");
    $numRisposte = count($listaRisposte);

    $nuovaRisposta = $docDomande->createElement("risposta");
    $nodoListaRisposte->appendChild($nuovaRisposta);

    $nuovoIdRisposta = $docDomande->createElement("idRisposta", $idDomanda."-RSP".($numRisposte + 1));
    $nuovaRisposta->appendChild($nuovoIdRisposta);

    $nuovoAutore = $docDomande->createElement("autore" , $autoreRisposta);
    $nuovaRisposta->appendChild($nuovoAutore);

    if($autoreRisposta == "Cliente"){
        $datiCliente = getDatiCliente($codFiscCliente);

        $nuovoNomeAutoreRisposta = $docDomande->createElement("nomeAutoreRisposta" , $datiCliente['nome']);
        $nuovaRisposta->appendChild($nuovoNomeAutoreRisposta);

        $nuovoCognomeAutoreRisposta = $docDomande->createElement("cognomeAutoreRisposta" , $datiCliente['cognome']);
        $nuovaRisposta->appendChild($nuovoCognomeAutoreRisposta);

        $nuovoCodFiscAutoreRisposta = $docDomande->createElement("codFiscAutoreRisposta" , $codFiscCliente);
        $nuovaRisposta->appendChild($nuovoCodFiscAutoreRisposta);
    }
    
    $nuovoTestoRisposta = $docDomande->createElement("testoRisposta" , $testoRisposta);
    $nuovaRisposta->appendChild($nuovoTestoRisposta);

    $docDomande->save("../XML/Domande.xml");
}


//Funzione per aggiungere una faq 

function aggiungiFaq ($testoDomanda , $testoRisposta , $idDomandaCliente){
    $xmlString = "";
    foreach(file("../XML/FAQs.xml") as $node){
        $xmlString .=trim($node);
    }
    $docFaq = new DOMDocument();
    $docFaq->loadXML($xmlString);
    $docFaq->formatOutput = true;

    $elemRadice = $docFaq->documentElement;
    $listaFaq = $elemRadice->childNodes;

    $xpathFaq = new DOMXPath($docFaq);

    if(count($listaFaq)==0){
        $nuovoIdFaq="F1";
    }else{
        $numFaq=count($listaFaq)-1;
        $FAQ = $xpathFaq->query("/listaFAQ/FAQ");
        $ultimaFaq = $FAQ->item($numFaq);
    
        $idUltimaFaq=$ultimaFaq->getAttribute("id");
        $numeroId=substr($idUltimaFaq,1,1);
        settype($numeroId,"integer");
        $nuovoIdFaq="F".($numeroId+1);
    }


    $nuovaFaq = $docFaq->createElement("FAQ");
    $nuovaFaq->setAttribute("id" ,$nuovoIdFaq);
    $elemRadice->appendChild($nuovaFaq);


    if($idDomandaCliente != "null"){
        $nuovaIdDomandaCliente = $docFaq->createElement("idDomandaCliente", $idDomandaCliente);
        $nuovaFaq->appendChild($nuovaIdDomandaCliente);
    }

    $nuovaDomanda = $docFaq->createElement("testoDomanda", $testoDomanda);
    $nuovaFaq->appendChild($nuovaDomanda);

    $nuovaRisposta = $docFaq->createElement("testoRisposta" , $testoRisposta);
    $nuovaFaq->appendChild($nuovaRisposta);

    $docFaq->save("../XML/FAQs.xml");
}



// Funzione per inserire un nuova recensione 

function aggiungiRecensione($codFiscCliente , $categoriaScelta , $testoRecensione , $votoScelto){
    $xmlString = "";
    foreach(file("../XML/Recensioni.xml") as $node){
        $xmlString .= trim($node);
    }

    $docRecensioni = new DOMDocument();
    $docRecensioni->loadXML($xmlString);
    $docRecensioni->formatOutput = true;

    $elemRadice = $docRecensioni->documentElement;
    $ultimaRecensione = $elemRadice->lastChild;

    if(is_null($ultimaRecensione)){
        $idNuovaRecensione = "R1";
    }
    else{
        $idUltimaRecensione = $ultimaRecensione->getAttribute("id");        

        $nuovoNumero = substr($idUltimaRecensione , 1) + 1;
        $idNuovaRecensione = "R".$nuovoNumero;    
    }

    $nuovaRecensione = $docRecensioni->createElement("recensione");
    $nuovaRecensione->setAttribute("id" , $idNuovaRecensione);
    $elemRadice->appendChild($nuovaRecensione);

    $datiCliente = getDatiCliente($codFiscCliente);

    $nuovoNomeAutore = $docRecensioni->createElement("nomeAutore" , $datiCliente['nome']);
    $nuovaRecensione->appendChild($nuovoNomeAutore);

    $nuovoCognomeAutore = $docRecensioni->createElement("cognomeAutore" , $datiCliente['cognome']);
    $nuovaRecensione->appendChild($nuovoCognomeAutore);

    $nuovoCodFiscAutore = $docRecensioni->createElement("codFiscAutore" , $codFiscCliente);
    $nuovaRecensione->appendChild($nuovoCodFiscAutore);

    $nuovaCategoria = $docRecensioni->createElement("categoria" , $categoriaScelta);
    $nuovaRecensione->appendChild($nuovaCategoria);

    $nuovoTestoRecensione = $docRecensioni->createElement("testoRecensione", $testoRecensione);
    $nuovaRecensione->appendChild($nuovoTestoRecensione);

    $nuovoVoto = $docRecensioni->createElement("voto" , $votoScelto);
    $nuovaRecensione->appendChild($nuovoVoto);

    $nuovaUtilita = $docRecensioni->createElement("utilita" , 0);
    $nuovaRecensione->appendChild($nuovaUtilita);

    $nuovoAccordo = $docRecensioni->createElement("accordo" , 0);
    $nuovaRecensione->appendChild($nuovoAccordo);

    $docRecensioni->save("../XML/Recensioni.xml");
}

//Funzione per inserire un nuovo commento 

function aggiungiCommento($idRecensione , $codFiscCliente , $testoCommento){
    $xmlString = "";
    foreach(file("../XML/Commenti.xml") as $node){
        $xmlString .= trim($node);
    }

    $docCommenti = new DOMDocument();
    $docCommenti->loadXML($xmlString);
    $docCommenti->formatOutput = true;

    $elemRadice = $docCommenti->documentElement;
    $ultimoCommento = $elemRadice->lastChild;

    if(is_null($ultimoCommento)){
        $idNuovoCommento = "CM1";
    }
    else{
        $idUltimoCommento = $ultimoCommento->getAttribute("id");        

        $nuovoNumero = substr($idUltimoCommento , 2) + 1;
        $idNuovoCommento = "CM".$nuovoNumero;    
    }

    $nuovoCommento = $docCommenti->createElement("commento");
    $nuovoCommento->setAttribute("id" , $idNuovoCommento);
    $elemRadice->appendChild($nuovoCommento);

    $nuovoIdRecensione = $docCommenti->createElement("idRecensione" , $idRecensione);
    $nuovoCommento->appendChild($nuovoIdRecensione);

    $datiCliente = getDatiCliente($codFiscCliente);

    $nuovoNomeAutore = $docCommenti->createElement("nomeAutore" , $datiCliente['nome']);
    $nuovoCommento->appendChild($nuovoNomeAutore);

    $nuovoCognomeAutore = $docCommenti->createElement("cognomeAutore" , $datiCliente['cognome']);
    $nuovoCommento->appendChild($nuovoCognomeAutore);

    $nuovoCodFiscAutore = $docCommenti->createElement("codFiscAutore" , $codFiscCliente);
    $nuovoCommento->appendChild($nuovoCodFiscAutore);    

    $nuovoTestoCommento = $docCommenti->createElement("testo", $testoCommento);
    $nuovoCommento->appendChild($nuovoTestoCommento);

    $nuovaUtilita = $docCommenti->createElement("utilita" , 0);
    $nuovoCommento->appendChild($nuovaUtilita);

    $nuovoAccordo = $docCommenti->createElement("accordo" , 0);
    $nuovoCommento->appendChild($nuovoAccordo);

    $nuovaListaRisposte = $docCommenti->createElement("listaRisposte");
    $nuovoCommento->appendChild($nuovaListaRisposte);

    $docCommenti->save("../XML/Commenti.xml");

}

//Funzione per aggiungere una risposta ad un particolare commento

function aggiungiRispostaCommento($idCommento , $codFiscCliente , $testoRisposta){
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

    $nodoListaRisposte = $commento->getElementsByTagName("listaRisposte")->item(0);
    $ultimaRisposta = $nodoListaRisposte->lastChild;

    if(is_null($ultimaRisposta)){
        $idNuovaRisposta = $idCommento."-CMR1";
    }
    else{
        $idUltimaRisposta = $ultimaRisposta->firstChild->textContent;    

        $pieces = explode("-" , $idUltimaRisposta);

        $nuovoNumero = substr($pieces[1] , 3) + 1;
        $idNuovaRisposta = $idCommento."-CMR".$nuovoNumero;  
    }

    $nuovaRisposta = $docCommenti->createElement("risposta");    
    $nodoListaRisposte->appendChild($nuovaRisposta);

    $elemIdRisposta = $docCommenti->createElement("idRisposta" , $idNuovaRisposta);
    $nuovaRisposta->appendChild($elemIdRisposta);

    $datiCliente = getDatiCliente($codFiscCliente);

    $nuovoNomeAutore = $docCommenti->createElement("nomeAutoreRisposta" , $datiCliente['nome']);
    $nuovaRisposta->appendChild($nuovoNomeAutore);

    $nuovoCognomeAutore = $docCommenti->createElement("cognomeAutoreRisposta" , $datiCliente['cognome']);
    $nuovaRisposta->appendChild($nuovoCognomeAutore);

    $nuovoCodFiscAutore = $docCommenti->createElement("codFiscAutoreRisposta" , $codFiscCliente);
    $nuovaRisposta->appendChild($nuovoCodFiscAutore);    

    $nuovoTestoRisposta = $docCommenti->createElement("testoRisposta", $testoRisposta);
    $nuovaRisposta->appendChild($nuovoTestoRisposta);

    $nuovaUtilita = $docCommenti->createElement("utilitaRisposta" , 0);
    $nuovaRisposta->appendChild($nuovaUtilita);

    $nuovoAccordo = $docCommenti->createElement("accordoRisposta" , 0);
    $nuovaRisposta->appendChild($nuovoAccordo);   

    $docCommenti->save("../XML/Commenti.xml");
}


// Funzione per inserire una nuova valutazione 

function aggiungiValutazione($idOggetto, $tipoOggetto , $codFiscCliente , $votoUtilita , $votoAccordo){
    $xmlString = "";
    foreach(file("../XML/Valutazioni.xml") as $node){
        $xmlString .= trim($node);
    }

    $docValutazioni = new DOMDocument();
    $docValutazioni->loadXML($xmlString);
    $docValutazioni->formatOutput = true;

    $elemRadice = $docValutazioni->documentElement;

    $nuovaValutazione = $docValutazioni->createElement("valutazione");
    $elemRadice->appendChild($nuovaValutazione);

    $elemIdOggetto = $docValutazioni->createElement("idOggettoValutato", $idOggetto);
    $nuovaValutazione->appendChild($elemIdOggetto);

    $elemCodFisc = $docValutazioni->createElement("codFisc" , $codFiscCliente);
    $nuovaValutazione->appendChild($elemCodFisc);

    $elemUtilita = $docValutazioni->createElement("votoUtilita", $votoUtilita);
    $nuovaValutazione->appendChild($elemUtilita);

    $elemAccordo = $docValutazioni->createElement("votoAccordo", $votoAccordo);
    $nuovaValutazione->appendChild($elemAccordo);

    if($tipoOggetto == "Recensione"){
        modificaValutazioneRecensione($idOggetto , "utilita" , $votoUtilita);
        modificaValutazioneRecensione($idOggetto , "accordo" , $votoAccordo);
    }
    elseif($tipoOggetto == "Commento"){
        modificaValutazioneCommento($idOggetto , "utilita" , $votoUtilita);
        modificaValutazioneCommento($idOggetto , "accordo" , $votoAccordo);
    }
    else{
        modificaValutazioneRispostaCommento($idOggetto , "utilitaRisposta", $votoUtilita);
        modificaValutazioneRispostaCommento($idOggetto , "accordoRisposta" , $votoAccordo);
    }

    $docValutazioni->save("../XML/Valutazioni.xml");    
}



?>