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
	$lang_charset = 'windows-1250';
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
		'im_004' => 'Vitajte',
		'im_005' => 'Vložit',
		'im_006' => 'Storno',
		'im_007' => 'Vloži',		
		'im_008' => 'Vložit/Zmeni',
		'im_009' => 'Vlastnosti',
		'im_010' => 'Vlastnosti obrázku',
		'im_013' => 'Vyskakovacie okná',
		'im_014' => 'Obrázok vo vyskakovacom okne;',
		'im_015' => 'O programe',
		'im_016' => 'Sekcie',
		'im_097' => 'Chvi¾ku strpenia, nahrávam...',
		'im_098' =>	'Otvori sekciu',
		'im_099' => 'Zatvori sekciu',
		//-------------------------------------------------------------------------
		// insert/change screen - in	
		'in_001' => 'Vloži/Zmeni obrázok',
		'in_002' => 'Knižnica',
		'in_003' => 'Vyberte knižnicu obrázkov',
		'in_004' => 'Obrázky',
		'in_005' => 'Náh¾ad',
		'in_006' => 'Zmaza obrázok',
		'in_007' => 'Kliknite pre zväèenie obrázku',
		'in_008' => 'Otvori upload obrázku, premenovanie, alebo zmazanie sekcie',	
		'in_009' => 'Informácie',
		'in_010' => 'Vyskakovacie okno',		
		'in_013' => 'Vytvorenie odkazu na obrázok otváraný v novom okne.',
		'in_014' => 'Odstrani odkaz na vyskakovacie okno',	
		'in_015' => 'Súbor',	
		'in_016' => 'Premenova',
		'in_017' => 'Premenova obrázok',
		'in_018' => 'Upload',
		'in_019' => 'Uploadova obrázok',	
		'in_020' => 'Ve¾kos(i)',
		'in_021' => 'Zaškrtnite požadované ve¾kosti pre upload obrázkov',
		'in_022' => 'Originál',
		'in_023' => 'Obrázok bude orezaný',
		'in_024' => 'Zmaza',
		'in_025' => 'Adresár',
		'in_026' => 'Kliknite pre vytvorenie adresára',
		'in_027' => 'Vytvori adresár',
		'in_028' => 'Šírka',
		'in_029' => 'Výška',
		'in_030' => 'Typ',
		'in_031' => 'Ve¾kos',
		'in_032' => 'Meno',
		'in_033' => 'Vytvorené',
		'in_034' => 'Zmenené',
		'in_035' => 'Informácie o obrázku',
		'in_036' => 'Kliknite na obrázok pre zazatvorenie okna',
		'in_037' => 'Otoèit',
		'in_038' => 'Automatické otoèenie: nastavi na EXIF informácie, pre použitie EXIF orientácie uložené fotoaparátom. Môže by tiež nastavené na +180&deg; alebo -180&deg; pre obrázok na šírku, alebo +90&deg; alebo -90&deg; pre obrázok na výšku. Kladné hodnoty pre posun v smere hodinových ruèièiek, záporné proti smeru.',
		'in_041' => '',
		'in_042' => 'žiadny',		
		'in_043' => 'na výšku',
		'in_044' => '+ 90&deg;',	
		'in_045' => '- 90&deg;',
		'in_046' => 'na šírku',	
		'in_047' => '+ 180&deg;',	
		'in_048' => '- 180&deg;',
		'in_049' => 'fotoaparát',	
		'in_050' => 'exif informácie',
		'in_051' => 'POZOR: Tento obrázok je dynamický náh¾ad vytvorený iManagerom - parametre budú stratené pri zmmene obrázku.',
		'in_052' => 'Kliknite pre zmenu náh¾adu vybraného obrázka',
		'in_053' => 'Náhodný',
		'in_054' => 'Ak je zaškrtnuté, bude vybrný náhodný obrázok',
		'in_055' => 'Zaškrtnite pre vloženie náhodného obrázku',
		'in_056' => 'Parametre',
		'in_057' => 'Kliknite pre nastavenie východzích parametrov',
		'in_099' => 'východzie',	
		//-------------------------------------------------------------------------
		// properties, attributes - at
		'at_001' => 'Vlastnosti obrázku',
		'at_002' => 'Zdroj',
		'at_003' => 'Titulok',
		'at_004' => 'TITLE - titulok obrázku, zobrazí sa po prejdení myšou nad obrázok',
		'at_005' => 'Popis',
		'at_006' => 'ALT -  alternatívny text obrázku, zobrazí sa pri nenaèítaní obrázku',
		'at_007' => 'Štýl',
		'at_008' => 'Uistite sa, že zadaný štýl existuje vo vašej definícii štýlov.',
		'at_009' => 'CSS-štýl',	
		'at_010' => 'Atribuúy',
		'at_011' => 'Atribuúy \'umiestnenie\', \'okraj\', \'horiz_medzera\' a \'vert_medzera\' elementu IMAGE niesú podporované v XHTML 1.0 Strict DTD. Použite namiesto toho CSS štýly.',
		'at_012' => 'Zarovnanie',	
		'at_013' => 'východzie',
		'at_014' => 'v¾avo',
		'at_015' => 'vpravo',
		'at_016' => 'nahor',
		'at_017' => 'doprostred',
		'at_018' => 'dole',
		'at_019' => 'stred obrázku zarovnaný so stredom textu',
		'at_020' => 'vrch obrázku zarovnaný s vrchom textu',
		'at_021' => 'na èiaru',		
		'at_022' => 've¾kos',
		'at_023' => 'Šírka',
		'at_024' => 'Výška',
		'at_025' => 'Rámèek',
		'at_026' => 'V-odsadenie',
		'at_027' => 'H-odsadenie',
		'at_028' => 'Náh¾ad',	
		'at_029' => 'Kliknite pre vloženie špeciálnych znakov do po¾a titulku',
		'at_030' => 'Kliknite pre vloženie špeciálnych znakov do po¾a popisu',
		'at_031' => 'Nastavi východzie rozmery obrázku',
		'at_032' => 'Záhlavie',
		'at_033' => 'zaškrtnuté: nastavi záhlavie obrázku / nezaškrtnuté: bez záhlavia alebo zrušenie záhlavia',
		'at_034' => 'nastavi záhlavie obrázku',
		'at_099' => 'východzie',	
		//-------------------------------------------------------------------------		
		// error messages - er
		'er_001' => 'Chyba',
		'er_002' => 'Nie je vybraný obrázok!',
		'er_003' => 'Šírka nie je èíslo',
		'er_004' => 'Výška nie je èíslo',
		'er_005' => 'Rámèek nie je èíslo',
		'er_006' => 'Horizontálne odsadenie nie je èíslo',
		'er_007' => 'Vertikálne odsadenie nie je èíslo',
		'er_008' => 'Kliknite na OK pre zmazanie obrázku',
		'er_009' => 'Premenovanie náh¾adu nie je dovolené! Premenujte obrázok, ak chcete premenovat jeho náh¾ad.',
		'er_010' => 'Kliknite na OK pre premenovanie obrázku na',
		'er_011' => 'Nové meno je prázdne, alebo nebolo zmenené!',
		'er_014' => 'Zadajte nové meno súboru!',
		'er_015' => 'Zadajte validní meno súboru!',
		'er_016' => 'Náh¾ad nie je k dispozícii! Pre zapnutie náh¾adov nastavte ve¾kost náh¾adov v konfiguraènom súbore.',
		'er_021' => 'Kliknite na OK pre upload obrázku.',
		'er_022' => 'Upload obrázku - prosím vydržte...',
		'er_023' => 'Nebol vybraný žiadnz obrázok, alebo nebol oznaèený žiadnz súbor.',
		'er_024' => 'súbor',
		'er_025' => 'už existuje! Kliknite na OK pre prepísanie...',
		'er_026' => 'zadajte nové meno súboru!',
		'er_027' => 'Adresár fyzicky neexistuje',
		'er_028' => 'Došlo k chybe pri obsluhe uploadu súboru. Skúste to prosím znovu.',
		'er_029' => 'Naplatný typ obrazového súboru',
		'er_030' => 'Mazanie zlyhalo! Skúste to prosím znovu.',
		'er_031' => 'Prepísa',
		'er_032' => 'Náh¾ad skutoènej ve¾kosti funguje len pre obrázky väèších rozmerov ako okno náh¾adu',
		'er_033' => 'Premenovanie súboru zlyhalo! Skúste to prosím znovu.',
		'er_034' => 'Vytvorení adresáre zlyhalo! Skúste to prosím znovu.',
		'er_035' => 'Zväèšenie nie je podporované!',
		'er_036' => 'Chyba pri vytváraní zoznamu obrázkov!',
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
