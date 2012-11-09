<?php
	// ================================================
	// PHP image browser - iBrowser 
	// ================================================
	// iBrowser - language file: Dutch
	// ================================================
	// Developed: Ematic Interactive
	// Copyright: Ematic Interactive
	// License: GPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.0                   Date: 06/01/2005
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
		'im_001' => 'Afbeeldingen verkenner',
		'im_002' => 'iBrowser',
		'im_003' => 'Menu',
		'im_004' => 'Welkom',
		'im_005' => 'Invoegen',
		'im_006' => 'Annuleren',
		'im_007' => 'Invoegen',		
		'im_008' => 'Invoegen/ wijzigen',
		'im_009' => 'Eigenschappen',
		'im_010' => 'Afbeelding eigenschappen',
		'im_013' => 'Popup',
		'im_014' => 'Afbeelding popup',
		'im_015' => 'Over iBrowser',
		'im_016' => 'Sectie',
		'im_097' => 'Please wait while loading...',
		'im_098' =>	'Open sectie',
		'im_099' => 'Sluit sectie',
		//-------------------------------------------------------------------------
		// insert/change screen - in	
		'in_001' => 'Invoegen/Wijzigen afbeelding',
		'in_002' => 'Bibliotheek',
		'in_003' => 'Selecteer een afbeeldingen bibliotheek',
		'in_004' => 'Afbeeldingen',
		'in_005' => 'Voorbeeld',
		'in_006' => 'Verwijder afbeelding',
		'in_007' => 'Klik voor een grotere weergave van de afbeelding',
		'in_008' => 'Open de afbeelding uploaden, hernoemen of verwijder sectie',	
		'in_009' => 'Informatie',
		'in_010' => 'Popup',		
		'in_013' => 'Creeer een link naar een afbeelding die geopend moet worden in een nieuw venster.',
		'in_014' => 'Verwijder popup link',	
		'in_015' => 'Bestand',	
		'in_016' => 'Hernoemen',
		'in_017' => 'Hernoem afbeelding',
		'in_018' => 'Upload',
		'in_019' => 'Upload afbeelding',	
		'in_020' => 'Grootte(n)',
		'in_021' => 'Controleer de gewenste afmetingen die aangemaakt moeten worden tijdens het uploaden van de afbeelding(en)',
		'in_022' => 'Origineel',
		'in_023' => 'Afbeelding wordt gecropped',
		'in_024' => 'Verwijder',
		'in_025' => 'Map',
		'in_026' => 'Klik om een map aan te maken',
		'in_027' => 'Maak een map',
		'in_028' => 'Breedte',
		'in_029' => 'Hoogte',
		'in_030' => 'Type',
		'in_031' => 'Grootte',
		'in_032' => 'Naam',
		'in_033' => 'Aangemaakt',
		'in_034' => 'Gewijzigd',
		'in_035' => 'Afbeelding info',
		'in_036' => 'Klik op de afbeelding om het venster te sluiten',
		'in_037' => 'Roteren',
		'in_038' => 'Auto roteren: zet op exif info, om EXIF orientatie van de camera te gebruiken. Kan ook op +180&deg; of -180&deg; worden gezet voor landschap, of op +90&deg; of -90&deg; voor portret. Positieve waarden voor met de klok mee en negatieve waarden voor tegen de klok in roteren.',
		'in_041' => '',
		'in_042' => 'geen',
		'in_043' => 'portret',
		'in_044' => '+ 90&deg;',
		'in_045' => '- 90&deg;',
		'in_046' => 'landschap',
		'in_047' => '+ 180&deg;',
		'in_048' => '- 180&deg;',
		'in_049' => 'camera',
		'in_050' => 'exif info',		
		'in_051' => 'WAARSCHUWING: Huidige afbeelding is een dynamische thumbnail gecreëerd door iManager - parameters zullen verloren gaan bij het wijzigen van de afbeelding.',
		'in_052' => 'Switch image selection view',
		'in_099' => 'standaard',	
		//-------------------------------------------------------------------------
		// properties, attributes - at
		'at_001' => 'Afbeelding attributen',
		'at_002' => 'Bron',
		'at_003' => 'Titel',
		'at_004' => 'TITEL - geeft de afbeelding mouseover weer',
		'at_005' => 'Omschrijving',
		'at_006' => 'ALT -  textuele vervanging voor de afbeelding, welke wordt getoond ipv de afbeelding',
		'at_007' => 'Stijl',
		'at_008' => 'Let op dat de geselecteerde stijl voorkomt in uw stylesheet!',
		'at_009' => 'CSS-stijl',	
		'at_010' => 'Attributen',
		'at_011' => 'De \'align\', \'border\', \'hspace\', and \'vspace\' attributen van het afbeeldings element worden niet ondersteund in XHTML 1.0 Strict DTD. Gebruik hiervoor in de plaats CSS-stijl.',
		'at_012' => 'Uitlijnen',	
		'at_013' => 'standaard',
		'at_014' => 'links',
		'at_015' => 'rechts',
		'at_016' => 'boven',
		'at_017' => 'midden',
		'at_018' => 'onder',
		'at_019' => 'abs midden',
		'at_020' => 'Tekst bovenkant',
		'at_021' => 'basis',		
		'at_022' => 'Afmetingen',
		'at_023' => 'Breedte',
		'at_024' => 'Hoogte',
		'at_025' => 'Rand',
		'at_026' => 'V-space',
		'at_027' => 'H-space',
		'at_028' => 'Voorbeeld',	
		'at_029' => 'Klik om een speciaal karakter in het titel veld in te voegen',
		'at_030' => 'Klik om een speciaal karakter in het omschrijving veld in te voegen',
		'at_031' => 'Herstel naar de originele afbeeldings dimensies',
		'at_099' => 'standaard',	
		//-------------------------------------------------------------------------		
		// error messages - er
		'er_001' => 'Fout',
		'er_002' => 'Geen afbeelding geselecteerd!',
		'er_003' => 'Breedte is geen getal',
		'er_004' => 'Hoogte is geen getal',
		'er_005' => 'Rand is geen getal',
		'er_006' => 'Horizontale spatiering is geen getal',
		'er_007' => 'Vertikale spatiering is geen getal',
		'er_008' => 'Klik op OK om de afbeelding te verwijderen',
		'er_009' => 'Hernoemen van thumbnails is niet toegestaan! Hernoem de originele afbeelding om de thumbnail te hernoemen.',
		'er_010' => 'Klik op OK om de afbeelding te hernoemen naar',
		'er_011' => 'De nieuwe naam is of leeg of is niet gewijzigd!',
		'er_014' => 'Geef een nieuwe bestandsnaam op!',
		'er_015' => 'Geef een geldige bestandsnaam op!',
		'er_016' => 'Creeren van thumbnails is niet aanwezig! Zet de thumbnail afmetingen in het configuratie bestand file om het aanmaken van thumbnails te activeren.',
		'er_021' => 'Klik op OK om de afbeelding(en) te uploaden.',
		'er_022' => 'Afbeelding uploaden - even geduld...',
		'er_023' => 'Er is geen afbeelding geselecteerd of er is geen afmetingen aangevinkt.',
		'er_024' => 'Bestand',
		'er_025' => 'bestaat reeds! Klik op OK om het bestand te overschrijven...',
		'er_026' => 'Geef een nieuwe bestandsnaam!',
		'er_027' => 'Folder bestaat niet fysiek',
		'er_028' => 'Er is een fout opgetreden tijdens het uploaden. Probeer het opnieuw.',
		'er_029' => 'Verkeerde afbeeldings bestandstype',
		'er_030' => 'Verwijderen is niet gelukt! Probeer het opnieuw.',
		'er_031' => 'Overschrijven',
		'er_032' => 'Volledige afmetingen voorbeeldweergave werkt alleen met afbeeldingen die groter zijn dan de preview afmetingen',
		'er_033' => 'Hernoemen van bestand is niet gelukt! Probeer het opnieuw.',
		'er_034' => 'Folder aanmaken is niet gelukt! Probeer het opnieuw.',
		'er_035' => 'Vergroten is niet toegestaan!',
		'er_036' => 'Fout tijdens het bouwen van de afbeeldingenlijst!',
	  ),	  
	  //-------------------------------------------------------------------------
	  // symbols
		'symbols'		=> array (
		'title' 		=> 'Symbolen',
		'ok' 			=> 'OK',
		'cancel' 		=> 'Annuleren',
	  ),	  
	)
?>