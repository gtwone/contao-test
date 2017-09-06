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
$GLOBALS['TL_DCA']['tl_berufslexikon_links'] = array
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
				'label'               => &$GLOBALS['TL_LANG']['tl_berufslexikon_links']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_berufslexikon_links']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_beruf']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_berufslexikon_links', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_berufslexikon_links']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'bezeichnung,url,berufsfeld,published;'
		
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
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_links']['bezeichnung'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_links']['url'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'					  => array('mandatory'=>true, 'rgxp'=>'url'),
			'sql'                     => "varchar(255) NOT NULL default ''",
		),
		'berufsfeld' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_beruf']['berufsfeld'],
			'exclude'				  => true,
			'inputType'				  => 'select',
			'eval'                    => array('multiple'=>true, 'tl_class'=>'long', 'chosen'=>true),
			'sql'                     => "text NULL",
			'foreignKey'              => 'tl_berufsfeld.bezeichnung',
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufslexikon_links']['published'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('isBoolean'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);

class tl_berufslexikon_links extends Backend
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
	 * Check permissions to edit table tl_berufslexikon_links
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_berufslexikon_links::published', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'tl_berufslexikon_links toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	 
		$this->createInitialVersion('tl_berufslexikon_links', $intId);
	 
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_berufslexikon_links']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_berufslexikon_links']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
			}
		}
	 
		// Update the database
		$this->Database->prepare("UPDATE tl_berufslexikon_links SET tstamp=". time() .", published='" . ($blnPublished ? '' : '1') . "' WHERE id=?")
			->execute($intId);
		$this->createNewVersion('tl_berufslexikon_links', $intId);
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
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_berufslexikon_links::published', 'alexf'))
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
