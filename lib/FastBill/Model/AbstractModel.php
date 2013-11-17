<?php

namespace FastBill\Model;

use DomainException;
use BadMethodCallException;
use stdClass;

abstract class AbstractModel {

  /**
   * @var array key => XML_NAME_IN_UPPERCASE value => xmlNameInCamelCase
   */
  protected static $xmlProperties = array(); // overwrite this

  private $properties = array();

  private $name;

  protected $collections = array();

  protected function __construct(Array $properties) {
    $this->properties = $this->readProperties($properties);

    $parts = explode('\\', get_class($this));
    $this->name = array_pop($parts);
  }

  protected function readProperties(Array $properties) {
    foreach ($this->collections as $propertyName => $fqn) {
      $collection = array();

      if (is_array($properties[$propertyName])) {
        foreach ($properties[$propertyName] as $collectionItemProperties) {
          $collection[] = $fqn::fromObject($collectionItemProperties);
        }
      }

      $properties[$propertyName] = $collection;
    }

    return $properties;
  }

  private function isCollection($propertyName) {
    return array_key_exists($propertyName, $this->collections);
  }

  public static function fromArray(Array $properties) {
    $propertyNames = array_values(static::$xmlProperties);

    $propertiesValues = array_replace(
      array_combine(
        $propertyNames,
        array_fill(0, count(static::$xmlProperties), NULL)
      ),
      $properties
    );

    return new static($propertiesValues);
  }

  /**
   * Returns the instance for the object with the XMLNames found in $xmlObject
   * 
   * @param $xmlObject but it reads the jsonProperties as well
   * @return static
   */
  public static function fromObject(stdClass $object) {
    $propertiesValues = array();
    foreach (static::$xmlProperties as $xmlName => $propertyName) {

      if (isset($object->$xmlName)) {
        $value = $object->$xmlName;

      } elseif (isset($object->$propertyName)) {
        $value = $object->$propertyName;

      } else {
        $value = NULL;
      }

      $propertiesValues[$propertyName] = $value;
    }

    return new static($propertiesValues);
  }

  private function getProperty($name) {
    if (!array_key_exists($name, $this->properties)) {
      throw new DomainException(sprintf("The property: '%s' does not exist on %s", $name, get_class($this)));
    }

    return $this->properties[$name];
  }

  private function setProperty($name, $value) {
    if (!array_key_exists($name, $this->properties)) {
      throw new DomainException(sprintf("The property: '%s' does not exist on %s", $name, get_class($this)));
    }

    $this->properties[$name] = $value;
    return $this;
  }

  /**
   * @return stdClass the keys are  IN_UPPER_CASE
   */
  public function serializeJSONXML() {
    $json = new \stdClass;

    foreach (static::$xmlProperties as $xmlName => $name) {
      $json->$xmlName = $this->serializeProperty($name, $this->properties[$name], 'serializeJSONXML');
    }

    return $json;
  }

  /**
   * @return stdClass the keys are inCamelCase
   */
  public function serializeJSON() {
    $json = new \stdClass;

    foreach (static::$xmlProperties as $xmlName => $name) {
      $json->$name = $this->serializeProperty($name, $this->properties[$name], 'serializeJSON');
    }

    return $json;
  }

  protected function serializeProperty($name, $value, $serialize) {
    if ($this->isCollection($name)) {
      return array_map(function ($member) use ($serialize) {
        return $member->$serialize();
      }, $value);
    } else {
      return $value;
    }
  }

  public function __call($method, $args = NULL) {
    $prop = mb_strtolower(mb_substr($method,3,1)).mb_substr($method,4); // ucfirst in mb_string
    
    if (mb_strpos($method, 'get') === 0) {
      return $this->getProperty($prop);
    }

    if (mb_strpos($method, 'set') === 0) {
      $args = (array) $args;
      array_unshift($args, $prop);
      return call_user_func_array(array($this, 'setProperty'), $args);
    }
  
    throw new BadMethodCallException('Call to undefined method '.get_class($this).'::'.$method.'()');
  }

  public function __toString() {
    return __NAMESPACE__.': '.$this->name;
  }
}
