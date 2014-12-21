<?php

namespace chippyash\Test\Zend\Acl\Xml;

use chippyash\Zend\Acl\Xml\ResourceBuilder;
use Zend\Permissions\Acl\Acl;

require_once __DIR__ . '/Stubs/TestResourceStub.php';

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-12-21 at 10:55:51.
 */
class ResourceBuilderTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ResourceBuilder
     */
    protected $object;
    /**
     *
     * @var Acl
     */
    protected $acl;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->acl = new Acl();
        $dom = new \DOMDocument();
        $dom->load(__DIR__ . '/fixtures/test.xml');
        $xquery = new \DOMXPath($dom);
        $this->object = new ResourceBuilder($xquery, $this->acl);
    }

    public function testBuildItemWillAddResourcesToAcl()
    {
        $this->assertFalse($this->acl->hasResource('logout'));
        $this->assertTrue($this->object->buildItem());
        $this->assertTrue($this->acl->hasResource('logout'));
    }
}
