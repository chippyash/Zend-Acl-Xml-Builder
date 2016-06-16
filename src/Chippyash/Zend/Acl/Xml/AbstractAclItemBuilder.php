<?php

/*
 * Builder to build Acl from an XML file
 *
 * @copyright Ashley Kitson, UK, 2014
 * @license GPL3.0+
 */

namespace Chippyash\Zend\Acl\Xml;

use Zend\Permissions\Acl\Acl;
use Chippyash\BuilderPattern\AbstractBuilder;
use Zend\Permissions\Acl\AclInterface;

/**
 * Build a zend-permissions-acl from an XML definition
 */
abstract class AbstractAclItemBuilder extends AbstractBuilder
{
    /**
     * @var \DOMDocument
     */
    protected $dom;

    /**
     * @var Acl
     */
    protected $acl;

    /**
     * Constructor
     *
     * @param \DOMDocument $dom
     * @param Acl|AclInterface $acl
     *
     * @internal param \DOMDocument $xquery
     */
    public function __construct(\DOMDocument $dom, Acl $acl)
    {
        $this->dom = $dom;
        $this->acl = $acl;
        parent::__construct();
    }

    /**
     * @override
     */
    protected function setBuildItems()
    {
        $this->buildItems = [
            'result' => function () {
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
