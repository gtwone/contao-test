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
$GLOBALS['TL_DCA']['tl_berufslexikon_video'] = array
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
				'label'               => &$GLOBALS['TL_LANG']['tl_berufslexikon_video']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_berufslexikon_video']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_berufslexikon_video']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_berufslexikon_video', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_berufslexikon_video']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'bezeichnung;{bilder_legend},url,link,linktitel,bild;{anzeige_legend},berufsfeld,beruf;{expert_legend},additionalCode;{einstellungen_legend:hide},published;'
		
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
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_video']['bezeichnung'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'unique'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''",
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_video']['url'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'					  => array('mandatory'=>true, 'rgxp'=>'url'),
			'sql'                     => "varchar(255) NOT NULL default ''",
			'save_callback' 		  => array
			(
				array('tl_berufslexikon_video', 'checkURL')
			),
		),
       'bild' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_video']['bild'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('filesOnly'=>true, 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes'], 'fieldType'=>'radio'),
			'sql'                     => "binary(16) NULL",
		),
        'link' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_video']['link'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'					  => array('rgxp'=>'url'),
			'sql'                     => "varchar(255) NOT NULL default ''",
		),  
        'linktitel' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_video']['linktitel'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'sql'                     => "varchar(255) NOT NULL default ''",
		), 
		'berufsfeld' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_berufslexikon_video']['berufsfeld'],
			'exclude'				  => true,
			'inputType'				  => 'select',
			'eval'                    => array('multiple'=>true, 'tl_class'=>'w50', 'chosen'=>true),
			'sql'                     => "text NULL",
			'foreignKey'              => 'tl_berufsfeld.bezeichnung',
		),
		'beruf' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_berufslexikon_video']['beruf'],
			'exclude'				  => true,
			'inputType'				  => 'select',
			'eval'                    => array('multiple'=>true, 'tl_class'=>'w50', 'chosen'=>true),
			'sql'                     => "text NULL",
			'foreignKey'              => 'tl_beruf.bezeichnung',
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_video']['published'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('isBoolean'=>true),
			'sql'                     => "char(1) NOT NULL default ''",
		)
	)
);

class tl_berufslexikon_video extends Backend
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
	 * Check permissions to edit table tl_berufslexikon_video
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_berufslexikon_video::published', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'tl_berufslexikon_video toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	 
		$this->createInitialVersion('tl_berufslexikon_video', $intId);
	 
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_berufslexikon_video']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_berufslexikon_video']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
			}
		}
	 
		// Update the database
		$this->Database->prepare("UPDATE tl_berufslexikon_video SET tstamp=". time() .", published='" . ($blnPublished ? '' : '1') . "' WHERE id=?")
			->execute($intId);
		$this->createNewVersion('tl_berufslexikon_video', $intId);
	}
	
    /**
     * Ůdert das Aussehen des Toggle-Buttons.
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
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_berufslexikon_video::published', 'alexf'))
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
			throw new Exception($GLOBALS['TL_LANG']['tl_berufslexikon_video']['url_ungueltig']);
		};
				
		return $urlStr;
	}

}
