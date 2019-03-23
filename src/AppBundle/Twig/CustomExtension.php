<?php

namespace AppBundle\Twig;

/**
 * Class CustomExtension
 * @package AppBundle\Twig
 */
class CustomExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('parse_rut', array($this, 'parseRut')),
            );
    }

    /**
     * @param $strRut
     * @return string
     */
    public function parseRut($strRut)
    {
        $strRut = str_replace('.', '', $strRut);
        
        $arrExp = explode( "-", $strRut );
        if ( COUNT( $arrExp ) != 2 ) {
            $strRut = str_replace('-', '', $strRut);
            $SplRut = str_split( $strRut );
            $lenRut = count($SplRut);
            $auxRut = '';
            foreach ($SplRut as $pos => $char) {
                $auxRut = $auxRut.$char;
                if ($pos == ($lenRut - 2)) {
                    $auxRut = $auxRut.'-';
                }
            }
            $strRut = $auxRut;
        }
        $arrRut = explode( "-", $strRut );
        return number_format( $arrRut[0], 0, "", ".") . '-' . $arrRut[1];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'custom_extension';
    }
}