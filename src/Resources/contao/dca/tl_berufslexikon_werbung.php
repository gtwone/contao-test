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
 
/**
 * Table tl_beruf
 */
$GLOBALS['TL_DCA']['tl_berufslexikon_werbung'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('bezeichnung'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('bezeichnung'),
			'format'                  => '%s'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_berufslexikon_werbung', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'bezeichnung;{bilder_legend},img_desktop,img_mobil,url,bildbeschreibung,urlCampaign;{anzeige_legend},startDate,endDate,position,berufsfeld,beruf;{expert_legend},additionalCode;{einstellungen_legend:hide},published;'
		
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment",
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'bezeichnung' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['bezeichnung'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'unique'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''",
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['url'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'					  => array('mandatory'=>true, 'rgxp'=>'url', 'tl_class' => 'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''",
			'save_callback' 		  => array
			(
				array('tl_berufslexikon_werbung', 'checkURL')
			),
		),
		'urlCampaign' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['urlCampaign'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'					  => array('tl_class' => 'w50', 'isBoolean' => true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'startDate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['startDate'],
			'default'                 => time(),
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'mandatory'=>true, 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "int(10) unsigned NULL",
		),
		'endDate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['endDate'],
			'default'                 => null,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'mandatory'=>true, 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "int(10) unsigned NULL",
			'save_callback' 		  => array
			(
				array('tl_berufslexikon_werbung', 'checkEndDate')
			),
			
		),
		'img_desktop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['img_desktop'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('filesOnly'=>true, 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes'], 'fieldType'=>'radio', 'mandatory'=>true),
			'sql'                     => "binary(16) NULL",
		),
		'img_mobil' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['img_mobil'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('filesOnly'=>true, 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes'], 'fieldType'=>'radio', 'mandatory'=>true),
			'sql'                     => "binary(16) NULL",
		),
		'bildbeschreibung' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['bildbeschreibung'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'					  => array('mandatory'=>true, 'tl_class' => 'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''",
		),
		'position' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['position'],
			'exclude'				  => true,
			'inputType'				  => 'select',
			'options'				  => array(
											'1' => $GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['beruffeld_uebersicht'], 
											'2' => $GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['beruffelder_alle'],
											'4' => $GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['beruf_alle']
									  ),
			'eval'                    => array('multiple'=>true, 'tl_class'=>'w50', 'chosen'=>true),
			'sql'                     => "text NULL",
		),
		'additionalCode' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['additionalCode'],
			'exclude'				  => true,
			'inputType'				  => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'rows'=>5),
			'sql'                     => "text NULL",
		),
		'berufsfeld' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['berufsfeld'],
			'exclude'				  => true,
			'inputType'				  => 'select',
			'eval'                    => array('multiple'=>true, 'tl_class'=>'w50', 'chosen'=>true),
			'sql'                     => "text NULL",
			'foreignKey'              => 'tl_berufsfeld.bezeichnung',
		),
		'beruf' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['beruf'],
			'exclude'				  => true,
			'inputType'				  => 'select',
			'eval'                    => array('multiple'=>true, 'tl_class'=>'w50', 'chosen'=>true),
			'sql'                     => "text NULL",
			'foreignKey'              => 'tl_beruf.bezeichnung',
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['published'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('isBoolean'=>true),
			'sql'                     => "char(1) NOT NULL default ''",
		)
	)
);

class tl_berufslexikon_werbung extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Check permissions to edit table tl_berufslexikon_werbung
	 */
	public function checkPermission()
	{
		// HOOK: comments extension required
		if ($this->User->isAdmin)
		{
			return;
		}
	
	}
	
	/**
	 * Toggle the visibility of an element
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnPublished)
	{
		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_berufslexikon_werbung::published', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'tl_berufslexikon_werbung toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	 
		$this->createInitialVersion('tl_berufslexikon_werbung', $intId);
	 
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_berufslexikon_werbung']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_berufslexikon_werbung']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
			}
		}
	 
		// Update the database
		$this->Database->prepare("UPDATE tl_berufslexikon_werbung SET tstamp=". time() .", published='" . ($blnPublished ? '' : '1') . "' WHERE id=?")
			->execute($intId);
		$this->createNewVersion('tl_berufslexikon_werbung', $intId);
	}
	
    /**
     * Aendert das Aussehen des Toggle-Buttons.
     * @param $row
     * @param $href
     * @param $label
     * @param $title
     * @param $icon
     * @param $attributes
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        $this->import('BackendUser', 'User');
 
        if (strlen($this->Input->get('tid')))
        {
            $this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 0));
            $this->redirect($this->getReferer());
        }
 
        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_berufslexikon_werbung::published', 'alexf'))
        {
            return '';
        }
 
        $href .= '&amp;id='.$this->Input->get('id').'&amp;tid='.$row['id'].'&amp;state='.$row[''];
 
        if (!$row['published'])
        {
            $icon = 'invisible.gif';
        }
 
        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
    }
		
	public function checkURL($varValue, DataContainer $dc)
	{
		$urlStr = $varValue;
		$parsed = parse_url($varValue);
		if (empty($parsed['scheme'])) {
			$urlStr = 'http://' . ltrim($varValue, '/'); 
		}
		if(filter_var($urlStr, FILTER_VALIDATE_URL) === FALSE){
			throw new Exception($GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['url_ungueltig']);
		};
				
		return $urlStr;
	}
		
	public function checkEndDate($varValue, DataContainer $dc){
		
		/*
		$objCheckWerbung = \BerufwerbungModel::findBy(
			array('(startDate <='.$varValue.' AND endDate >='.$dc->activeRecord->startDate.')', ' NOT (id ='.$dc->activeRecord->id.')'),
			''
		);
		*/
		
		if($objCheckWerbung !== null){
			throw new Exception($GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['datum_vorhanden']);
		}

		if($dc->activeRecord->startDate > $varValue){
			
			throw new Exception($GLOBALS['TL_LANG']['tl_berufslexikon_werbung']['datum_ungueltig_ende']);
		
		}
		
		$varValue = $varValue + 86399;
		
		
		
		return $varValue;
		
	}

}
