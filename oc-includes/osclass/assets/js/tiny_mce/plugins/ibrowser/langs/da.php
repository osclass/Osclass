<?php
	// ================================================
	// PHP image browser - iBrowser
	// ================================================
	// iBrowser - language file: English
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: GPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.1                   Date: 05/25/2005
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
		'im_001' => 'Billed browser',
		'im_002' => 'iBrowser',
		'im_003' => 'Menu',
		'im_004' => 'Velkommen',
		'im_005' => 'Indsæt',
		'im_006' => 'Afbryd',
		'im_007' => 'Indsæt',
		'im_008' => 'Indsæt/rediger',
		'im_009' => 'Egenskaber',
		'im_010' => 'Billed egenskaber',
		'im_013' => 'Popup',
		'im_014' => 'Billed popup',
		'im_015' => 'Om iBrowser',
		'im_016' => 'Afsnit',
		'im_097' => 'Please wait while loading...',
		'im_098' => 'Åben afsnit',
		'im_099' => 'Luk afsnit',
		//-------------------------------------------------------------------------
		// insert/change screen - in
		'in_001' => 'Indsæt/rediger billede',
		'in_002' => 'Mapper',
		'in_003' => 'Vælg en billed mappe',
		'in_004' => 'Billeder',
		'in_005' => 'Preview',
		'in_006' => 'Slet billede',
		'in_007' => 'Klik for større billede',
		'in_008' => 'Åben billed upload, omdøb, eller slet sektion',
		'in_009' => 'Information',
		'in_010' => 'Popup',
		'in_013' => 'Opret et link således billedet åbnes i et ny vindue.',
		'in_014' => 'Fjern popup link',
		'in_015' => 'File',
		'in_016' => 'Omdøb',
		'in_017' => 'Omdøb billede',
		'in_018' => 'Upload',
		'in_019' => 'Upload billede',
		'in_020' => 'Størrelse(r)',
		'in_021' => 'Marker den ønskede størrelse(r) som billed(er) skal uploadesi',
		'in_022' => 'Original',
		'in_023' => 'Billedet vil blive beskåret',
		'in_024' => 'Slet',
		'in_025' => 'Folder',
		'in_026' => 'Klik for at oprette en ny mappe',
		'in_027' => 'Opret en mappe',
		'in_028' => 'Brede',
		'in_029' => 'Højde',
		'in_030' => 'Type',
		'in_031' => 'Størrelse',
		'in_032' => 'Navn',
		'in_033' => 'Oprettet',
		'in_034' => 'Ændret',
		'in_035' => 'Billed info',
		'in_036' => 'Klik på billedet for at lukke vinduet',
		'in_037' => 'Roter',
		'in_038' => 'Auto roter: sat til exif info, for at bruge EXIF orientering 
gemt af kamera. Kan også sættes til +180&grader; eller -180&grader; for 
landskab, eller +90&grader; eller -90&grader; for portræt. Positive værdier 
for med-uret og negative værdier for mod-uret.',
		'in_041' => '',
		'in_042' => 'intet',
		'in_043' => 'portræt',
		'in_044' => '+ 90&grader;',
		'in_045' => '- 90&grader;',
		'in_046' => 'landskab',
		'in_047' => '+ 180&grader;',
		'in_048' => '- 180&grader;',
		'in_049' => 'Kamera',
		'in_050' => 'exif info',
		'in_051' => 'ADVARSEL: Følgende billede er et dynamisk thumbnail oprettet 
af iManager - parameterne vil gå tabet hvis billedet ændres.',
		'in_052' => 'Switch image selection view',
		'in_099' => 'grundindstilling',
		//-------------------------------------------------------------------------
		// properties, attributes - at
		'at_001' => 'Billed egenskaber',
		'at_002' => 'Kilde',
		'at_003' => 'Titel',
		'at_004' => 'TITEL - Viser billed beskrivelse ved mouse-over',
		'at_005' => 'Beskrivelse',
		'at_006' => 'ALT -  tekst udskiftning for billede, til fremvisning eller 
istedet for billedet',
		'at_007' => 'Style',
		'at_008' => 'Check venligst at den valgte style findes i dit stylesheet!',
		'at_009' => 'CSS-style',
		'at_010' => 'Indstillinger',
		'at_011' => 'På række\', \'Border\', \'H-afstand\', og \'V-afstand\' 
indstillinger for billede er ikke understøttet i XHTML 1.0 Kun DTD. Brug 
CSS-style istedet for.',
		'at_012' => 'På række',
		'at_013' => 'Grundindstilling',
		'at_014' => 'Venstre',
		'at_015' => 'Højre',
		'at_016' => 'Top',
		'at_017' => 'Midt i',
		'at_018' => 'Bund',
		'at_019' => 'absolut-midt',
		'at_020' => 'tekst-top',
		'at_021' => 'grundlinie',
		'at_022' => 'Størrelse',
		'at_023' => 'Brede',
		'at_024' => 'Højde',
		'at_025' => 'Border',
		'at_026' => 'V-afstand',
		'at_027' => 'H-afstand',
		'at_028' => 'Preview',
		'at_029' => 'Klik for at indsætte speciale karaktere ind i titel feltet',
		'at_030' => 'Klik for at indsætte speciale karaktere ind i beskrivelses 
feltet',
		'at_031' => 'Nulstil billed dimensioner til forud valgte værdier',
		'at_099' => 'Grundindstillingt',
		//-------------------------------------------------------------------------
		// error messages - er
		'er_001' => 'Fejl',
		'er_002' => 'Der er ikke valgt noget billede !',
		'er_003' => 'Brede er ikke et tal',
		'er_004' => 'Højde er ikke et tal',
		'er_005' => 'Border er ikke et tal',
		'er_006' => 'Horisontal afstand er ikke et tal',
		'er_007' => 'Vertikal afstand er ikke et tal',
		'er_008' => 'Klik OK for at slette billedet',
		'er_009' => 'Omdøbning af Miniature er ikke tilladt! Omdøb original billedet, hvis du vil have nyt navn for Miniature billedet.',
		'er_010' => 'Klik OK for at omdøbe billedet til',
		'er_011' => 'Nyt navn er enten tomt eller ikke ændret!',
		'er_014' => 'Indsæt nyt file navn!',
		'er_015' => 'Indsæt venligst korrekt navn!',
		'er_016' => 'Miniature er ikke tilgængelig! Sæt Miniature størrelsen i config filen.',
		'er_021' => 'Klik OK for at uploade billede(r) .',
		'er_022' => 'Uploader billede - Vent venligst...',
		'er_023' => 'Der er ikke valgt noget billede eller file størrelsen er markeret.',
		'er_024' => 'File',
		'er_025' => 'Eksistere allerede! Klik OK for at overskrive filen...',
		'er_026' => 'Indsæt nyt file navn!',
		'er_027' => 'Mappen eksistere ikke',
		'er_028' => 'Der opstod en fejl under oploadning af filen. Prøv igen.',
		'er_029' => 'Forkert billed file type',
		'er_030' => 'Fejl ved sletning! Prøv igen.',
		'er_031' => 'Overskriv',
		'er_032' => 'Fuld størrelse preview virker kun for billeder større end preview størrelsen',
		'er_033' => 'Fejl ved omdøbning af file! Prøv igen.',
		'er_034' => 'Fejl ved oprettelse af mappe! Prøv igen.',
		'er_035' => 'Forstørrelse er ikke tilladt!',
		'er_036' => 'Fejl ved opbygning af billed liste!',
	  ),
	  
//-------------------------------------------------------------------------
	  // symbols
		'symbols'		=> array (
		'title' 		=> 'Symbols',
		'ok' 			=> 'OK',
		'cancel' 		=> 'Fortryd',
	  ),
	)
?>
