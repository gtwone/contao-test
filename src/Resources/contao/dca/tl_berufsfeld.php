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
$GLOBALS['TL_DCA']['tl_berufsfeld'] = array
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
				'label'               => &$GLOBALS['TL_LANG']['tl_berufsfeld']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_berufsfeld']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_berufsfeld']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_beruf']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_berufsfeld', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_berufsfeld']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'bezeichnung,lang;{header_legend},multiSRC;{beschreibung_legend},beschreibung;{deberufsfeld_legend},DeBerufsfeld;{experten_einstellungen},keywords,ProfessionAreaId;{einstellungen_legend:hide},published;'
		
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
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufsfeld']['bezeichnung'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'keywords' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufsfeld']['keywords'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class' => 'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'ProfessionAreaId' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufsfeld']['ProfessionAreaId'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class' => 'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'beschreibung' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufsfeld']['beschreibung'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'					  => array('rte' => 'tinyMCE'),
			'sql'                     => "text NULL",
		),
		'multiSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufsfeld']['header'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('multiple'=>true, 'fieldType'=>'checkbox', 'files'=>true, 'isGallery' => true),
			'sql'                     => "blob NULL"
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufsfeld']['published'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('isBoolean'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'lang' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufsfeld']['lang'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'select',
			'options'                 => array('de', 'fr-CH'),
			'eval'      			  => array(
										'mandatory'          => true,
										'tl_class'           => 'w50',
			),
			'sql'                     => "varchar(10) NOT NULL default 'de'"
		),
		'DeBerufsfeld' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_berufsfeld']['DeBerufsfeld'],
			'exclude'				  => true,
			'inputType'				  => 'select',
			'eval'                    => array('tl_class'=>'w50', 'chosen'=>true, 'includeBlankOption'=>true),
			'sql'                     => "int(10) NULL",
			'options_callback'        => array('tl_berufsfeld', 'getBerufsfelderDe')
		)
		
	)
);

class tl_berufsfeld extends Backend
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
	 * Get all tl_berufsfeld with lang=de
	 */
	public function getBerufsfelderDe()
	{
		$objBerufsfelder = \BerufsfeldModel::findBy(
			array('lang="de"'),
			$varValue = '',
			array('order'=>'bezeichnung')
		);
		while($objBerufsfelder->next()){
			$myBerufsfelder[$objBerufsfelder->id] = $objBerufsfelder->bezeichnung;
		}
		return $myBerufsfelder;
	}
	
	/**
	 * Check permissions to edit table tl_berufsfeld
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_berufsfeld::published', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'tl_berufsfeld toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	 
		$this->createInitialVersion('tl_berufsfeld', $intId);
	 
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_berufsfeld']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_berufsfeld']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
			}
		}
	 
		// Update the database
		$this->Database->prepare("UPDATE tl_berufsfeld SET tstamp=". time() .", published='" . ($blnPublished ? '' : '1') . "' WHERE id=?")
			->execute($intId);
		$this->createNewVersion('tl_berufsfeld', $intId);
	}
	
    /**
     * Ändert das Aussehen des Toggle-Buttons.
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
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_berufsfeld::published', 'alexf'))
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
}
