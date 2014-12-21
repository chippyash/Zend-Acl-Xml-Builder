<?php

/*
 * Builder to build Acl from an XML file
 *
 * @copyright Ashley Kitson, UK, 2014
 * @license GPL3.0+
 */

namespace chippyash\Zend\Acl\Xml;

use Zend\Permissions\Acl\Acl;
use chippyash\BuilderPattern\AbstractBuilder;

/**
 * Build a zend-permissions-acl from an XML definition
 */
abstract class AbstractAclItemBuilder extends AbstractBuilder
{
    /**
     * @var \DOMXpath
     */
    protected $xquery;

    /**
     * @var Zend\Permissions\Acl\Acl
     */
    protected $acl;

    /**
     * Constructor
     *
     * @param \DOMXPath $xquery
     * @param AclInterface $acl
     */
    public function __construct(\DOMXPath $xquery, Acl $acl)
    {
        $this->xquery = $xquery;
        $this->acl = $acl;
        parent::__construct();
    }

    /**
     * @override
     */
    protected function setBuildItems()
    {
        $this->buildItems = [
            'result' => function() {
                return $this->buildItem();
            }
        ];
    }

    /**
     * Build the Acl item and return true if successfull else false
     *
     * @return bool
     */
    abstract public function buildItem();
}
