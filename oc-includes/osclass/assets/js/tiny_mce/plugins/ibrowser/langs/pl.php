<?php
	// ================================================
	// PHP image browser - iBrowser 
	// ================================================
	// iBrowser - language file: Polish
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: GPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.1                   Date: 10/21/2005
	// ================================================
	
	//-------------------------------------------------------------------------
	// charset to be used in dialogs
	$lang_charset = 'utf-8';
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
		'im_001' => 'Przegl&#261;darka obraz&#243;w',
		'im_002' => 'iBrowser',
		'im_003' => 'Menu',
		'im_004' => 'Witaj',
		'im_005' => 'Wstaw',
		'im_006' => 'Rezygnuj',
		'im_007' => 'Wstaw',		
		'im_008' => 'Wstaw/zamie&#324;',
		'im_009' => 'W&#322;a&#347;ciwo&#347;ci',
		'im_010' => 'W&#322;a&#347;ciwo&#347;ci obrazu',
		'im_013' => 'Wyskakuj&#261;ce okno',
		'im_014' => 'Wyskakuj&#261;cy obraz',
		'im_015' => 'O iBrowser',
		'im_016' => 'Sekcja',
		'im_097' => 'Prosz&#281; czeka&#263;, trwa &#322;&#261;dowanie...',
		'im_098' =>	'Otw&#243;rz sekcj&#281;',
		'im_099' => 'Zamknij sekcj&#281;',
		//-------------------------------------------------------------------------
		// insert/change screen - in	
		'in_001' => 'Wstaw/zamie&#324; obraz',
		'in_002' => 'Biblioteka',
		'in_003' => 'Wybierz bibliotek&#281; obraz&#243;w',
		'in_004' => 'Obrazy',
		'in_005' => 'Podgl&#261;d',
		'in_006' => 'Usu&#324; obraz',
		'in_007' => 'Kliknij aby powi&#281;kszy&#263;',
		'in_008' => 'Sekcja &#322;adowania, zmiany nazwy i kasowania obraz&#243;w',	
		'in_009' => 'Informacja',
		'in_010' => 'Wyskakuj&#261;ce okno',		
		'in_013' => 'Stw&#243;rz link do obrazu otwieraj&#261;cego si&#281; w nowym oknie',
		'in_014' => 'Usu&#324; link do wyskakuj&#261;cego okna',	
		'in_015' => 'Plik',	
		'in_016' => 'Zmie&#324; nazw&#281;',
		'in_017' => 'Zmie&#324; nazw&#281; obrazu',
		'in_018' => 'Za&#322;aduj',
		'in_019' => 'Za&#322;aduj obraz',	
		'in_020' => 'Rozmiar',
		'in_021' => 'Zaznacz oczekiwany rozmiar &#322;adowanego obrazu',
		'in_022' => 'Orygina&#322;',
		'in_023' => 'Obraz zostanie przyci&#281;ty',
		'in_024' => 'Usu&#324;',
		'in_025' => 'Folder',
		'in_026' => 'Kliknij aby stworzy&#263; folder',
		'in_027' => 'Stw&#243;rz folder',
		'in_028' => 'Szeroko&#347;&#263;',
		'in_029' => 'Wysoko&#347;&#263;',
		'in_030' => 'Typ',
		'in_031' => 'Rozmiar',
		'in_032' => 'Nazwa',
		'in_033' => 'Stworzony',
		'in_034' => 'Zmodyfikowany',
		'in_035' => 'Informacja o obrazie',
		'in_036' => 'Kliknij w obraz aby zamkn&#261;&#263; okno',
		'in_037' => 'Rotacja',
		'in_038' => 'Auto-rotacja: ustaw exif info, aby korzysta&#263; z informacji EXIF z aparatu cyfrowego. Mo&#380;na r&#243;wnie&#380; ustawi&#263; obr&#243;t +180&#176; lub -180&#176; dla pejza&#380;u i +90&#176; lub -90&#176; dla portretu. Positive values for clockwise and negative values for counterclockwise.',
		'in_041' => '',
		'in_042' => 'brak',		
		'in_043' => 'portret',
		'in_044' => '+ 90&#176;',	
		'in_045' => '- 90&#176;',
		'in_046' => 'pejza&#380;',	
		'in_047' => '+ 180&#176;',	
		'in_048' => '- 180&#176;',
		'in_049' => 'aparat',	
		'in_050' => 'exif info',
		'in_051' => 'UWAGA: Bie&#380;&#261;cy obraz jest dynamiczn&#261; miniatur&#261; stworzon&#261; przez iManager - parametry zostan&#261; zagubione podczas modyfikacji obrazu.',
		'in_052' => 'Kliknij aby zmieni&#263; widok selekcji obrazu',
		'in_053' => 'Losowo',
		'in_054' => 'Zaznaczenie pola spowoduje wstawienie losowego obrazu',
		'in_055' => 'Zaznacz aby wstawi&#263; losowy obraz',
		'in_056' => 'Parametry',
		'in_057' => 'kliknij aby zresetowa&#263; warto&#347;ci',
		'in_099' => 'standardowa',	
		//-------------------------------------------------------------------------
		// properties, attributes - at
		'at_001' => 'Atrybuty obrazu',
		'at_002' => '&#346;cie&#380;ka',
		'at_003' => 'Tytu&#322;',
		'at_004' => 'TITLE - opis obrazu pojawiaj&#261;cy si&#281; po najechaniu mysz&#261;',
		'at_005' => 'Opis',
		'at_006' => 'ALT - tekst alternatywny wy&#347;wietlany w zast&#281;pstwie obrazu',
		'at_007' => 'Styl',
		'at_008' => 'Prosz&#281; si&#281; upewni&#263;, &#380;e styl istnieje w arkuszu styl&#243;w!',
		'at_009' => 'Styl CSS',	
		'at_010' => 'Atrybuty',
		'at_011' => 'Atrybuty \'align\', \'border\', \'hspace\', i \'vspace\' definiowane dla obrazu nie s&#261; wspierane w XHTML 1.0 Strict DTD. Prosz&#281; u&#380;ywa&#263; styl&#243;w CSS zamiast nich.',
		'at_012' => 'Uk&#322;ad',	
		'at_013' => 'standardowy',
		'at_014' => 'do lewej',
		'at_015' => 'do prawej',
		'at_016' => 'do g&#243;ry',
		'at_017' => 'do &#347;rodka',
		'at_018' => 'do do&#322;u',
		'at_019' => '&#347;rodek obrazka zr&#243;wnany z s&#261;siaduj&#261;cym tekstem',
		'at_020' => 'g&#243;ra obrazka zr&#243;wnana z s&#261;siaduj&#261;cym tekstem',
		'at_021' => 'd&#243;&#322; obrazka zr&#243;wnany z s&#261;siaduj&#261;cym tekstem',		
		'at_022' => 'Rozmiar',
		'at_023' => 'Szeroko&#347;&#263;',
		'at_024' => 'Wysoko&#347;&#263;',
		'at_025' => 'Ramka',
		'at_026' => 'Odleg&#322;o&#347;&#263; V',
		'at_027' => 'Odleg&#322;o&#347;&#263; H',
		'at_028' => 'Podgl&#261;d',	
		'at_029' => 'Kliknij aby wprowadzi&#263; specjalny znak do pola tytu&#322;u',
		'at_030' => 'Kliknij aby wprowadzi&#263; specjalny znak do pola opisu',
		'at_031' => 'Zresetuj wymiary obrazka do standardowych warto&#347;ci',
		'at_032' => 'Podpis',
		'at_033' => 'zaznaczone: podpis ustawiony / odznaczone: brak lub usuni&#281;cie podpisu',
		'at_034' => 'ustaw podpis',
		'at_099' => 'standardowy',	
		//-------------------------------------------------------------------------		
		// error messages - er
		'er_001' => 'B&#322;&#261;d',
		'er_002' => 'Obraz nie zosta&#322; zaznaczony!',
		'er_003' => 'Szeroko&#347;&#263; nie jest liczb&#261;',
		'er_004' => 'Wysoko&#347;&#263; nie jest liczb&#261;',
		'er_005' => 'Szeroko&#347;&#263; ramki nie jest liczb&#261;',
		'er_006' => 'Odleg&#322;o&#347;&#263; horyzontalna nie jest liczb&#261;',
		'er_007' => 'Odleg&#322;o&#347;&#263; wertykalna nie jest liczb&#261;',
		'er_008' => 'Kliknij OK aby skasowa&#263; obraz',
		'er_009' => 'Zmiana nazwy miniatur nie jest dozwolona! Prosz&#281; zmieni&#263; nazw&#281; g&#322;&#243;wnego obrazu.',
		'er_010' => 'Kliknij OK aby zmieni&#263; nazw&#281; na',
		'er_011' => 'Nazwa jest pusta lub si&#281; nie zmieni&#322;a!',
		'er_014' => 'Prosz&#281; wprowadzi&#263; now&#261; nazw&#281; pliku!',
		'er_015' => 'Prosz&#281; wprowadzi&#263; prawid&#322;ow&#261; nazw&#281; pliku!',
		'er_016' => 'Tworzenie miniatur niemo&#380;liwe! Prosz&#281; ustawi&#263; rozmiar miniatury w pliku konfiguracyjnym.',
		'er_021' => 'Kliknij OK aby za&#322;adowa&#263; obraz(y)',
		'er_022' => '&#321;adowanie obrazu - prosz&#281; czeka&#263;...',
		'er_023' => '&#379;aden obraz nie zosta&#322; zaznaczony lub nie zdefiniowano jego wielko&#347;ci',
		'er_024' => 'Plik',
		'er_025' => 'ju&#380; istnieje! Naci&#347;nij OK aby go przepisa&#263;...',
		'er_026' => 'Prosz&#281; wprowadzi&#263; now&#261; nazw&#281; pliku!',
		'er_027' => 'Folder fizycznie nie istnieje',
		'er_028' => 'Pojawi&#322; si&#281; b&#322;&#261;d podczas &#322;adowania pliku. Prosz&#281; spr&#243;bowa&#263; ponownie.',
		'er_029' => 'B&#322;&#281;dny typ pliku',
		'er_030' => 'Kasowanie nieudane! Prosz&#281; spr&#243;bowa&#263; ponownie.',
		'er_031' => 'Nadpisz',
		'er_032' => 'Pe&#322;ny rozmiar podgl&#261;du dzia&#322;a tylko dla obraz&#243;w wi&#281;kszych ni&#380; podgl&#261;d',
		'er_033' => 'Zmiana nazwy pliku nieudana! Prosz&#281; spr&#243;bowa&#263; ponownie.',
		'er_034' => 'Tworzenie katalogu nieudane! Prosz&#281; spr&#243;bowa&#263; ponownie.',
		'er_035' => 'Powi&#281;kszanie nie jest dozwolone!',
		'er_036' => 'Wyst&#261;pi&#322; b&#322;&#261;d podczas tworzenia listy plik&#243;w!',
	  ),	  
	  //-------------------------------------------------------------------------
	  // symbols
		'symbols'		=> array (
		'title' 		=> 'Symbole',
		'ok' 			=> 'OK',
		'cancel' 		=> 'Rezygnuj',
	  ),	  
	)
?>