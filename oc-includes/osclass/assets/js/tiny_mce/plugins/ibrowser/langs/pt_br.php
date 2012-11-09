<?php
	// ================================================
	// PHP image browser - iBrowser 
	// ================================================
	// iBrowser - language file: Portugês - Brasil
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: GPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.1                   Date: 26/07/2006
	// Por Ronaldo Chevalier - www.rcsigns.com.br
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
		'im_001' => 'Visualização de Imagem',
		'im_002' => 'iBrowser',
		'im_003' => 'Menu',
		'im_004' => 'Bem Vindo',
		'im_005' => 'Inserir',
		'im_006' => 'Cancelar',
		'im_007' => 'Inserir',		
		'im_008' => 'Inserir/trocar',
		'im_009' => 'Propriedades',
		'im_010' => 'Propriedades da Imagem',
		'im_013' => 'Janela Popup',
		'im_014' => 'Imagem em popup',
		'im_015' => 'Sobre iBrowser',
		'im_016' => 'Seção',
		'im_097' => 'Por favor aguarde enquanto carrega...',
		'im_098' =>	'Abrir seção',
		'im_099' => 'Fechar seção',
		//-------------------------------------------------------------------------
		// insert/change screen - in	
		'in_001' => 'Inserir/trocar imagem',
		'in_002' => 'Biblioteca',
		'in_003' => 'Selecione uma imagem da biblioteca',
		'in_004' => 'Imagens',
		'in_005' => 'Visualização',
		'in_006' => 'Deletar imagem',
		'in_007' => 'Clique para visualizar a imagem em tamanho maior',
		'in_008' => 'Abrir a imagem carregada, renomear ou deletar seção',	
		'in_009' => 'Informação',
		'in_010' => 'Janela Popup',		
		'in_013' => 'Criar um link para uma imagem ser aberta em nova janela.',
		'in_014' => 'Remover link popup',	
		'in_015' => 'Aquivo',	
		'in_016' => 'Renomear',
		'in_017' => 'Renomear imagem',
		'in_018' => 'Carregar',
		'in_019' => 'Carregar imagem',	
		'in_020' => 'Tamanho(s)',
		'in_021' => 'Marque o(s) tamanho(s) desejado para criar enquanto a(s) imagem(ns) é carregada',
		'in_022' => 'Original',
		'in_023' => 'A Imagem será cortada',
		'in_024' => 'Deletar',
		'in_025' => 'Diretório',
		'in_026' => 'Clique para criar um diretório',
		'in_027' => 'Crie um diretório',
		'in_028' => 'Largura',
		'in_029' => 'Altura',
		'in_030' => 'Tipo',
		'in_031' => 'Tamanho',
		'in_032' => 'Nome',
		'in_033' => 'Criado',
		'in_034' => 'Modificado',
		'in_035' => 'Informação da Imagem',
		'in_036' => 'Clique na imagem para fechar a janela',
		'in_037' => 'Rotacionar',
		'in_038' => 'Rotacionar Automático: ajuste a informação do exif, para usar a orientação pelo EXIF armazenado pela câmera. Você pode ajustar também para +180&deg; ou -180&deg; para tipo paisagem, ou +90&deg; ou -90&deg; para retrato. Valores positivos para sentido horário e valores negativos para sentido anti-horário.',
		'in_041' => '',
		'in_042' => 'Nenhum',		
		'in_043' => 'Retrato',
		'in_044' => '+ 90&deg;',	
		'in_045' => '- 90&deg;',
		'in_046' => 'Paisagem',	
		'in_047' => '+ 180&deg;',	
		'in_048' => '- 180&deg;',
		'in_049' => 'Câmera',	
		'in_050' => 'exif info',
		'in_051' => 'AVISO: A imagem atual é uma miniatura criada dinâmicamente pelo iManager - os parâmetros serã perdidos na troca da imagem.',
		'in_052' => 'Clique para visualizar outra imagem',
		'in_053' => 'Aleatório',
		'in_054' => 'Se marcado, uma imagem aleatória será inserida',
		'in_055' => 'Marque para inserir uma imagem aleatória',
		'in_056' => 'Parâmetros',
		'in_057' => 'Clique para voltar os parâmetros para seus valores padrão',
		'in_099' => 'Padrão',	
		//-------------------------------------------------------------------------
		// properties, attributes - at
		'at_001' => 'Atributos da Imagem',
		'at_002' => 'Código',
		'at_003' => 'Título',
		'at_004' => 'Título - mostrar descrição da imagem quando o mouse estiver em cima',
		'at_005' => 'Descrição',
		'at_006' => 'ALT - recolocação textual para a imagem, para ser indicado ou usado no lugar da imagem ',
		'at_007' => 'Estilo',
		'at_008' => 'Por favor, tenha certeza que o estilo selecionado existe na sua folha de estilos!',
		'at_009' => 'Estilos CSS',	
		'at_010' => 'Atributos',
		'at_011' => 'Os \'align\', \'border\', \'hspace\', and \'vspace\' atributos dos elementos da imagem não são suportados pelo XHTML 1.0 Strict DTD. Por favor use o estilo CSS disponível.',
		'at_012' => 'Alinhamento',	
		'at_013' => 'padrão',
		'at_014' => 'esquerda',
		'at_015' => 'direita',
		'at_016' => 'topo',
		'at_017' => 'meio',
		'at_018' => 'base',
		'at_019' => 'absmeio',
		'at_020' => 'texttop',
		'at_021' => 'linha de base',		
		'at_022' => 'Tamanho',
		'at_023' => 'Largura',
		'at_024' => 'Altura',
		'at_025' => 'Borda',
		'at_026' => 'Espaço Vertical',
		'at_027' => 'Espaço Horizontal',
		'at_028' => 'Visualizar',	
		'at_029' => 'Clique para inserir caracteres especiais no campo de título',
		'at_030' => 'Clique para inserir caracteres especiais no campo descrição',
		'at_031' => 'Voltar dimensões da imagem à seus valores padrão',
		'at_032' => 'Subtítulo',
		'at_033' => 'marcado: ajustar subtítulo da imagem / desmarcado: sem subtítulo ou limpar subtítulo da imagem',
		'at_034' => 'Ajustar subtítulo da imagem',
		'at_099' => 'padrão',	
		//-------------------------------------------------------------------------		
		// error messages - er
		'er_001' => 'Erro',
		'er_002' => 'Nenhuma imagem selecionada!',
		'er_003' => 'Largura não é número',
		'er_004' => 'Altura não é número',
		'er_005' => 'Borda não é número',
		'er_006' => 'Espaço Horizontal não é número',
		'er_007' => 'Espaço Vertical não é número',
		'er_008' => 'Clique em OK para deletar a imagem',
		'er_009' => 'Renomear miniatura não está disponível! Por favor renomeie a imagem principal se quiser renomear a miniatura.',
		'er_010' => 'Cliqu OK renomear a imagem',
		'er_011' => 'O novo nome está vazio ou não foi alterado!',
		'er_014' => 'Entre com um novo nome para o arquivo!',
		'er_015' => 'Entre com um novo válido!',
		'er_016' => 'Miniaturas não disponível! Ajuste o tamanho da miniatura no arquivo de configuração para habilitar.',
		'er_021' => 'Clique em OK to carregar a imagem(ns).',
		'er_022' => 'Carregando imagem - por favor aguarde...',
		'er_023' => 'Nenhuma imagem foi selecionada ou nenhum tamanho de arquivo foi marcado.',
		'er_024' => 'Arquivo',
		'er_025' => 'Este arquivo já existe! Clique em OK para regravar o arquivo...',
		'er_026' => 'Entre com um novo nome!',
		'er_027' => 'Pasta destino não existe fisicamente',
		'er_028' => 'Ocorreu um erro enquanto carregava o arquivo. Por favor tente novamente.',
		'er_029' => 'Tipo de imagem inválido',
		'er_030' => 'Falha para deletar o arquivo! Por favor tente novamente.',
		'er_031' => 'Regravado',
		'er_032' => 'Visualização em tamanho maior somente funciona para imagens maiores que o tamanho visualizado.',
		'er_033' => 'Renomear o arquivo falhou! Por favor tente novamente.',
		'er_034' => 'Criar pasta falhou! Please try again.',
		'er_035' => 'Aumentar não está disponível!',
		'er_036' => 'Erro construindo lista de imagens!',
	  ),	  
	  //-------------------------------------------------------------------------
	  // symbols
		'symbols'		=> array (
		'title' 		=> 'Symbolos',
		'ok' 			=> 'OK',
		'cancel' 		=> 'Cancelar',
	  ),	  
	)
?>