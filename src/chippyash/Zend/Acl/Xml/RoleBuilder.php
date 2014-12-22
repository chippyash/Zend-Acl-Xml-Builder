<?php

/*
 * Builder to build Acl from an XML file
 * 
 * @copyright Ashley Kitson, UK, 2014
 * @license GPL3.0+
 */

namespace chippyash\Zend\Acl\Xml;

use chippyash\Zend\Acl\Xml\AbstractAclItemBuilder;

/**
 * Build zend-permissions-acl roles from an XML definition
 */
class RoleBuilder extends AbstractAclItemBuilder
{

    /**
     * @override
     * @return boolean
     */
    public function buildItem()
    {
        $this->addRoles($this->dom->getElementsByTagName('role'));

        return true;
    }

    /**
     * Add roles to ACL
     * 
     * @param \DOMNodeList $roles
     */
    protected function addRoles(\DOMNodeList $roles)
    {
        foreach ($roles as $role) {
            $parents = null;
            if ($role->hasAttribute('parents')) {
                $parents = explode(',', $role->getAttribute('parents'));
            }

            $roleType = 'GenericRole';
            if ($role->hasAttribute('type')) {
                $roleType = $role->getAttribute('type');
            }

            $roleName = $role->nodeValue;

            if ($roleType !== 'GenericRole') {
                $this->acl->addRole(new $roleType($roleName), $parents);
            } else {
                $this->acl->addRole($roleName, $parents);
            }
        }
    }
}
