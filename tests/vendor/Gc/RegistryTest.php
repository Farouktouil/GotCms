<?php
namespace Gc;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-10-17 at 20:40:09.
 */
class RegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Registry
     */
    protected $_object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_object = new Registry;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object->_unsetInstance();
    }

    /**
     * @covers Gc\Registry::getInstance
     */
    public function testGetInstance()
    {
        $this->tearDown();
        $this->assertInstanceOf('Registry', Registry::getInstance());
    }

    /**
     * @covers Gc\Registry::setInstance
     */
    public function testSetInstance()
    {
        // Remove the following lines when you implement this test.
        $this->_object->setInstance(new Registry);
        $this->assertInstanceOf('Registry', Registry::getInstance());
    }

    /**
     * @covers Gc\Registry::get
     */
    public function testGet()
    {
        $this->_object->set('key', 'value');
        $this->assertEquals('value', $this->_object->get('key'));
    }

    /**
     * @covers Gc\Registry::set
     */
    public function testSet()
    {
        $this->_object->set('key', 'value');
        $this->assertEquals('value', $this->_object->get('key'));
    }

    /**
     * @covers Gc\Registry::isRegistered
     */
    public function testIsRegisteredWithData()
    {
        $this->_object->set('key', 'value');
        $this->assertTrue($this->_object->isRegistered('key'));
    }

    /**
     * @covers Gc\Registry::isRegistered
     */
    public function testIsRegisteredWithoutData()
    {
        $this->tearDown();
        $this->assertTrue(!$this->_object->isRegistered('key'));
    }

    /**
     * @covers Gc\Registry::offsetExists
     */
    public function testOffsetExists()
    {
        $this->_object->set('key', 'value');
        $this->assertTrue($this->_object->isRegistered('key'));
    }
}