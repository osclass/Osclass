<?php
	// ================================================
	// PHP Bild browser - iBrowser 
	// ================================================
	// iBrowser - language file: German
	// ================================================
	// Developed: baumedia.net
	// Copyright: baumedia.net
	// License: GPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.1                   Date: 09/08/2005
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
		'im_001' => 'Bild browser',
		'im_002' => 'iBrowser',
		'im_003' => 'Menu',
		'im_004' => 'Willkommen',
		'im_005' => 'Einf&uuml;gen',
		'im_006' => 'Abbrechen',
		'im_007' => 'Einf&uuml;gen',		
		'im_008' => 'Einf&uuml;gen/&auml;ndern',
		'im_009' => 'Attribute',
		'im_010' => 'Bild Attribute',
		'im_013' => 'Popup',
		'im_014' => 'Bild PopUp',
		'im_015' => '&Uuml;ber iBrowser',
		'im_016' => 'Abschnitt',
		'im_097' => 'Bitte warten...',
		'im_098' =>	'Abschnitt &ouml;ffnen',
		'im_099' => 'Abschnitt schlie&szlig;en',
		//-------------------------------------------------------------------------
		// Einf&uuml;gen/change screen - in	
		'in_001' => 'Einf&uuml;gen/Bild &auml;ndern',
		'in_002' => 'Ordner',
		'in_003' => 'Ordner ausw&auml;hlen',
		'in_004' => 'Bilder',
		'in_005' => 'Vorschau',
		'in_006' => 'Bild l&ouml;schen',
		'in_007' => 'F&uuml;r Gro&szlig;ansicht klicken',
		'in_008' => 'Bild hochladen, l&ouml;schen oder umbenennen',	
		'in_009' => 'Information',
		'in_010' => 'Popup',		
		'in_013' => 'Erstellt einen Link zu einem im PopUp ge&ouml;ffneten Bild',
		'in_014' => 'PopUp Link entfernen',	
		'in_015' => 'Datei',	
		'in_016' => 'Umbenennen',
		'in_017' => 'Bild umbenennen',
		'in_018' => 'Upload',
		'in_019' => 'Bild Upload',	
		'in_020' => 'Gr&ouml;&szlig;e',
		'in_021' => 'Gew&uuml;nschte Gr&ouml;&szlig;e',
		'in_022' => 'Original',
		'in_023' => 'Bild wird beschnitten',
		'in_024' => 'L&ouml;schen',
		'in_025' => 'Ordner',
		'in_026' => 'Ordner erstellen',
		'in_027' => 'Ordner erstellen',
		'in_028' => 'Breite',
		'in_029' => 'H&ouml;he',
		'in_030' => 'Typ',
		'in_031' => 'Gr&ouml;&szlig;e',
		'in_032' => 'Name',
		'in_033' => 'erstellt am',
		'in_034' => 'ge&auml;ndert am',
		'in_035' => 'Bild Info',
		'in_036' => 'Zum schlie&szlig;en auf das Bild klicken',
		'in_037' => 'Drehen',
		'in_038' => 'Automatisches Drehen: benutzt die exif Informationen der Kamera. +180&deg; oder -180&deg; f&uuml;r Querformat setzen, +90&deg; oder -90&deg; f&uuml;r Hochformat. Positive Werte drehen im Uhrzeigersinn, negative entgegen.',
		'in_041' => '',
		'in_042' => 'nichts',		
		'in_043' => 'Hochformat',
		'in_044' => '+ 90&deg;',	
		'in_045' => '- 90&deg;',
		'in_046' => 'Querformat',	
		'in_047' => '+ 180&deg;',	
		'in_048' => '- 180&deg;',
		'in_049' => 'Kamera',	
		'in_050' => 'EXIF Info',
		'in_051' => 'ACHTUNG: Das Bild ist ein dynamisch vom iManager generiertes Thumbnail - Einstellungen gehen bei Bildver&auml;nderungen verloren.',
		'in_052' => 'Bildansicht &auml;ndern',
		'in_053' => 'Zufall',
		'in_054' => 'Zuf&auml;lliges Bild einf&uuml;gen',
		'in_055' => 'Zuf&auml;lliges Bild einf&uuml;gen',
		'in_056' => 'Parameter',
		'in_057' => 'Standardeinstellungen herstellen',
		'in_099' => 'Standardeinstellungen',	
		//-------------------------------------------------------------------------
		// properties, attributes - at
		'at_001' => 'Bild Attribute',
		'at_002' => 'Quelle',
		'at_003' => 'Titel',
		'at_004' => 'Bildtitel - wird bei MouseOver angezeigt',
		'at_005' => 'Beschreibung',
		'at_006' => 'ALT-Tag des Bilds',
		'at_007' => 'Stil',
		'at_008' => 'Der eingestellte Stil muss im Stylesheet vorhanden sein.',
		'at_009' => 'CSS-Stil',	
		'at_010' => 'Attribute',
		'at_011' => 'Die \'align\', \'border\', \'hspace\', and \'vspace\' Eigenschaften werden vom XHTML 1.0 Strict DTD nicht unterst&uuml;tzt. Bitte die CSS-Stile statt dessen verwenden',
		'at_012' => 'Ausrichtung',	
		'at_013' => 'Standard',
		'at_014' => 'Links',
		'at_015' => 'Rechts',
		'at_016' => 'Oben',
		'at_017' => 'Mittig',
		'at_018' => 'Unten',
		'at_019' => 'Absolut mittig',
		'at_020' => 'Oben b&uuml;ndig',
		'at_021' => 'Grundlinie',		
		'at_022' => 'Gr&ouml;&szlig;e',
		'at_023' => 'Breite',
		'at_024' => 'H&ouml;he',
		'at_025' => 'Rahmen',
		'at_026' => 'V-Abstand',
		'at_027' => 'H-Abstand',
		'at_028' => 'Vorschau',	
		'at_029' => 'Sonderzeichen einf&uuml;gen',
		'at_030' => 'Sonderzeichen einf&uuml;gen',
		'at_031' => 'Bildgr&ouml;&szlig;e wiederherstellen',
		'at_032' => 'Bildbeschreibung',
		'at_033' => 'Bildbeschreibung anzeigen.',
		'at_034' => 'Bildbeschreibung anzeigen.',
		'at_099' => 'Standard',	
		//-------------------------------------------------------------------------		
		// error messages - er
		'er_001' => 'Fehler',
		'er_002' => 'Kein Bild ausgew&auml;hlt!',
		'er_003' => 'Breite ist keine Zahl',
		'er_004' => 'H&ouml;he ist keine Zahl',
		'er_005' => 'Rahmengr&ouml;&szlig;e ist keine Zahl',
		'er_006' => 'H-Abstand muss Zahl sein',
		'er_007' => 'V-Abstand muss Zahl sein',
		'er_008' => 'Mit "OK" l&ouml;schen best&auml;tigen',
		'er_009' => 'Umbenennen der Thumbnails ist nicht m&ouml;glich! Bitte nennen Sie das Hauptbild um, wenn Sie das Thumbnail umbenennen wollen',
		'er_010' => 'Mit "OK" Bild umbenennen!',
		'er_011' => 'Der neue Name ist entweder leer oder nicht ge&auml;ndert!',
		'er_014' => 'Bitte neuen Dateinamen eingeben!',
		'er_015' => 'Bitte g&uuml;ltigen Dateinamen eingeben!',
		'er_016' => 'Thumbnailing ist nicht aktiviert! Um Thumbnails zu erstellen, m&uuml;ssen Sie die Konfiguration anpassen.',
		'er_021' => 'Mit "OK" Bilder uploaden!',
		'er_022' => 'Bild wird hochgeladen - bitte warten...',
		'er_023' => 'Entweder kein Bild ausgew&auml;hlt oder Dateigr&ouml;&szlig;e wurde nicht erfasst.',
		'er_024' => 'Datei',
		'er_025' => 'Datei existiert - "OK" dr&uuml;cken zum &uuml;berschreiben',
		'er_026' => 'Neuen Dateinamen eingeben!',
		'er_027' => 'Ordner nicht vorhanden',
		'er_028' => 'Es ist ein Fehler w&auml;hrend des Uploadvorganges aufgetreten. Bitte versuchen Sie es noch einmal.',
		'er_029' => 'Falscher Dateityp',
		'er_030' => 'L&ouml;schen fehlgeschlagen - Bitte noch einmal versuchen.',
		'er_031' => '&Uuml;berschreiben',
		'er_032' => 'Bilder k&ouml;nnen nur in Gro&szlig;ansicht angezeigt werden, wenn Sie gr&ouml;&szlig;er als die Vorschau sind.',
		'er_033' => 'Umbenennen fehlgeschlagen - bitte noch einmal versuchen!',
		'er_034' => 'Ordner anlegen fehlgeschlagen - bitte noch einmal versuchen!',
		'er_035' => 'Vergr&ouml;&szlig;ern ist nicht erlaubt!',
		'er_036' => 'Fehler bei der Dateianzeige!',
	  ),	  
	  //-------------------------------------------------------------------------
	  // symbols
		'symbols'		=> array (
		'title' 		=> 'Symbole',
		'ok' 			=> 'OK',
		'cancel' 		=> 'Abbrechen',
	  ),	  
	)
?>
