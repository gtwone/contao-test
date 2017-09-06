<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) Büro + Webdesign GmbH
 *
 * @package berufslexikon
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */
namespace gtwone\lexikon;

class ModuleWerbung extends \Module
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_berufslexikon_werbung';
		
    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
			
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['mod_berufsfelder_werbung']) . ' ###';
            $objTemplate->id = $this->id;
 
            return $objTemplate->parse();
        }

        return parent::generate();
    }
	 
    /**
     * Generate the module
     */
	
    protected function compile()
    {	
		
		$werbungCSS = $GLOBALS['TL_BERUFSLEXIKON']['werbung']['files']['css'];
		$GLOBALS['TL_CSS']['berufslexikon'] = $werbungCSS;
		
		$werbungJS = $GLOBALS['TL_BERUFSLEXIKON']['werbung']['files']['js'];
		$GLOBALS['TL_HEAD']['berufslexikon'] = '<script type="text/javascript" src="'.$werbungJS.'"></script>';
				
		$arrWerbung = array();
		
		if(!strlen($this->Input->get('berufslexikon')) && !strlen($this->Input->get('berufsfeld'))){
			
			$objWerbung = \BerufwerbungModel::findBy(
				array('published=1','startDate<=UNIX_TIMESTAMP(NOW())', 'endDate>=UNIX_TIMESTAMP(NOW())'),
				$varValue = '',
				array('order'=>'bezeichnung')
			);	
						
			$objWerbungBildDesktop		= \FilesModel::findByPk($objWerbung->img_desktop);
			$objWerbungBildMobil 		= \FilesModel::findByPk($objWerbung->img_mobil);
			
			$objWerbungBeruffelder 		= deserialize($objWerbung->berufsfeld);
			$objWerbungPositions 		= deserialize($objWerbung->position);
			
			$url = $objWerbung->url;
			
			if($objWerbung->urlCampaign){
				$url = $url.'?utm_source=Gateway%20Junior&utm_medium=Banner&utm_campaign='.$objWerbung->bezeichnung;
			}
			
			if($objWerbungPositions && in_array('1',$objWerbungPositions)){
				$arrWerbung = array(
					'bezeichnung'		=>	$objWerbung->bezeichnung,
					'url'				=>	$url,
					'img_desktop'		=>	$objWerbungBildDesktop->path,
					'img_mobil'			=>	$objWerbungBildMobil->path,
					'bildbeschreibung'	=>	$objWerbung->bildbeschreibung,
					'additionalCode'	=>	$objWerbung->additionalCode,
				);
			}
		}
				
		$this->Template->werbung = $arrWerbung;
		
    }
}

?>