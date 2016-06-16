<?php
/*
 * Builder to build Acl from an XML file
 *
 * @copyright Ashley Kitson, UK, 2014
 * @license GPL3.0+
 */
namespace Chippyash\Zend\Acl\Xml;

use Chippyash\BuilderPattern\AbstractDirector;
use Chippyash\Type\String\StringType;
use Zend\Permissions\Acl\Acl;

/**
 * Build director to build Zend Acl from XML file
 */
class AclDirector extends AbstractDirector
{
    public function __construct(StringType $xmlFile)
    {
        parent::__construct(
            new AclBuilder(
                $xmlFile,
                new Acl()
            ),
            new AclRenderer()
        );
    }
}
