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

class ModuleBeruffeldList extends \Module
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_berufsfeld_list';
		
    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
			
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['mod_berufsfelder_list']) . ' ###';
            $objTemplate->id = $this->id;
 
            return $objTemplate->parse();
        }

        return parent::generate();
    }
	 
    /**
     * Generate the module
     */
	protected function compileList(){
		/* vars depending on language */
		$ttlLexikon = 'berufslexikon';
		$ttlBerufsfeld = 'berufsfeld';
		if($GLOBALS['TL_LANGUAGE'] === 'fr-CH'){
			$ttlLexikon = 'lexique';
			$ttlBerufsfeld = 'champ-professionnels';
		}
		
		$objBerufsfelder = \BerufsfeldModel::findBy(
			array('published=1', 'lang="'.$GLOBALS['TL_LANGUAGE'].'"'),
			$varValue = '',
			array('order'=>'bezeichnung')
		);
		
		$objWerbung = \BerufwerbungModel::findBy(
			array('published=1','startDate<=UNIX_TIMESTAMP(NOW())', 'endDate>=UNIX_TIMESTAMP(NOW())'),
			$varValue = '',
			array('order'=>'bezeichnung')
		);	
		
		$objWerbungBildDesktop		= \FilesModel::findByPk($objWerbung->img_desktop);
		$objWerbungBildMobil 		= \FilesModel::findByPk($objWerbung->img_mobil);
		$objWerbungBeruffelder 		= deserialize($objWerbung->berufsfeld);
		$objWerbungPositions 		= deserialize($objWerbung->position);
		
		if($objWerbungPositions && in_array('1',$objWerbungPositions)){
			$arrWerbung = array(
				'bezeichnung'		=>	$objWerbung->bezeichnung,
				'url'				=>	$objWerbung->url,
				'img_desktop'		=>	$objWerbungBildDesktop->path,
				'img_mobil'			=>	$objWerbungBildMobil->path,
				'bildbeschreibung'	=>	$objWerbung->bildbeschreibung,
				'additionalCode'	=>	$objWerbung->additionalCode,
			);
		}
				
		if ($objBerufsfelder === null)
        {
            return;
        }
		
		$arrBerufsfelder = array();
		
		while($objBerufsfelder->next())
		{
			/**
			* Bilder laden
			*/
			$objBerufsfelder->multiSRC = deserialize($objBerufsfelder->multiSRC);
			$objBerufsfelderBilder = \FilesModel::findMultipleByUuids($objBerufsfelder->multiSRC);
			
			$arrBerufsfeldBilder = array();
			
			if($objBerufsfelderBilder === null){
				
			}else{
				foreach($objBerufsfelderBilder as $objBerufsfelderBild){
					array_push($arrBerufsfeldBilder, $objBerufsfelderBild->path);
				}
			}
			
			/**
			* Generiere Detail Link
			*/
			$strLink = $this->addToURL($ttlLexikon.'='.$objBerufsfelder->id.'&'.$ttlBerufsfeld.'='.standardize($objBerufsfelder->bezeichnung));
					
			/**
			* Array für FE-Ausgabe
			*/
			$arrBerufsfelder[] = array
            (
				'id'			=> $objBerufsfelder->id,
                'bezeichnung' 	=> $objBerufsfelder->bezeichnung,
				'bilder' 		=> $arrBerufsfeldBilder,
				'link'			=> $strLink
            );
			
		}
		
		$this->Template->berufsfelder = $arrBerufsfelder;
		$this->Template->werbung = $arrWerbung;
		
	}
	
	protected function compileDetail(){
		/* vars depending on language */
		$ttlLexikon = 'berufslexikon';
		$ttlBeruf = 'beruf';
		if($GLOBALS['TL_LANGUAGE'] === 'fr-CH'){
			$ttlLexikon = 'lexique';
			$ttlBeruf = 'profession';
		}
		
		$this->strTemplate = 'mod_berufsfeld_detail'; 
		$this->Template = new FrontendTemplate($this->strTemplate);
		$aktuellerEintrag = $this->Input->get($ttlLexikon);
		
		$objBerufsfelder = \BerufsfeldModel::findBy(
			array('id='.$aktuellerEintrag, 'published=1'),
			$varValue = '',
			array('order'=>'bezeichnung')
		);
		
		//Link zu Berufen
		if ($this->jumpTo > 0)
        {
            $objJump = \PageModel::findByPk($this->jumpTo);
 
            if ($objJump !== null)
            {
				$strLink = $this->generateFrontendUrl($objJump->row(), '/'.$ttlLexikon.'/%s/'.$ttlBeruf.'/%s');
            }
        }
        
        //Titel Tag
        $objBerufsTitle = $this->berufslexikonTitle;
        $strBerufsTitle = str_replace('%beruffeldnbezeichnung%', $objBerufsfelder->bezeichnung, $objBerufsTitle);
        
        //Description Tag
        $objBerufsDesc = $this->berufslexikonDesc;
        $strBerufslink = str_replace('%beruffeldnbezeichnung%', $objBerufsfelder->bezeichnung, $objBerufsDesc);
        		
		//Alle zugeoordneten Berufe finden
		$objBeruf = \BerufModel::findBy(
			array('published', '1'),
			$varValue = '',
			array('order'=>'bezeichnung')
		);
				
		if($objBeruf !== null){
			
			$arrBerufe = array();
			
			foreach($objBeruf as $beruf){
				if($beruf->berufsfeld !== null && $beruf->berufsfeld !== ''){
					$beruf->berufsfeld = deserialize($beruf->berufsfeld);
					if(in_array($objBerufsfelder->id,$beruf->berufsfeld)){
						$arrBerufe[] = array(
							'bezeichnung' => $beruf->bezeichnung,
							'url2' => strlen($strLink) ? sprintf($strLink, $beruf->id, standardize($beruf->bezeichnung)) : '',
						);
					}
				}
			}
		}

		//Alle zugeoordneten Links finden
		$objLinks = \BeruflinkModel::findBy('published','1');
		
		if($objLinks !== null){
			
			$arrLinks = array();
			
			foreach($objLinks as $link){
				if($link->berufsfeld !== null && $link->berufsfeld !== ''){
					$link->berufsfeld = deserialize($link->berufsfeld);
					if(in_array($objBerufsfelder->id,$link->berufsfeld)){
					
						$arrLinks[] = array(
							'bezeichnung' => $link->bezeichnung,
							'url' => $link->url,
						);
					}
				}
			}
		}
		
        //Videos
        $videoCSS = $GLOBALS['TL_BERUFSLEXIKON']['video']['files']['css'];
		$GLOBALS['TL_CSS']['berufslexikon'] = $videoCSS;
		
		$videoJS = $GLOBALS['TL_BERUFSLEXIKON']['video']['files']['js'];
		$GLOBALS['TL_HEAD']['berufslexikon'] = '<script type="text/javascript" src="'.$videoJS.'"></script>';
        
        
        $objVideos = \BerufvideoModel::findBy(
            array('published=1'),
			$varValue = '',
			array('order'=>'bezeichnung')
        );       
        
        if($objVideos){
                    
            $arrVideos = array();
            
            foreach($objVideos as $objVideo){
                
                $objVideoBerufsfelder = deserialize($objVideo->berufsfeld);
                $objVideoPoster = \FilesModel::findByPk($objVideo->bild);
                
                if($objVideoBerufsfelder != NULL)  {             
                if(in_array($objBerufsfelder->id,$objVideoBerufsfelder)){
                    
                    if(strpos($objVideo->url, 'www.whatchado.com')){
                        
                        $objWatchado = $objVideo->url;
                        
                        if(!strpos($objWatchado,'/embed/')){
                            $objWatchado = str_replace('stories/', 'stories/embed/', $objWatchado);
                        }
                         
                        $code = '<div class="embed-responsive embed-responsive-16by9"><iframe width="560" height="315" src="'.$objWatchado.'" scrolling="no" allowfullscreen frameborder="0"></iframe></div>';
                        
                        
                    }else if(strpos($objVideo->url, 'youtu.be')){
                        $objVideoYT = str_replace('https://youtu.be/', 'https://www.youtube.com/embed/', $objVideo->url);
                        $code = '<div class="embed-responsive embed-responsive-16by9"><iframe src="'.$objVideoYT.'" frameborder="0" allowfullscreen></iframe></div>';
                    }else if(strpos($objVideo->url, 'vimeo')){
                        //$code = '<video style="width:100%; height:100%;" preload="none"><source src="'.$objVideo->url.'" /></video>';
                        $objVideoVimeo = str_replace('https://vimeo.com/', 'https://player.vimeo.com/video/', $objVideo->url);
                        $code = '<div class="embed-responsive embed-responsive-16by9"><iframe src="'.$objVideoVimeo.'?title=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
                    }else{
                        $code = '<div class="embed-responsive embed-responsive-16by9"><video style="width:100%; height:100%;" preload="none" poster="'.$objVideoPoster->path.'"><source src="'.$objVideo->url.'" /></video></div>';
                    }
                    
                    if($objVideo->link){
                        
                        if($objVideo->linktitel){
                            $linktext = $objVideo->linktitel;
                        }else{
                            $linktext = str_replace('http://', '', $objVideo->link);
                        }
                        
                        $code .= '<a href="'.$objVideo->link.'" target="_blank">'.$linktext.'</a>';
                    }
                    
                    array_push($arrVideos, $code);
                    
                }
            }
			}
		
        }
		
        
        
        //Werbung
		$objWerbungen = \BerufwerbungModel::findBy(
			array('published=1','startDate<=UNIX_TIMESTAMP(NOW())', 'endDate>=UNIX_TIMESTAMP(NOW())'),
			$varValue = '',
			array('order'=>'bezeichnung')
		);	
		
		if($objWerbungen){
			foreach($objWerbungen as $objWerbung){
				$objWerbungBeruffelder			= deserialize($objWerbung->berufsfeld);
				$objWerbungPositions 		= deserialize($objWerbung->position);
				if(in_array($objBerufsfelder->id,$objWerbungBeruffelder) || $objWerbungPositions && in_array('2',$objWerbungPositions)){
					$objWerbungBildDesktop		= \FilesModel::findByPk($objWerbung->img_desktop);
					$objWerbungBildMobil 		= \FilesModel::findByPk($objWerbung->img_mobil);
					$arrWerbung = array(
						'bezeichnung'		=>	$objWerbung->bezeichnung,
						'url'				=>	$objWerbung->url,
						'img_desktop'		=>	$objWerbungBildDesktop->path,
						'img_mobil'			=>	$objWerbungBildMobil->path,
						'bildbeschreibung'	=>	$objWerbung->bildbeschreibung,
						'additionalCode'	=>	$objWerbung->additionalCode,
					);
					
				}
			}
		}
		
		$objWerbungBildDesktop		= \FilesModel::findByPk($objWerbung->img_desktop);
		$objWerbungBildMobil 		= \FilesModel::findByPk($objWerbung->img_mobil);
		$objWerbungBeruffelder 		= deserialize($objWerbung->berufsfeld);
		$objWerbungPositions 		= deserialize($objWerbung->position);
		/*
		if($objWerbungBeruffelder && in_array($objBerufsfelder->id,$objWerbungBeruffelder) || $objWerbungPositions && in_array('2',$objWerbungPositions)){
			$arrWerbung = array(
				'bezeichnung'		=>	$objWerbung->bezeichnung,
				'url'				=>	$objWerbung->url,
				'img_desktop'		=>	$objWerbungBildDesktop->path,
				'img_mobil'			=>	$objWerbungBildMobil->path,
				'bildbeschreibung'	=>	$objWerbung->bildbeschreibung,
				'additionalCode'	=>	$objWerbung->additionalCode,
			);
		}
		*/
		if ($objBerufsfelder === null)
        {
            return;
        }
		
		/**
		* Bilder laden
		*/
		$objBerufsfelder->multiSRC = deserialize($objBerufsfelder->multiSRC);
		$objBerufsfelderBilder = \FilesModel::findMultipleByUuids($objBerufsfelder->multiSRC);
		
		$arrBerufsfeldBilder = array();
		
		if($objBerufsfelderBilder === null){
			
		}else{
			foreach($objBerufsfelderBilder as $objBerufsfelderBild){
				array_push($arrBerufsfeldBilder, $objBerufsfelderBild->path);
			}
		}
		
        /**
        * Lehr/Schnupperstellen finden Link
        */
        $linkSchnupper = '';
        $linkLehrstelle = '';
        
        if($objBerufsfelder->ProfessionAreaId){
            $linkSchnupper = '/Home/JobOffers?ApprenticeshipType=TrialApprenticeship&ProfessionAreaId='.$objBerufsfelder->ProfessionAreaId.'&SelectedAreaLikeString='.$objBerufsfelder->ProfessionAreaId.'&ProfessionAreaName='.$objBerufsfelder->bezeichnung;
            $linkLehrstelle = '/Home/JobOffers?ApprenticeshipType=Apprenticeship&ProfessionAreaId='.$objBerufsfelder->ProfessionAreaId.'&SelectedAreaLikeString='.$objBerufsfelder->ProfessionAreaId.'&ProfessionAreaName='.$objBerufsfelder->bezeichnung;
        }
        
        
		$arrBerufsfeld = array(
			'id'			        => $objBerufsfelder->id,
			'bezeichnung'	        => $objBerufsfelder->bezeichnung,
			'beschreibung'	        => $objBerufsfelder->beschreibung,
			'bilder'		        => $arrBerufsfeldBilder,
            'linkschnupperstelle'   => $linkSchnupper,
            'linklehrstelle'        => $linkLehrstelle,
		);
		
		$this->Template->berufsfeld = $arrBerufsfeld;		
		$this->Template->berufe = $arrBerufe;
		$this->Template->links = $arrLinks;
		$this->Template->werbung = $arrWerbung;
		$this->Template->video = $arrVideos;
        
        $GLOBALS['objPage']->pageTitle = $strBerufsTitle;
        $GLOBALS['objPage']->description = $strBerufslink;
        $GLOBALS[TL_KEYWORDS] = $objBerufsfelder->keywords;
		
		/**
		* LanguageChanger manipulieren
		*/
		$this->Template->jQuery = '';
		if($GLOBALS['TL_LANGUAGE'] === 'fr-CH'){
			$this->Template->jQuery .= '$("li.lang-fr-CH a").attr("href", window.location.href);';
			$this->Template->jQuery .= '$("li.lang-fr-CH a").attr("title", document.title);';
			if($objBerufsfelder->DeBerufsfeld > 0){
				$objDeBerufsfeld = \BerufsfeldModel::findBy(
					array('id=?', 'published=?'),
					array($objBerufsfelder->DeBerufsfeld, '1')
				);
				//if (($jumpTo = \PageModel::findByPk(69)) !== null) {
				if (($jumpTo = \PageModel::findBy("alias", "berufsfelder")) !== null) {
					$strUrl = \Controller::generateFrontendUrl($jumpTo->row(), '/berufslexikon/'.$objDeBerufsfeld->id.'/berufsfeld/'.standardize($objDeBerufsfeld->bezeichnung));
					$this->Template->jQuery .= '$("li.lang-de a").attr("href", "'.$strUrl.'");';
					$this->Template->jQuery .= '$("li.lang-de a").attr("title", "'.$objDeBerufsfeld->bezeichnung.'");';
				}
			}else{
				$this->Template->jQuery .= '$("li.lang-de").remove();';
			}
		}else{
			$this->Template->jQuery .= '$("li.lang-de a").attr("href", window.location.href);';
			$this->Template->jQuery .= '$("li.lang-de a").attr("title", document.title);';
			$objFrBerufsfeld = \BerufsfeldModel::findBy(
				array('DeBerufsfeld=?', 'published=?'),
				array($objBerufsfelder->id, '1')
			);
			if($objFrBerufsfeld === null){
				$this->Template->jQuery .= '$("li.lang-fr-CH").remove();';
			}else{
				//if (($jumpTo = \PageModel::findByPk(335)) !== null) {
				if (($jumpTo = \PageModel::findBy("alias", "champs-professionnels")) !== null) {
					$strUrl = \Controller::generateFrontendUrl($jumpTo->row(), '/lexique/'.$objFrBerufsfeld->id.'/champ-professionnels/'.standardize($objFrBerufsfeld->bezeichnung));
					$this->Template->jQuery .= '$("li.lang-fr-CH a").attr("href", "'.$strUrl.'");';
					$this->Template->jQuery .= '$("li.lang-fr-CH a").attr("title", "'.$objFrBerufsfeld->bezeichnung.'");';
				}
			}
		}
	}
	 
    protected function compile()
    {
		
		if(
			strlen($this->Input->get('berufslexikon')) && strlen($this->Input->get('berufsfeld'))
			|| strlen($this->Input->get('lexique')) && strlen($this->Input->get('champ-professionnels'))
		){
			$this->compileDetail();
		}else{
			$this->compileList();
		}
		
		$arrLabels = array(
			'beschreibung'	   =>	$GLOBALS['TL_LANG']['MSC']['beschreibung'],
			'berufsliste'	   =>	$GLOBALS['TL_LANG']['MSC']['berufsliste'],
			'links'			   =>	$GLOBALS['TL_LANG']['MSC']['links'],
            'findenschnupper'  =>   $GLOBALS['TL_LANG']['MSC']['findenschnupper'],
            'findelehre'       =>   $GLOBALS['TL_LANG']['MSC']['findelehre'],
            'source'           =>   $GLOBALS['TL_LANG']['MSC']['source'],
            'title'            =>   $GLOBALS['TL_LANG']['MSC']['title']
		);
		
		$this->Template->labels = $arrLabels;
    }
}

?>