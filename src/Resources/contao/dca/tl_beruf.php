<?php
/**
 * Table tl_beruf
 */
$GLOBALS['TL_DCA']['tl_beruf'] = array
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
				'label'               => &$GLOBALS['TL_LANG']['tl_beruf']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_beruf']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_beruf']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_beruf']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_beruf']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_beruf', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_beruf']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'bezeichnung,lang,multiSRC;{beschreibung_legend},beschreibung;{anforderung_legend},anforderung;{ausbildung_legend},ausbildung;{zukunft_legend},zukunft;{relationen_legend:hide},berufsfeld,beruf_verwandt;{deberuf_legend},DeBeruf,link_joboffers;{experten_einstellungen},keywords,ProfessionId;einstellungen;{einstellungen_legend:hide},published;'
		
	),


	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'bezeichnung' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_beruf']['bezeichnung'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'keywords' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_beruf']['keywords'],
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
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class' => 'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'ProfessionId' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_berufsfeld']['ProfessionId'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class' => 'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'beschreibung' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_beruf']['beschreibung'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'					  => array('rte' => 'tinyMCE'),
			'sql'                     => "text NULL",
		),
		'multiSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_beruf']['multiSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('multiple'=>true, 'fieldType'=>'checkbox', 'files'=>true, 'isGallery' => true),
			'sql'                     => "blob NULL",
		),
		'anforderung' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_beruf']['anforderung'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'					  => array('rte' => 'tinyMCE'),
			'sql'                     => "text NULL",
		),
		'ausbildung' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_beruf']['ausbildung'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'					  => array('rte' => 'tinyMCE'),
			'sql'                     => "text NULL",
		),
		'zukunft' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_beruf']['zukunft'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'					  => array('rte' => 'tinyMCE'),
			'sql'                     => "text NULL",
		),
		'berufsfeld' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_beruf']['berufsfeld'],
			'exclude'				  => true,
			'inputType'				  => 'select',
			'eval'                    => array('multiple'=>true, 'tl_class'=>'w50', 'chosen'=>true),
			'sql'                     => "text NULL",
			'foreignKey'              => 'tl_berufsfeld.bezeichnung',
		),
		'beruf_verwandt' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_beruf']['beruf_verwandt'],
			'exclude'				  => true,
			'inputType'				  => 'select',
			'eval'                    => array('multiple'=>true, 'tl_class'=>'w50', 'chosen'=>true),
			'sql'                     => "text NULL",
			'foreignKey'              => 'tl_beruf.bezeichnung',
            'relation'                => array('type'=>'hasMany', 'load'=>'eager')
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_beruf']['published'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('isBoolean'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'lang' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_beruf']['lang'],
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
		'DeBeruf' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_beruf']['DeBeruf'],
			'exclude'				  => true,
			'inputType'				  => 'select',
			'eval'                    => array('tl_class'=>'w50', 'chosen'=>true, 'includeBlankOption'=>true),
			'sql'                     => "int(10) NULL",
			'options_callback'        => array('tl_beruf', 'getBerufeDe')
		),
		'link_joboffers' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_beruf']['link_joboffers'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		)
	)
);


/**
 * Class tl_beruf
 */
class tl_beruf extends Backend
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
	 * Get all tl_beruf with lang=de
	 */
	public function getBerufeDe()
	{
		$objBerufe = \BerufModel::findBy(
			array('lang="de"'),
			$varValue = '',
			array('order'=>'bezeichnung')
		);
		while($objBerufe->next()){
			$myBerufe[$objBerufe->id] = $objBerufe->bezeichnung;
		}
		return $myBerufe;
	}
	
	/**
	 * Check permissions to edit table tl_beruf
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_beruf::published', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'tl_beruf toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	 
		$this->createInitialVersion('tl_beruf', $intId);
	 
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_beruf']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_beruf']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
			}
		}
	 
		// Update the database
		$this->Database->prepare("UPDATE tl_beruf SET tstamp=". time() .", published='" . ($blnPublished ? '' : '1') . "' WHERE id=?")
			->execute($intId);
		$this->createNewVersion('tl_beruf', $intId);
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
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_beruf::published', 'alexf'))
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


