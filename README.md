# chippyash/zend-acl-xml-builder

## Quality Assurance

[![Build Status](https://travis-ci.org/chippyash/Zend-Acl-Xml-Builder.svg?branch=master)](https://travis-ci.org/chippyash/Zend-Acl-Xml-Builder)
[![Coverage Status](https://coveralls.io/repos/chippyash/Zend-Acl-Xml-Builder/badge.png)](https://coveralls.io/r/chippyash/Zend-Acl-Xml-Builder)

Certified for PHP 5.5+

[Test Contract](https://github.com/chippyash/Zend-Acl-Xml-Builder/blob/master/docs/Test-Contract.md) in the docs directory.

## What?

Provides the ability to specify a [Zend ACL](http://framework.zend.com/manual/current/en/modules/zend.permissions.acl.intro.html) 
using XML. The XML is validated by an XSD file which you can also use in your XML 
editor

The library is released under the [GNU GPL V3 or later license](http://www.gnu.org/copyleft/gpl.html)

## Why?

Zend/Permissions/Acl is great and lightweight way of providing access control to your
applications, but it can be a PITA to configure using the native command set. As
it happens, it is ideally placed, because of its structure, to be driven by an XML
configuration.  

This also means that the ACL can be managed by some third party application or service.  
It is not beyond the wit of most to be able to write an XSL translation for 
instance, that takes a definition from LDAP and converts to this library format 
to be able then to control the ACL from your organisation's LDAP servers.

## When

The current library simply handles reading an XML file and returning an ACL.
Down the road, I can see:

- taking XML content as input so the builder can be chained into a sequence of events
- adding caching as an option
- providing the XSD as a publicly available namespace

## How

To understand how to use it, see the test files, in particular, take a look at
AclDirectorTest as a starting point and work down from there.

In essence you need to do two things

1. Provide an XML definition of the ACL
2. Tell the Director where the XML is

This library depends on the [Builder Pattern](https://github.com/chippyash/Builder-Pattern)
and in turn the [Strong Type](https://github.com/chippyash/Strong-Type) libraries.

### Defining the XML

For the canonical truth, study the XSD file located in src/chippyash/Zend/Acl/Xml/xsd.

There is also an example XML file used for testing located in test/src/chippyash/Zend/Acl/Xml/fixtures.

Essentially, Zend-ACL defines the ACL in three parts:

* Roles
* Resources
* Rules

Whilst you can define Roles and Resources independently, Rules require that you
have already defined Roles and Resources to act on.  Rules also allow you to set
additional privileges and assertions.  

To provide an ACL you must specify all three parts.

#### Roles

- A role can have the following optional attributes:
    - type: string: default = "GenericRole". Name of your specialized role class. The default
uses the Zend GenericRole
    - parents: string: default = none. Comma separated list of names of parents for this role
- Content for the role element is the role name

#### Resources

- A resource can have the following optional attributes:
    - type: string: default = "GenericResource". Name of your specialized resource class. The default
uses the Zend GenericResource
    - parent: string: default = none. Name of parent for this resource. NB. unlike roles,
resources may only have a single parent.
- Content for the resource element is the resource name

#### Rules

- A rule has an obligatory attribute:
    - type: string: one of 'ALLOW' or 'DENY'
- A rule has the following optional arguments:
    - roles: string: default = "*". comma separated list of role names that the rule applies to
    - resources: string: default = "*". comma separated list of resource names that the rule applies to
    - assertion: string: default = none. Fully namespaced class providing the assertion.  
The class must exist and implement the Zend\Permissions\Acl\Assertion\AssertionInterface.
You can find an example in test/src/chippyash/Zend/Acl/Xml/Stubs
- a rule can contain optional \<privilege\> elements. Each \<privilege\> element contains
the name of an arbitrary privilege.

#### NB

All definition items are processed in the order that they appear in the XML file.

### Build the ACL

<pre>
use chippyash\Zend\Acl\Xml\AclDirector;
use chippyash\Type\String\StringType;

$location = new StringType('/location/of/my/acl.xml');
$director = new AclDirector($location);
$acl = $director->build();
</pre>

### Changing the library

1.  fork it
2.  write the test
3.  amend it
4.  do a pull request

Found a bug you can't figure out?

1.  fork it
2.  write the test
3.  do a pull request

NB. Make sure you rebase to HEAD before your pull request

## Where?

The library is hosted at [Github](https://github.com/chippyash/Zend-Acl-Xml-Builder). It is
available at [Packagist.org](https://packagist.org/packages/chippyash/zend-acl-xml-builder)

### Installation

Install [Composer](https://getcomposer.org/)

#### For production

add

<pre>
    "chippyash/zend-acl-xml-builder": "~1.0"
</pre>

to your composer.json "requires" section

#### For development

Clone this repo, and then run Composer in local repo root to pull in dependencies

<pre>
    git clone git@github.com:chippyash/Zend-Acl-Xml-Builder.git ZendAclBuilder
    cd ZendAclBuilder
    composer install --dev
</pre>

To run the tests:

<pre>
    cd ZendAclBuilder
    vendor/bin/phpunit -c test/phpunit.xml test/
</pre>

## Other stuff

Check out the other packages at [my blog site](http://the-matrix.github.io/packages/) for more PHP stuff;

## History

V0...  pre releases

