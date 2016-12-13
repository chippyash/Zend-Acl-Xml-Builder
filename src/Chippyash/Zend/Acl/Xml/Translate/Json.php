<?php
declare(strict_types = 1);
/**
 * Zend-Acl-Builder
 *
 * @author    Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license   GPL V3+ See LICENSE.md
 */
namespace Chippyash\Zend\Acl\Xml\Translate;

use Chippyash\Type\String\StringType;

class Json implements TranslateInterface
{
    /**
     * @param StringType $sourceXmlFile
     *
     * @return string  XML converted to JSON equivalent
     */
    public function translate(StringType $sourceXmlFile)
    {
        // TODO: Implement translate() method.
    }
}