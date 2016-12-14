<?php
namespace Chippyash\Test\Zend\Acl\Xml\Translate;

use Chippyash\Zend\Acl\Xml\Translate\Xsl;
use org\bovigo\vfs\vfsStream;

class XslTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var Xsl
     */
    protected $sut;

    protected function setUp()
    {
        $this->sut = 'foo';
    }

    public function testCase()
    {

    }

    protected function getXslPath()
    {
        $xsl = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
            version="1.0">
    <xsl:template name="test">
    
    </xsl:template>
</xsl:stylesheet>
EOT;


    }
}
