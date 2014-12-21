<?php
/*
 * A test stub for unit testing
 */

namespace chippyash\Test\Zend\Acl\Xml\Stubs;

use Zend\Permissions\Acl\Assertion\AssertionInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Implement an assertion to prove it can be used in acl definition
 */
class TestAssertionStub implements AssertionInterface
{
    public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null) {
        return true;
    }
}
