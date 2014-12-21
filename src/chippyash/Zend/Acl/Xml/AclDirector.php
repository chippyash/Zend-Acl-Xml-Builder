<?php

/*
 * Builder to build Acl from an XML file
 *
 * @copyright Ashley Kitson, UK, 2014
 * @license GPL3.0+
 */

namespace chippyash\Zend\Acl\Xml;

use chippyash\BuilderPattern\AbstractDirector;
use chippyash\Type\String\StringType;
use chippyash\Zend\Acl\Xml\AclBuilder;
use chippyash\Zend\Acl\Xml\AclRenderer;
use Zend\Permissions\Acl\Acl;

/**
 * Build director to build Zend Acl from XML file
 */
class AclDirector extends AbstractDirector
{
    public function __construct(StringType $xmlFile)
    {
        parent::__construct(
                new AclBuilder($xmlFile, new Acl()), new AclRenderer());
    }
}
