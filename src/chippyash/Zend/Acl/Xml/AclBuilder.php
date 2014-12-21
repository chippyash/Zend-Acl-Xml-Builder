<?php

/*
 * Builder to build Acl from an XML file
 *
 * @copyright Ashley Kitson, UK, 2014
 * @license GPL3.0+
 */

namespace chippyash\Zend\Acl\Xml;

use Zend\Permissions\Acl\Acl;
use chippyash\Type\String\StringType;
use chippyash\BuilderPattern\AbstractBuilder;
use chippyash\Zend\Acl\Xml\Exceptions\AclBuilderException;

/**
 * Build a zend-permissions-acl from an XML definition
 */
class AclBuilder extends AbstractBuilder
{
    const ERR_NO_XML = 'ACL definition XML does not exist';
    const ERR_INVALID_ACL = 'ACL definition is invalid: %s';

    /**
     * Constructor
     *
     * @param StringType $xmlFile
     * @param Acl $acl
     */
    public function __construct(StringType $xmlFile, Acl $acl)
    {
        parent::__construct();

        $xsdFile = __DIR__ . '/xsd/zendacl.xsd';
        $this->xquery = $this->loadDefinition($xmlFile(), $xsdFile);
        $this->acl = $acl;
        $this->roles = new RoleBuilder($this->xquery, $this->acl);
        $this->resources = new ResourceBuilder($this->xquery, $this->acl);
        $this->rules = new RuleBuilder($this->xquery, $this->acl);
    }

    /**
     * Load XML definition from file
     *
     * @param string $xmlFile
     * @param string $xsdFile
     *
     * @return \DOMXPath
     *
     * @throws AclBuilderBuilderException
     */
    protected function loadDefinition($xmlFile, $xsdFile)
    {
        if (!file_exists($xmlFile)) {
            throw new AclBuilderException(self::ERR_NO_XML);
        }

        $defDom = new \DOMDocument();
        $defDom->load($xmlFile);
        $prevSetting = \libxml_use_internal_errors(true);
        if (!$defDom->schemaValidate($xsdFile)) {
            $errors = \libxml_get_errors();
            $errMsg = '';
            foreach ($errors as $error) {
                $errMsg .= $this->libxml_display_error($error);
            }
            throw new AclBuilderException(sprintf(self::ERR_INVALID_ACL, $errMsg));
        }
        \libxml_use_internal_errors($prevSetting);

        return new \DOMXPath($defDom);
    }

    /**
     * @override
     */
    protected function setBuildItems()
    {
        $this->buildItems = [
            'xquery' => null,
            'acl' => null,
            'roles' => null,
            'resources' => null,
            'rules' => null,
        ];
    }

    /**
     * @link http://php.net/manual/en/domdocument.schemavalidate.php
     * @param type $error
     * @return type
     */
    protected function libxml_display_error(\LibXMLError $error)
    {
        $return = "";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Warning $error->code: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "Error $error->code: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error $error->code: ";
                break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            $return .= " in $error->file";
        }
        $return .= " on line $error->line ";

        return $return;
    }
}
