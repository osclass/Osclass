<?php
	// ================================================
	// PHP image browser - iBrowser 
	// ================================================
	// iBrowser - language file: Czech
	// Translated by Tomas Vaverka (Pche)
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: GPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.1                   Date: 17/02/2006
	// ================================================
	
	//-------------------------------------------------------------------------
	// charset to be used in dialogs
	// pouzita znakova sada
	$lang_charset = 'utf-8';
	// text direction for the current language to be used in dialogs
	// smer textu v danem jazyce
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
		'im_004' => 'V&#237;tejte',
		'im_005' => 'Vlo&#382;it',
		'im_006' => 'Storno',
		'im_007' => 'Vlo&#382;it',		
		'im_008' => 'Vlo&#382;it/Zm&#283;nit',
		'im_009' => 'Vlastnosti',
		'im_010' => 'Vlastnosti obr&#225;zku',
		'im_013' => 'Vyskakovac&#237; okna',
		'im_014' => 'Obr&#225;zek ve vyskakovac&#237;m okn&#283;',
		'im_015' => 'O programu',
		'im_016' => 'Sekce',
		'im_097' => 'Chvilku strpen&#237;, nahr&#225;v&#225;m...',
		'im_098' =>	'Otev&#345;&#237;t sekci',
		'im_099' => 'Zav&#345;&#237;t sekci',
		//-------------------------------------------------------------------------
		// insert/change screen - in	
		'in_001' => 'Vlo&#382;it/Zm&#283;nit obr&#225;zek',
		'in_002' => 'Knihovna',
		'in_003' => 'Vyberte knihovnu obr&#225;zk&#367;',
		'in_004' => 'Obr&#225;zky',
		'in_005' => 'N&#225;hled',
		'in_006' => 'Smazat obr&#225;zek',
		'in_007' => 'Klikn&#283;te pro zv&#283;t&#353;en&#237; obr&#225;zku',
		'in_008' => 'Otev&#345;&#237;t upload obr&#225;zku, p&#345;ejmenov&#225;n&#237;, nebo smaz&#225;n&#237; sekce',	
		'in_009' => 'Informace',
		'in_010' => 'Vyskakovac&#237; okno',		
		'in_013' => 'Vytvo&#345;en&#237; odkazu na obr&#225;zek otv&#237;ran&#253; v nov&#233;m okn&#283;.',
		'in_014' => 'Odstranit odkaz na vyskakovac&#237; okno',	
		'in_015' => 'Soubor',	
		'in_016' => 'P&#345;ejmenovat',
		'in_017' => 'P&#345;ejmenovat obr&#225;zek',
		'in_018' => 'Upload',
		'in_019' => 'Uploadovat obr&#225;zek',	
		'in_020' => 'Velikost(i)',
		'in_021' => 'Za&#353;krtn&#283;te po&#382;adovan&#233; velikosti pro upload obr&#225;zk&#367;',
		'in_022' => 'Origin&#225;l',
		'in_023' => 'Obr&#225;zek bude o&#345;&#237;znut',
		'in_024' => 'Smazat',
		'in_025' => 'Adres&#225;&#345;',
		'in_026' => 'Klikn&#283;te pro vytvo&#345;en&#237; adres&#225;&#345;e',
		'in_027' => 'Vytvo&#345;it adres&#225;&#345;',
		'in_028' => '&#352;&#237;&#345;ka',
		'in_029' => 'V&#253;&#353;ka',
		'in_030' => 'Typ',
		'in_031' => 'Velikost',
		'in_032' => 'Jm&#233;no',
		'in_033' => 'Vytvo&#345;eno',
		'in_034' => 'Zm&#283;n&#283;no',
		'in_035' => 'Informace o obr&#225;zku',
		'in_036' => 'Klikn&#283;te na obr&#225;zek pro zav&#345;en&#237; okna',
		'in_037' => 'Oto&#269;it',
		'in_038' => 'Automatick&#233; oto&#269;en&#237;: nastavit na EXIF informace, pro pou&#382;it&#237; EXIF orientace ulo&#382;en&#233; fotoapar&#225;tem. Tak&#233; m&#367;&#382;e b&#253;t nastaveno na +180&deg; nebo -180&deg; pro obr&#225;zek na &#353;&#237;&#345;ku, nebo +90&deg; nebo -90&deg; pro obr&#225;zek na v&#253;&#353;ku. Kladn&#233; hodnoty pro posun ve sm&#283;ru hodinov&#253;ch ru&#269;i&#269;ek, z&#225;porn&#233; proti sm&#283;ru.',
		'in_041' => '',
		'in_042' => '&#382;&#225;dn&#253;',		
		'in_043' => 'na v&#253;&#353;ku',
		'in_044' => '+ 90&deg;',	
		'in_045' => '- 90&deg;',
		'in_046' => 'na &#353;&#237;&#345;ku',	
		'in_047' => '+ 180&deg;',	
		'in_048' => '- 180&deg;',
		'in_049' => 'fotoapar&#225;t',	
		'in_050' => 'exif informace',
		'in_051' => 'POZOR: Tento obr&#225;zek je dynamick&#253; n&#225;hled vytvo&#345;en&#253; iManagerem - parametry budou ztraceny p&#345;i zm&#283;n&#283; obr&#225;zku.',
		'in_052' => 'Klikn&#283;te pro zm&#283;nu n&#225;hledu vybran&#233;ho obr&#225;zku',
		'in_053' => 'N&#225;hodn&#283;',
		'in_054' => 'Je-li za&#353;krtnuto, bude vybr&#225;n n&#225;hodn&#253; obr&#225;zek',
		'in_055' => 'Za&#353;krtn&#283;te pro vlo&#382;en&#237; n&#225;hodn&#233;ho obr&#225;zku',
		'in_056' => 'Parametry',
		'in_057' => 'klikn&#283;te pro nastaven&#237; parametr&#367; na v&#253;choz&#237;',
		'in_099' => 'v&#253;choz&#237;',	
		//-------------------------------------------------------------------------
		// properties, attributes - at
		'at_001' => 'Vlastnosti obr&#225;zku',
		'at_002' => 'Zdroj',
		'at_003' => 'Titulek',
		'at_004' => 'TITLE - titulek obr&#225;zku, zobraz&#237; se po p&#345;ejet&#237; my&#353;&#237; nad obr&#225;zek',
		'at_005' => 'Popis',
		'at_006' => 'ALT -  alternativn&#237; text obr&#225;zku, zobraz&#237; se p&#345;i nena&#269;ten&#237; obr&#225;zku',
		'at_007' => 'Styl',
		'at_008' => 'Ujist&#283;te se, &#382;e zadan&#253; styl existuje ve va&#353;i definici styl&#367;.',
		'at_009' => 'CSS-styl',	
		'at_010' => 'Atributy',
		'at_011' => 'Atributy \'align\', \'border\', \'hspace\', and \'vspace\' elementu IMAGE nejsou podporov&#225;ny v XHTML 1.0 Strict DTD. Pou&#382;ijte m&#237;sto toho CSS styly.',
		'at_012' => 'Zarovn&#225;n&#237;',	
		'at_013' => 'v&#253;choz&#237;',
		'at_014' => 'vlevo',
		'at_015' => 'vpravo',
		'at_016' => 'nahoru',
		'at_017' => 'doprost&#345;ed',
		'at_018' => 'dol&#367;',
		'at_019' => 'st&#345;ed obr&#225;zku zarovnan&#253; se st&#345;edem textu',
		'at_020' => 'vr&#353;ek obr&#225;zku zarovnan&#253; s vr&#353;kem textu',
		'at_021' => '&#250;&#269;a&#345;&#237;',		
		'at_022' => 'Velikost',
		'at_023' => '&#352;&#237;&#345;ka',
		'at_024' => 'V&#253;&#353;ka',
		'at_025' => 'R&#225;me&#269;ek',
		'at_026' => 'V-odsazen&#237;',
		'at_027' => 'H-odsazen&#237;',
		'at_028' => 'N&#225;hled',	
		'at_029' => 'Klikn&#283;te pro vlo&#382;en&#237; speci&#225;ln&#237;ch znak&#367; do pole titulku',
		'at_030' => 'Klikn&#283;te pro vlo&#382;en&#237; speci&#225;ln&#237;ch znak&#367; do pole popisu',
		'at_031' => 'Nastavit v&#253;choz&#237; rozm&#283;ry obr&#225;zku',
		'at_032' => 'Z&#225;hlav&#237;',
		'at_033' => 'za&#353;krtnuto: nastavit z&#225;hlav&#237; obr&#225;zku / neza&#353;krtnuto: bez z&#225;hlav&#237; nebo zru&#353;en&#237; z&#225;hlav&#237;',
		'at_034' => 'nastavit z&#225;hlav&#237; obr&#225;zku',
		'at_099' => 'v&#253;choz&#237;',	
		//-------------------------------------------------------------------------		
		// error messages - er
		'er_001' => 'Chyba',
		'er_002' => 'Nen&#237; vybr&#225;n obr&#225;zek!',
		'er_003' => '&#352;&#237;&#345;ka nen&#237; &#269;&#237;slo',
		'er_004' => 'V&#253;&#353;ka nen&#237; &#269;&#237;slo',
		'er_005' => 'R&#225;me&#269;ek nen&#237; &#269;&#237;slo',
		'er_006' => 'Horizont&#225;ln&#237; odsazen&#237; nen&#237; &#269;&#237;slo',
		'er_007' => 'Vertik&#225;ln&#237; odsazen&#237; nen&#237; &#269;&#237;slo',
		'er_008' => 'Klikn&#283;te na OK pro smaz&#225;n&#237; obr&#225;zku',
		'er_009' => 'P&#345;ejmenov&#225;n&#237; n&#225;hled&#367; nen&#237; povoleno! P&#345;ejmenujte obr&#225;zek, chcete-li p&#345;ejmenovat jeho n&#225;hled.',
		'er_010' => 'Klikn&#283;te na OK pro p&#345;ejmenov&#225;n&#237; obr&#225;zku na',
		'er_011' => 'Nov&#233; jm&#233;no je pr&#225;zdn&#233;, nebo nebylo zm&#283;n&#283;no!',
		'er_014' => 'Zadejte nov&#233; jm&#233;no souboru!',
		'er_015' => 'Zadejte validn&#237; jm&#233;no souboru!',
		'er_016' => 'N&#225;hled nen&#237; k dispozivi! Pro zapnut&#237; n&#225;hled&#367; nastavte velikost n&#225;hled&#367; v konfigura&#269;n&#237;m souboru.',
		'er_021' => 'Klikn&#283;te na OK pro upload obr&#225;zku.',
		'er_022' => 'Upload obr&#225;zku - pros&#237;m vydr&#382;te...',
		'er_023' => 'Nebyl vybr&#225;n &#382;&#225;dn&#253; obr&#225;zek, nebo nebyl ozna&#269;en &#382;&#225;dn&#253; soubor.',
		'er_024' => 'Soubor',
		'er_025' => 'u&#382; existuje! Klikn&#283;te na OK pro p&#345;eps&#225;n&#237;...',
		'er_026' => 'Zadejte nov&#233; jm&#233;no souboru!',
		'er_027' => 'Adres&#225;r fyzicky neexistuje',
		'er_028' => 'Do&#353;lo k chyb&#283; p&#345;i obsluze uploadu souboru. Zkuste to pros&#237;m znovu.',
		'er_029' => '&#352;patn&#253; typ obrazov&#233;ho souboru',
		'er_030' => 'Maz&#225;n&#237; selhalo! Zkuste to pros&#237;m znovu.',
		'er_031' => 'P&#345;epsat',
		'er_032' => 'N&#225;hled skute&#269;n&#233; velikosti funguje jen pro obr&#225;zky v&#283;t&#353;&#237;ch rozm&#283;r&#367; jako okno n&#225;hledu',
		'er_033' => 'P&#345;ejmenov&#225;n&#237; souboru selhalo! Zkuste to pros&#237;m znovu.',
		'er_034' => 'Vytvo&#345;en&#237; adres&#225;&#345;e selhalo! Zkuste to pros&#237;m znovu.',
		'er_035' => 'Zv&#283;t&#353;en&#237; nen&#237; podporov&#225;no!',
		'er_036' => 'Chyba p&#345;i vytv&#225;&#345;en&#237; seznamu obr&#225;zk&#367;!',
	  ),	  
	  //-------------------------------------------------------------------------
	  // symbols
		'symbols'		=> array (
		'title' 		=> 'Symboly',
		'ok' 			=> 'OK',
		'cancel' 		=> 'Storno',
	  ),	  
	)
?>
