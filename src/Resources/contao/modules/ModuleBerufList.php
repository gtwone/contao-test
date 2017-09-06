<?php
 namespace gtwone\lexikon;
class ModuleBerufList extends Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_beruf_list';
	
	 public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
			
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['mod_beruf_list']) . ' ###';
            $objTemplate->id = $this->id;
 
            return $objTemplate->parse();
        }

        return parent::generate();
    }
 
	protected function compileList($buchstabe = ''){
		/* vars depending on language */
		$ttlLexikon = 'berufslexikon';
		$ttlBeruf = 'beruf';
		$ttlAbc = 'beruf-nach-buchstabe';
		if($GLOBALS['TL_LANGUAGE'] === 'fr-CH'){
			$ttlLexikon = 'lexique';
			$ttlBeruf = 'profession';
			$ttlAbc = 'professions-par-ordre-alphabetique';
		}
		
		/* get all Berufe */
		$objAllBerufe = \BerufModel::findBy(
			array('published=1', 'lang="'.$GLOBALS['TL_LANGUAGE'].'"'),
			$varValue = '',
			array('order'=>'bezeichnung')
		);
		$allFirstCharacters = array();
		if($objAllBerufe !== null){
			while($objAllBerufe->next()){
				$allFirstCharacters[] = strtolower(substr($objAllBerufe->bezeichnung, 0, 1));
			}
			$allFirstCharacters = array_unique($allFirstCharacters);
			$objAllBerufe->reset();
		}
		
		/**
		* ABC Filter 
		*/
		$abc =	'';
		foreach (range('a', 'z') as $char) {
			
			$abcLink = $this->addToURL($ttlAbc.'='.$char);
			
			if($buchstabe == $char){
				$class = 'aktiv';
			}else{
				$class = '';
			}
			
			if(in_array($char, $allFirstCharacters)){
				$abc .= '<li class="link '.$class.'"><a href="'.$abcLink.'">'.$char.'</a></li>';
			} else {
				$abc .= '<li class="inaktiv"><div>'.$char.'</div></li>';
			}
		}
		
		$abc = '<div class="sticky"><ul class="list-abc list-unstyled list-inline">'.$abc.'</ul><p style="clear:both"></p></div>';
		
		if(strlen(trim($buchstabe)) > 0){
			$objBerufe = \BerufModel::findBy(
				array("published=1", "bezeichnung LIKE  '".$buchstabe."%'", 'lang="'.$GLOBALS['TL_LANGUAGE'].'"'),
				$varValue = '',
				array('order'=>'bezeichnung')
			);
			
			/**
			* LanguageChanger manipulieren
			*/
			$this->Template->jQuery = '';
			if($GLOBALS['TL_LANGUAGE'] === 'fr-CH'){
				$this->Template->jQuery .= '$("li.lang-fr-CH a").attr("href", window.location.href);';
				$this->Template->jQuery .= '$("li.lang-fr-CH a").attr("title", document.title);';
				if (($jumpTo = \PageModel::findBy("alias", "berufsliste")) !== null) {
					$strUrl = \Controller::generateFrontendUrl($jumpTo->row(), '/beruf-nach-buchstabe/'.trim($buchstabe));
					$this->Template->jQuery .= '$("li.lang-de a").attr("href", "'.$strUrl.'");';
					$this->Template->jQuery .= '$("li.lang-de a").attr("title", "'.$objDeBeruf->bezeichnung.'");';
				}
			}else{
				$this->Template->jQuery .= '$("li.lang-de a").attr("href", window.location.href);';
				$this->Template->jQuery .= '$("li.lang-de a").attr("title", document.title);';
				if (($jumpTo = \PageModel::findBy("alias", "professionns")) !== null) {
					$strUrl = \Controller::generateFrontendUrl($jumpTo->row(), '/professions-par-ordre-alphabetique/'.trim($buchstabe));
					$this->Template->jQuery .= '$("li.lang-fr-CH a").attr("href", "'.$strUrl.'");';
					$this->Template->jQuery .= '$("li.lang-fr-CH a").attr("title", "'.$objFrBeruf->bezeichnung.'");';
				}
			}
		}else{
			$objBerufe = $objAllBerufe;
		}
						
		if($objBerufe === null)
        {
			return $this->Template->abc = $abc;
        }
		
		$arrBerufe = array();
		
		while($objBerufe->next())
		{
			/**
			* Generiere Detail Link
			*/
			$strLink = $this->addToURL($ttlAbc.'=&'.$ttlLexikon.'='.$objBerufe->id.'&'.$ttlBeruf.'='.standardize($objBerufe->bezeichnung));
						
			/**
			* Array für FE-Ausgabe
			*/
			$arrBerufe[] = array
            (
				'id'			=> $objBerufe->id,
                'bezeichnung' 	=> $objBerufe->bezeichnung,
				'link'			=> $strLink
            );
		}

		$this->Template->abc = $abc;
		$this->Template->berufe = $arrBerufe;
		
	}
	
	protected function compileDetail(){
		/* vars depending on language */
		$ttlLexikon = 'berufslexikon';
		$ttlBeruf = 'beruf';
		if($GLOBALS['TL_LANGUAGE'] === 'fr-CH'){
			$ttlLexikon = 'lexique';
			$ttlBeruf = 'profession';
		}
		
		/**
		* Detail Template laden
		*/
		$this->strTemplate = 'mod_beruf_detail'; 
		$this->Template = new FrontendTemplate($this->strTemplate);
		
		$aktuellerEintrag = $GLOBALS['TL_LANGUAGE'] === 'fr-CH' ? $this->Input->get('lexique') : $this->Input->get('berufslexikon');
		
		$objBeruf = \BerufModel::findBy(
			array('id=?', 'published=?'),
			array($aktuellerEintrag, '1')
		);
		
		$objBeruf->beruf_verwandt = deserialize($objBeruf->beruf_verwandt);
		$arrBerufeVerwandt = array();
		
		if($objBeruf->beruf_verwandt == ''){

		}else{
			foreach($objBeruf->beruf_verwandt as $beruf_verwandt_id){
					
				$objBerufVerwandtDetail = \BerufModel::findBy(
					array('id=?', 'published=?'),
					array($beruf_verwandt_id, '1')
				);
						
				$arrBerufeVerwandt[] = array(
					'bezeichnung'	=>	$objBerufVerwandtDetail->bezeichnung,
					'link'			=>  $this->addToURL($ttlLexikon.'='.$objBerufVerwandtDetail->id.'&'.$ttlBeruf.'='.standardize($objBerufVerwandtDetail->bezeichnung)),
				);
			}
		}
				
		$arrBeruf = array();
				
		if ($objBeruf === null)
        {
            return;
        }
		
        //WERBUNG
		$objWerbungen = \BerufwerbungModel::findBy(
			array('published=1','startDate<=UNIX_TIMESTAMP(NOW())', 'endDate>=UNIX_TIMESTAMP(NOW())'),
			$varValue = '',
			array('order'=>'bezeichnung')
		);
		
		if($objWerbungen){
			foreach($objWerbungen as $objWerbung){
				$objWerbungBeruf			= deserialize($objWerbung->beruf);
				$objWerbungPositions 		= deserialize($objWerbung->position);
				if(in_array($objBeruf->id,$objWerbungBeruf) || $objWerbungPositions && in_array('4',$objWerbungPositions)){
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
                
                $objVideoBeruf = deserialize($objVideo->beruf);
                $objVideoPoster = \FilesModel::findByPk($objVideo->bild);
                
                if($objVideoBeruf != NULL)  {             
                if(in_array($objBeruf->id,$objVideoBeruf)){
                    
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
		
		/**
		* Bilder laden
		*/
		$objBeruf->multiSRC = deserialize($objBeruf->multiSRC);
		$objBerufBilder = \FilesModel::findMultipleByUuids($objBeruf->multiSRC);
		
		$arrBerufBilder = array();
		
		if($objBerufBilder === null){
			
		}else{
			foreach($objBerufBilder as $objBerufBild){
				array_push($arrBerufBilder, $objBerufBild->path);
			}
		}
		
        //Titel Tag
        $objBerufsTitle = $this->berufslexikonTitle;
        $strBerufsTitle = str_replace('%berufbezeichnung%', $objBeruf->bezeichnung, $objBerufsTitle);
        
        //Description Tag
        $objBerufsDesc = $this->berufslexikonDesc;
        $strBerufslink = str_replace('%berufbezeichnung%', $objBeruf->bezeichnung, $objBerufsDesc);
        
        /**
        * Lehr/Schnupperstellen finden Link
        */
        
        // Momentan keine ProfessionIds vorhanden
        //if($objBeruf->ProfessionId){
			if($objBeruf->link_joboffers){
				$linkSchnupper = $objBeruf->link_joboffers;
				$linkLehrstelle = $linkSchnupper;
			}else{
				$linkSchnupper =  '/Home/JobOffers?ApprenticeshipType=TrialApprenticeship&ProfessionName='.$objBeruf->bezeichnung;
				$linkLehrstelle = '/Home/JobOffers?ApprenticeshipType=Apprenticeship&ProfessionName='.$objBeruf->bezeichnung;
			}
        //}
        
		$arrBeruf = array(
			'id'					=> $objBeruf->id,
			'bezeichnung'			=> $objBeruf->bezeichnung,
			'beschreibung'			=> $objBeruf->beschreibung,
			'anforderung'			=> $objBeruf->anforderung,
			'ausbildung'			=> $objBeruf->ausbildung,
			'zukunft'				=> $objBeruf->zukunft,
			'bilder'				=> $arrBerufBilder,
            'linkschnupperstelle'   => $linkSchnupper,
            'linklehrstelle'        => $linkLehrstelle,
		);
		
		$this->Template->berufe = $arrBeruf;
		$this->Template->berufe_verwandt = $arrBerufeVerwandt;
		$this->Template->werbung = $arrWerbung;
		$this->Template->video = $arrVideos;
        
        $GLOBALS['objPage']->pageTitle = $strBerufsTitle;
        $GLOBALS['objPage']->description = $strBerufslink;
        $GLOBALS[TL_KEYWORDS] = $objBeruf->keywords;
		
		/**
		* LanguageChanger manipulieren
		*/
		$this->Template->jQuery = '';
		if($GLOBALS['TL_LANGUAGE'] === 'fr-CH'){
			$this->Template->jQuery .= '$("li.lang-fr-CH a").attr("href", window.location.href);';
			$this->Template->jQuery .= '$("li.lang-fr-CH a").attr("title", document.title);';
			if($objBeruf->DeBeruf > 0){
				$objDeBeruf = \BerufModel::findBy(
					array('id=?', 'published=?'),
					array($objBeruf->DeBeruf, '1')
				);
				if (($jumpTo = \PageModel::findBy("alias", "berufsliste")) !== null) {
					$strUrl = \Controller::generateFrontendUrl($jumpTo->row(), '/berufslexikon/'.$objDeBeruf->id.'/beruf/'.standardize($objDeBeruf->bezeichnung));
				}
				$this->Template->jQuery .= '$("li.lang-de a").attr("href", "'.$strUrl.'");';
				$this->Template->jQuery .= '$("li.lang-de a").attr("title", "'.$objDeBeruf->bezeichnung.'");';
			}else{
				$this->Template->jQuery .= '$("li.lang-de").remove();';
			}
		}else{
			$this->Template->jQuery .= '$("li.lang-de a").attr("href", window.location.href);';
			$this->Template->jQuery .= '$("li.lang-de a").attr("title", document.title);';
			$objFrBeruf = \BerufModel::findBy(
				array('DeBeruf=?', 'published=?'),
				array($objBeruf->id, '1')
			);
			if($objFrBeruf === null){
				$this->Template->jQuery .= '$("li.lang-fr-CH").remove();';
			}else{
				if (($jumpTo = \PageModel::findBy("alias", "professions")) !== null) {
					$strUrl = \Controller::generateFrontendUrl($jumpTo->row(), '/lexique/'.$objFrBeruf->id.'/profession/'.standardize($objFrBeruf->bezeichnung));
				}
				$this->Template->jQuery .= '$("li.lang-fr-CH a").attr("href", "'.$strUrl.'");';
				$this->Template->jQuery .= '$("li.lang-fr-CH a").attr("title", "'.$objFrBeruf->bezeichnung.'");';
			}
		}
	}
	
	protected function compile()
	{
		/* vars depending on language */
		$ttlAbc = 'beruf-nach-buchstabe';
		if($GLOBALS['TL_LANGUAGE'] === 'fr-CH'){
			$ttlAbc = 'professions-par-ordre-alphabetique';
		}
		
		if(
			strlen($this->Input->get('berufslexikon')) && strlen($this->Input->get('beruf'))
			|| strlen($this->Input->get('lexique')) && strlen($this->Input->get('profession'))
		){
			$this->compileDetail();
		}elseif($this->Input->get($ttlAbc)){
			$this->compileList($this->Input->get($ttlAbc));
		}else{
			$this->compileList();
		}
		
		$arrLabels = array(
			'beschreibung'	=>	$GLOBALS['TL_LANG']['MSC']['beschreibung'],
			'anforderungen'	=>	$GLOBALS['TL_LANG']['MSC']['anforderungen'],
			'ausbildung'	=>	$GLOBALS['TL_LANG']['MSC']['ausbildung'],
			'zukunft'	=>	$GLOBALS['TL_LANG']['MSC']['zukunft'],
			'verwandt'	=>	$GLOBALS['TL_LANG']['MSC']['verwandt'],
            'findenschnupper'  =>   $GLOBALS['TL_LANG']['MSC']['findenschnupper'],
            'findelehre'       =>   $GLOBALS['TL_LANG']['MSC']['findelehre'],
            'source'           =>   $GLOBALS['TL_LANG']['MSC']['source'],
            'title'            =>   $GLOBALS['TL_LANG']['MSC']['title']
		);
		
		$this->Template->labels = $arrLabels;
	}
}

?>