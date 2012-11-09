<?php
	// ================================================
	// PHP image browser - iBrowser 
	// ================================================
	// iBrowser - language file: Spanish
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: GPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.3                   Date: 08/08/2005
	// Contributor: Diego de Lucas
	//				diegodelucas@gmail.com
	//				www.dlgsoftware.net
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
		'im_001' => 'Navegador de im&aacute;genes',
		'im_002' => 'iBrowser',
		'im_003' => 'Men&uacute;',
		'im_004' => 'Bienvenido',
		'im_005' => 'Insertar',
		'im_006' => 'Cancelar',
		'im_007' => 'Insertar',		
		'im_008' => 'Insertar/Cambiar',
		'im_009' => 'Propiedades',
		'im_010' => 'Propiedades de la imagen',
		'im_013' => 'Emergente',
		'im_014' => 'Imagen emergente',
		'im_015' => 'Acerca de iBrowser',
		'im_016' => 'Secci&oacute;n',
		'im_097' => 'Cargando... Espere...',
		'im_098' =>	'Abrir secci&oacute;n',
		'im_099' => 'Cerrar secci&oacute;n',
		//-------------------------------------------------------------------------
		// insert/change screen - in	
		'in_001' => 'Insertar/Cambiar imagen',
		'in_002' => 'Librer&iacute;a/Directorio',
		'in_003' => 'Selecciona una librer&iacute;a o directorio',
		'in_004' => 'Im&aacute;genes',
		'in_005' => 'Vista previa',
		'in_006' => 'Borrar imagen',
		'in_007' => 'Click para ampliar la imagen',
		'in_008' => 'Abre la secci&oacute;n de subir, renombrar o borrar imagen',	
		'in_009' => 'Informaci&oacute;n',
		'in_010' => 'Emergente',		
		'in_013' => 'Crea un enlace a una imagen para ser abierto en una ventana nueva.',
		'in_014' => 'Elimina enlace emergente',	
		'in_015' => 'Archivo',	
		'in_016' => 'Renombrar',
		'in_017' => 'Renombrar imagen',
		'in_018' => 'Subir',
		'in_019' => 'Subir imagen',	
		'in_020' => 'Tama&ntilde;o(s)',
		'in_021' => 'Marcar tama&ntilde;o(s) deseado(s) para ser creados mientras se sube(n) la(s) imagen(es)',
		'in_022' => 'Original',
		'in_023' => 'La imagen ser&aacute; ajustada en ambas dimensiones',
		'in_024' => 'Borrar',
		'in_025' => 'Directorio',
		'in_026' => 'Click OK para crear el directorio',
		'in_027' => 'Crear directorio',
		'in_028' => 'Ancho',
		'in_029' => 'Alto',
		'in_030' => 'Tipo',
		'in_031' => 'Tama&ntilde;o',
		'in_032' => 'Nombre',
		'in_033' => 'Creado',
		'in_034' => 'Modificado',
		'in_035' => 'Informaci&oacute;n de la imagen',
		'in_036' => 'Click en la imagen para cerrar la ventana',
		'in_037' => 'Rotar',
		'in_038' => 'Auto rotar: seleccionar \'exif info\', para usar la orientaci&oacute;n EXIF almacenada por la c&aacute;mara. Tambi&eacute;n puede seleccionar +180&deg; o -180&deg; para paisaje, o +90&deg; o -90&deg; para retrato. Los valores positivos en sentido de las agujas del reloj, y los negativos en sentido contrario.',
		'in_041' => '',
		'in_042' => 'none',		
		'in_043' => 'retrato',
		'in_044' => '+ 90&deg;',	
		'in_045' => '- 90&deg;',
		'in_046' => 'paisaje',	
		'in_047' => '+ 180&deg;',	
		'in_048' => '- 180&deg;',
		'in_049' => 'c&aacute;mara',	
		'in_050' => 'exif info',
		'in_051' => 'ATENCI&Oacute;N: la imagen actual es una imagen en miniatura din&aacute;mica creada con iManager - los par&aacute;metros se perder&aacute;n al cambiar de imagen.',
		'in_052' => 'Click para cambiar la vista de selecci&oacute;n de imagen',
		'in_053' => 'Aleatorio',
		'in_054' => 'Si se marca, se insertar&aacute; una imagen aleatoriamente',
		'in_055' => 'Marcar para insertar una imagen aleatoriamente',
		'in_056' => 'Par&aacute;metros',
		'in_057' => 'Click para inicializar los par&aacute;metros a sus valores por defecto',
		'in_099' => 'por defecto',	
		//-------------------------------------------------------------------------
		// properties, attributes - at
		'at_001' => 'Atributos de la imagen',
		'at_002' => 'Fuente',
		'at_003' => 'T&iacute;tulo',
		'at_004' => 'TITLE - muestra la descripci&oacute;n de la imagen cuando situamos el rat&oacute;n encima de &eacute;sta',
		'at_005' => 'Descripci&oacute;n',
		'at_006' => 'ALT - reemplazo textual de la imagen, para ser mostrado o usado en lugar de la imagen',
		'at_007' => 'Estilo',
		'at_008' => 'Asegurese de que el estilo seleccionado existe en la hoja de estilos',
		'at_009' => 'Estilo CSS',	
		'at_010' => 'Atributos',
		'at_011' => 'Los atributos \'align\', \'border\', \'hspace\', y \'vspace\' de un elemento imagen no son soportados en XHTML 1.0 Strict DTD. Use estilos CSS en su lugar.',
		'at_012' => 'Alineado',	
		'at_013' => 'por defecto',
		'at_014' => 'izquierda',
		'at_015' => 'derecha',
		'at_016' => 'arriba',
		'at_017' => 'medio',
		'at_018' => 'abajo',
		'at_019' => 'absmiddle',		
		'at_020' => 'texttop',
		'at_021' => 'baseline',		
		'at_022' => 'Tama&ntilde;o',
		'at_023' => 'Ancho',
		'at_024' => 'Alto',
		'at_025' => 'Borde',
		'at_026' => 'Espaciado vert.',
		'at_027' => 'Espaciado horiz.',
		'at_028' => 'Vista previa',	
		'at_029' => 'Click para insertar un car&aacute;cter especial en el campo de t&iacute;tulo',
		'at_030' => 'Click para insertar un car&aacute;cter especial en el campo de descripci&oacute;n',
		'at_031' => 'Ajusta las dimensiones de la imagen a los valores por defecto',
		'at_032' => 'Subt&iacute;tulo',
		'at_033' => 'Marcado: Pone subt&iacute;tulo a la imagen / Desmarcado: imagen sin subt&iacute;tulo o borra el subt&iacute;tulo de la imagen',
		'at_034' => 'Fija el subt&iacute;tulo de la imagen',
		'at_099' => 'por defecto',	
		//-------------------------------------------------------------------------		
		// error messages - er
		'er_001' => 'Error',
		'er_002' => 'No se ha seleccionado una imagen!',
		'er_003' => 'El ancho no es un n&uacute;mero',
		'er_004' => 'El alto no es un n&uacute;mero',
		'er_005' => 'El borde no es un n&uacute;mero',
		'er_006' => 'El espaciado horizontal no es un n&uacute;mero',
		'er_007' => 'El espaciado vertical no es un n&uacute;mero',
		'er_008' => 'Click OK para borrar la imagen',
		'er_009' => 'El renombrado de las im&aacute;genes en miniatura no est&aacute; permitido! Renombre la imagen principal si quiere renombrar la imagen en miniatura.',
		'er_010' => 'Click OK para renombrar la imagen a',
		'er_011' => 'El nuevo nombre está vacío o no ha cambiado!',
		'er_014' => 'Introduzca un nuevo nombre para el archivo!',
		'er_015' => 'Introduzca un nombre v&aacute;lido!',
		'er_016' => 'La creaci&oacute;n de im&aacute;genes en miniatura no est&aacute; habilitada! Fije el tama&ntilde;o de las miniaturas en el fichero de configuraci&oacute;n para habilitarlo.',
		'er_021' => 'Click OK para subir la(s) imagen(es).',
		'er_022' => 'Subiendo imagen - por favor, espere...',
		'er_023' => 'No ha seleccionado una imagen o no ha marcado un tamaño.',
		'er_024' => 'El archivo',
		'er_025' => 'ya existe! Click OK para sobreescribir el archivo...',
		'er_026' => 'Introduzca un nuevo nombre de archivo!',
		'er_027' => 'El directorio no existe físicamente',
		'er_028' => 'Ha ocurrido un error mientras se sub&iacute;a el archivo. Int&eacute;ntelo de nuevo.',
		'er_029' => 'Tipo de archivo de imagen incorrecto',
		'er_030' => 'El borrado ha fallado! Int&eacute;ntelo de nuevo.',
		'er_031' => 'Sobreescribir',
		'er_032' => 'La vista previa a tamaño completo sólo funciona para imágenes mayores que el tamaño de la previsualización',
		'er_033' => 'El renombrado del archivo ha fallado! Int&eacute;ntelo de nuevo.',
		'er_034' => 'La creaci&oacute;n del directorio ha fallado! Int&eacute;ntelo de nuevo.',
		'er_035' => 'El agrandamiento no está  permitido!',
		'er_036' => 'Error creando la lista de im&aacute;genes!',
	  ),	  
	  //-------------------------------------------------------------------------
	  // symbols
		'symbols'		=> array (
		'title' 		=> 'Símbolos',
		'ok' 			=> 'OK',
		'cancel' 		=> 'Cancelar',
	  ),	  
	)
?>