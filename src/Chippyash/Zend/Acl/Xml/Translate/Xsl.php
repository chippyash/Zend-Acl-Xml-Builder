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

/**
 * Translate using XSL transformation
 */
class Xsl implements TranslateInterface
{
    /**
     * Path to XSL file
     * @var string
     */
    protected $xslFile;

    /**
     * Json constructor.
     *
     * @param StringType $xsfFile Path to XSL file
     */
    public function __construct(StringType $xsfFile)
    {
        $this->xslFile = $xsfFile();
    }

    /**
     * @param StringType $sourceXmlFile Path to XML file
     *
     * @return mixed  XML converted to some equivalent
     */
    public function translate(StringType $sourceXmlFile)
    {
        $xsl = new \XSLTProcessor();
        $dom = new \DOMDocument();
        $dom->load($sourceXmlFile());
        $translated = $xsl->transformToXml($dom);

        return $translated;
    }
}