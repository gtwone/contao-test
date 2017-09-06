<?php
/**
*Back end modules
*/
$GLOBALS['BE_MOD']['berufslexikon']['berufe'] = array('tables' => array('tl_beruf','tl_berufsfeld'), 'icon'   => 'system/modules/berufslexikon/assets/images/gateway.png');
$GLOBALS['BE_MOD']['berufslexikon']['berufsfeld'] = array('tables' => array('tl_berufsfeld'),'icon'   => 'system/modules/berufslexikon/assets/images/gateway.png');
$GLOBALS['BE_MOD']['berufslexikon']['tl_berufslexikon_links'] = array('tables' => array('tl_berufslexikon_links'),'icon'   => 'system/modules/berufslexikon/assets/images/gateway.png');
$GLOBALS['BE_MOD']['berufslexikon']['tl_berufslexikon_werbung'] = array('tables' => array('tl_berufslexikon_werbung'),'icon'   => 'system/modules/berufslexikon/assets/images/gateway.png');
$GLOBALS['BE_MOD']['berufslexikon']['tl_berufslexikon_video'] = array('tables' => array('tl_berufslexikon_video'),'icon'   => 'system/modules/berufslexikon/assets/images/gateway.png');

/**
*Front end modules
*/
$GLOBALS['FE_MOD']['miscellaneous']['berufslexikon_berufsfelder']   = 'gtwone\lexikon\ModuleBeruffeldList';
$GLOBALS['FE_MOD']['miscellaneous']['berufslexikon_berufsliste'] = 'gtwone\lexikon\ModuleBerufList';
$GLOBALS['FE_MOD']['miscellaneous']['berufslexikon_werbung'] = 'gtwone\lexikon\ModuleWerbung';


/**
*Models
*/
$GLOBALS['TL_MODELS']['tl_berufsfeld'] = 'Contao\BerufsfeldModel';
$GLOBALS['TL_MODELS']['tl_beruf'] = 'Contao\BerufModel';
$GLOBALS['TL_MODELS']['tl_berufslexikon_werbung'] = 'Contao\BerufwerbungModel';
$GLOBALS['TL_MODELS']['tl_berufslexikon_links'] = 'Contao\BeruflinkModel';
$GLOBALS['TL_MODELS']['tl_berufslexikon_video'] = 'Contao\BerufvideoModel';


/**
* Hooks
*/
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array('addBerufslexToSitemap', 'berufslexPages');

/**
*Custom
*
$GLOBALS['TL_BERUFSLEXIKON'] = array(
	'werbung' => array(
		'files'	=> array(
			'css' => 'system/modules/berufslexikon/assets/css/werbung.css',
			'js'  => 'system/modules/berufslexikon/assets/js/evt-track.js'
		),
	),
    'video' => array(
        'files' => array(
            'css' => 'assets/jquery/mediaelement/2.17.0/mediaelementplayer.min.css',
            'js' => 'assets/jquery/mediaelement/2.17.0/mediaelement-and-player.min.js'
        ),
    ),
);*/

?>