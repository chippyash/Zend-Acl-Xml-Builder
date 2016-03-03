<?php

/*
 * Builder to build Acl from an XML file
 *
 * @copyright Ashley Kitson, UK, 2014
 * @license GPL3.0+
 */

namespace Chippyash\Zend\Acl\Xml;

use Chippyash\BuilderPattern\Renderer\PassthruRenderer;
use Chippyash\BuilderPattern\BuilderInterface;

/**
 * Returns the built ACL
 */
class AclRenderer extends PassthruRenderer
{
    /**
     * Render the built data
     *
     * @param BuilderInterface $builder
     * @return Zend\Permissions\Acl\Acl
     */
    public function render(BuilderInterface $builder)
    {
        $results = parent::render($builder);
        return $results['acl'];
    }
}
