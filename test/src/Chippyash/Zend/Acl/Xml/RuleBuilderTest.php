<?php

namespace Chippyash\Test\Zend\Acl\Xml;

use Chippyash\Zend\Acl\Xml\RuleBuilder;
use Chippyash\Zend\Acl\Xml\ResourceBuilder;
use Chippyash\Zend\Acl\Xml\RoleBuilder;
use Zend\Permissions\Acl\Acl;

require_once __DIR__ . '/Stubs/TestResourceStub.php';
require_once __DIR__ . '/Stubs/TestRoleStub.php';
require_once __DIR__ . '/Stubs/TestAssertionStub.php';

/**
 * More of a component test than a unit test as Rules have a dependency
 * on roles and resources having been defined first
 */
class RuleBuilderTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var RuleBuilder
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
        $dom->validateOnParse = true;
        $dom->load(__DIR__ . '/fixtures/test.xml', LIBXML_NONET | (defined('LIBXML_COMPACT') ? LIBXML_COMPACT : 0));
        $this->object = new RuleBuilder($dom, $this->acl);
        $roleBuilder = new RoleBuilder($dom, $this->acl);
        $roleBuilder->build();
        $resourceBuilder = new ResourceBuilder($dom, $this->acl);
        $resourceBuilder->build();
    }

    public function testBuildItemWillAddRulesToAcl()
    {
        $this->assertFalse($this->acl->isAllowed('guest','login'));
        $this->assertFalse($this->acl->isAllowed('user',null,'GET'));
        $this->assertTrue($this->object->buildItem());
        $this->assertTrue($this->acl->isAllowed('guest', 'login'));
        $this->assertTrue($this->acl->isAllowed('user',null,'GET'));
    }
}
