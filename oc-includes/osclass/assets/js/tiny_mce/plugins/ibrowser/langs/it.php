<?php
	// ================================================
	// PHP image browser - iBrowser 
	// ================================================
	// iBrowser - language file: Italian
	// ================================================
	// Developed: Luca Aru
	// Copyright: Luca Aru
	// License: GPL - see license.txt
	// (c)2006 All rights reserved.
	// ================================================
	// Revision: 1.0                 Date: 31/01/2006
	// ================================================
	
	//-------------------------------------------------------------------------
	// charset to be used in dialogs
	$lang_charset = 'iso-8859-1';
	// text direction for the current language to be used in dialogs
	$lang_direction = 'ltr';
	//-------------------------------------------------------------------------
	
	// language text data array
	// first dimension - block, second - exact phrase
	//-------------------------------------------------------------------------
	// iBrowser
	$lang_data = array (  
		'ibrowser' => array (
		//-------------------------------------------------------------------------
		// common - im
		'im_001' => 'Image browser',
		'im_002' => 'iBrowser',
		'im_003' => 'Menu',
		'im_004' => 'Benvenuto',
		'im_005' => 'Inserisci',
		'im_006' => 'Elimina',
		'im_007' => 'Inserisci',		
		'im_008' => 'Inserisci/cambia',
		'im_009' => 'Propriet&agrave;',
		'im_010' => 'Propriet&agrave; dell&#39;immagine',
		'im_013' => 'Popup',
		'im_014' => 'Immagine popup',
		'im_015' => 'Informazioni su iBrowser',
		'im_016' => 'Sezione',
		'im_097' => 'Caricamento in corso, attendere...',
		'im_098' =>	'Apri sezione',
		'im_099' => 'Chiudi sezione',
		//-------------------------------------------------------------------------
		// insert/change screen - in	
		'in_001' => 'Inserisci/Cambia immagine',
		'in_002' => 'Libreria',
		'in_003' => 'Scegli una libreria di immagini',
		'in_004' => 'Immagini',
		'in_005' => 'Anteprima',
		'in_006' => 'Elimina immagini',
		'in_007' => 'Clicca per ingrandire l&#39;immagine',
		'in_008' => 'Apri la sezione di caricamento, eliminazione e modifica del nome dell&#39;immagine',	
		'in_009' => 'Informazione',
		'in_010' => 'Popup',		
		'in_013' => 'Crea un link a un&#39;immagine che sar&agrave; aperto in una nuova finestra.',
		'in_014' => 'rimuovi il link popup',	
		'in_015' => 'File',	
		'in_016' => 'Rinomina',
		'in_017' => 'Rinomina immagine',
		'in_018' => 'Carica',
		'in_019' => 'Carica immagine',	
		'in_020' => 'Dimensione/i',
		'in_021' => 'Seleziona le dimensioni che verranno create mentre carichi le immmagini',
		'in_022' => 'Originale',
		'in_023' => 'L&#39;immagine verr&agrave; troncata',
		'in_024' => 'Elimina',
		'in_025' => 'Directory',
		'in_026' => 'Clicca per creare una directory',
		'in_027' => 'Crea una directory',
		'in_028' => 'Larghezza',
		'in_029' => 'Altezza',
		'in_030' => 'Tipo',
		'in_031' => 'Dimensione',
		'in_032' => 'Nome',
		'in_033' => 'Creata',
		'in_034' => 'Modificata',
		'in_035' => 'Informazioni sull&#39;immagine',
		'in_036' => 'Clicca sull&#39;immagine per chiudere la finestra',
		'in_037' => 'Ruota',
		'in_038' => 'Ruota automaticamente: imposta su &#39;exif info&#39; per utilizzare l&#39;orientazione EXIF memorizzata dalla fotocamera. Puoi anche impostare +180&deg; o -180&deg; per foto panoramica, o +90&deg; o -90&deg; per ritratto (Valori positivi ruotano l&#39;immagine in senso orario, valori negativi in senso antiorario).',
		'in_041' => '',
		'in_042' => 'nessuna',		
		'in_043' => 'ritratto',
		'in_044' => '+ 90&deg;',	
		'in_045' => '- 90&deg;',
		'in_046' => 'panorama',	
		'in_047' => '+ 180&deg;',	
		'in_048' => '- 180&deg;',
		'in_049' => 'camera',	
		'in_050' => 'info exif',
		'in_051' => 'ATTENZIONE: L&#39;immagine corrente &egrave; una miniatura creata da iManager - i parametri saranno persi al cambio di immagine.',
		'in_052' => 'Clicca per passare alla &#39;vista immagine&#39;',
		'in_053' => 'Casuale',
		'in_054' => 'Se spuntato, verr&agrave inserita un&#39;immagine casuale',
		'in_055' => 'Spunta per inserire un&#39;immagine casuale',
		'in_056' => 'Parametri',
		'in_057' => 'clicca per reimpostare i parametri ai valori di default',
		'in_099' => 'default',	
		//-------------------------------------------------------------------------
		// properties, attributes - at
		'at_001' => 'Attributi dell&#39;immagine',
		'at_002' => 'Sorgente',
		'at_003' => 'Titolo',
		'at_004' => 'TITLE - mostra una descrizione dell&#39;immagine al passaggio del mouse',
		'at_005' => 'Descrizione',
		'at_006' => 'ALT -  Alternativa testuale per l&#39;immagine,sar&agrave; mostrata o utilizzata al posto dell&#39;immagine',
		'at_007' => 'Stile',
		'at_008' => 'Verifica che lo stile scelto esista nel tuo foglio di stile!',
		'at_009' => 'CSS-stile',	
		'at_010' => 'Attributi',
		'at_011' => 'Gli attributi &#39;align&#39;, &#39;border&#39;, &#39;hspace&#39;, and &#39;vspace&#39; dell&#39;elemento non sono supportati in XHTML 1.0 Strict DTD. Usa i CSS al posto di questi attributi',
		'at_012' => 'Allineamento',	
		'at_013' => 'default',
		'at_014' => 'sinistra',
		'at_015' => 'destra',
		'at_016' => 'alto',
		'at_017' => 'mezzo',
		'at_018' => 'basso',
		'at_019' => 'absmiddle',
		'at_020' => 'texttop',
		'at_021' => 'baseline',		
		'at_022' => 'Dimensione',
		'at_023' => 'Larghezza',
		'at_024' => 'Altezza',
		'at_025' => 'Bordo',
		'at_026' => 'V-space',
		'at_027' => 'H-space',
		'at_028' => 'Anteprima',	
		'at_029' => 'Clicca per inserire un carattere speciale nel campo title',
		'at_030' => 'Clicca per inserire un carattere speciale nel campo descrizione',
		'at_031' => 'Riporta le dimensioni dell&#39;immagine ai valori di default',
		'at_032' => 'Didascalia',
		'at_033' => 'selezionato: seleziona la didascalia per l&#39;immagine&#39; / non selezionato: nessuna didascalia / rimuove la didascalia per l&#39;immagine',
		'at_034' => ' didascalia dell&#39;immagine',
		'at_099' => 'default',	
		//-------------------------------------------------------------------------		
		// error messages - er
		'er_001' => 'Errore',
		'er_002' => 'Nessuna immagine selezionata!',
		'er_003' => 'Inserire un valore numerico per la larghezza!',
		'er_004' => 'Inserire un valore numerico per l&#39;altezza!',
		'er_005' => 'Inserire un valore numerico per il bordo!',
		'er_006' => 'Inserire un valore numerico per H-space',
		'er_007' => 'Inserire un valore numerico per V-Space',
		'er_008' => 'Clicca su OK per cancellare l&#39;immagine',
		'er_009' => 'Non &grave;  possibile rinominare le miniature. Rinomina le immagini originali se vuoi rinominare le miniature.',
		'er_010' => 'Clicca su OK per rinominare l&#39;immagine:',
		'er_011' => 'Il nuovo nome &egrave vuoto oppure non &egrave; stato cambiato!',
		'er_014' => 'Inserisci un nuovo nome per il file!',
		'er_015' => 'Inserisci un nome di file valido!',
		'er_016' => 'Miniature non disponibili! Indica la grandezza delle miniature nel file di configurazione prima di abilitare la creazione delle miniature.',
		'er_021' => 'Clicca OK per caricare le immagini.',
		'er_022' => 'Caricamento immagine in corso - attendere prego...',
		'er_023' => 'Non &grave; selezionata nessuna immagine oppure non &grave; stata indicata la dimensione del file.',
		'er_024' => 'File',
		'er_025' => 'esiste gi&agrave;! Clicca su OK per sovrascrivere il file...',
		'er_026' => 'Inserisci un nuovo nome per il file!',
		'er_027' => 'La directory non esiste',
		'er_028' => '&Egrave; avvenuto un errore durante il caricamento del file. Riprova.',
		'er_029' => 'Tipo di file immagine non valido',
		'er_030' => 'Eliminazione non riuscita! Riprova.',
		'er_031' => 'Sovrascrivi',
		'er_032' => 'L&#39;anteprima a grandezza piena funziona solo per immagini pi&ugrave; grandi dell&#39;immagine di anteprima',
		'er_033' => 'File non rinominato correttamente! Riprova.',
		'er_034' => 'Creazione della directory non riuscita! Riprova.',
		'er_035' => 'Ingrandimento dell&#39;immagine non consentito!',
		'er_036' => 'Errore nella creazione della lista delle immagini!',
	  ),	  
	  //-------------------------------------------------------------------------
	  // symbols
		'symbols'		=> array (
		'title' 		=> 'Simboli',
		'ok' 			=> 'OK',
		'cancel' 		=> 'Elimina',
	  ),	  
	)
?>
