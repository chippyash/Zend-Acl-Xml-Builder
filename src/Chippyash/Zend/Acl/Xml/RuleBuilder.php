<?php

/*
 * Builder to build Acl from an XML file
 *
 * @copyright Ashley Kitson, UK, 2014
 * @license GPL3.0+
 */

namespace Chippyash\Zend\Acl\Xml;

use Chippyash\Zend\Acl\Xml\AbstractAclItemBuilder;
use Zend\Permissions\Acl\Acl;

/**
 * Build zend-permissions-acl rules from an XML definition
 */
class RuleBuilder extends AbstractAclItemBuilder
{

    /**
     * @override
     * @return boolean
     */
    public function buildItem()
    {
        $this->addRules($this->dom->getElementsByTagName('rule'));

        return true;
    }

    /**
     * Add rules to ACL
     * @param \DOMNodeList $rules
     */
    protected function addRules(\DOMNodeList $rules)
    {
        foreach ($rules as $rule) {
            //type is a required attribute
            $type = $this->getOpType($rule->getAttribute('type'));

            $roles = null;
            if ($rule->hasAttribute('roles')) {
                if ($rule->getAttribute('roles') != '*') {
                    $roles = explode(',', $rule->getAttribute('roles'));
                }
            }

            $resources = null;
            if ($rule->hasAttribute('resources')) {
                if ($rule->getAttribute('resources') != '*') {
                    $resources = explode(',', $rule->getAttribute('resources'));
                }
            }

            $assertion = null;
            if ($rule->hasAttribute('assertion')) {
                $assertName = $rule->getAttribute('assertion');
                $assertion = new $assertName();
            }

            $privileges = null;
            if (count($rule->childNodes) !== 0) {
                $privileges = [];
                foreach ($rule->childNodes as $privilege) {
                    $privileges[] = $privilege->nodeValue;
                }
            }

            $this->acl->setRule(Acl::OP_ADD, $type, $roles, $resources, $privileges, $assertion);
        }
    }

    /**
     * Convert XML definition of Op Type to ACL definition
     * @param string $type
     * @return string
     */
    protected function getOpType($type)
    {
        if ($type == 'ALLOW') {
            return Acl::TYPE_ALLOW;
        }

        return Acl::TYPE_DENY;
    }
}
