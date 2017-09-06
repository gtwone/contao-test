<?php
$GLOBALS['TL_DCA']['tl_module']['palettes']['berufslexikon_berufsfelder']   = '{title_legend},name,type;{redirect_legend},jumpTo;{protected_legend:hide},protected;{expert_legend:hide},berufslexikonTitle,berufslexikonDesc,guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['berufslexikon_berufsliste']   = '{title_legend},name,type;{protected_legend:hide},protected;{expert_legend:hide},berufslexikonTitle,berufslexikonDesc,guests,cssID,space';

array_insert(
    $GLOBALS['TL_DCA']['tl_module']['fields'],
    3,
    array('berufslexikonDesc' => array
            (
                'label'                   => &$GLOBALS['TL_LANG']['MOD']['berufslexikonDesc'],
                'exclude'                 => true,
                'search'                  => true,
                'inputType'               => 'text',
                'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'long'),
                'sql'                     => "varchar(255) NOT NULL default ''"
            ))
        
    
);
array_insert(
    $GLOBALS['TL_DCA']['tl_module']['fields'],
    4,
    array('berufslexikonTitle' => array
            (
                'label'                   => &$GLOBALS['TL_LANG']['MOD']['berufslexikonTitle'],
                'exclude'                 => true,
                'search'                  => true,
                'inputType'               => 'text',
                'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'long'),
                'sql'                     => "varchar(255) NOT NULL default ''"
            ))
        
    
);

?>