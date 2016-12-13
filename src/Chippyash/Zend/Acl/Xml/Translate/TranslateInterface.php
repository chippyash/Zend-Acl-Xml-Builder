<?php
/**
 * Zend-Acl-Builder
 *
 * @author    Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license   GNU GPL V3+
 */
namespace Chippyash\Zend\Acl\Xml\Translate;

use Chippyash\Type\String\StringType;

/**
 * Interface TranslateInterface
 * Translate from XML to some other format
 */
interface TranslateInterface
{
    /**
     * Translate XML source file to some other format
     *
     * @param StringType $sourceXmlFile
     *
     * @return mixed
     */
    public function translate(StringType $sourceXmlFile);

}