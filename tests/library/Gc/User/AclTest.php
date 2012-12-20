<?php
namespace Gc\User;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-10-17 at 20:40:08.
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 * @group Gc
 */
class AclTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Acl
     */
    protected $_object;
    /**
     * @var Model
     */
    protected $_user;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @covers Gc\User\Acl::__construct
     * @covers Gc\User\Acl::_roleResource
     * @covers Gc\User\Acl::_initResources
     * @covers Gc\User\Acl::_initRoles
     */
    protected function setUp()
    {
        $this->_user = Model::fromArray(array(
            'lastname' => 'Test',
            'firstname' => 'Test',
            'email' => 'test@test.com',
            'login' => 'test-user-model',
            'user_acl_role_id' => 1,
        ));

        $this->_user->setPassword('test-user-model-password');
        $this->_user->save();
        $this->_object = new Acl($this->_user);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_user->delete();
        unset($this->_object);
        unset($this->_user);
    }

    /**
     * @covers Gc\User\Acl::listRoles
     */
    public function testListRoles()
    {
        $this->assertInternalType('array', $this->_object->listRoles());
    }

    /**
     * @covers Gc\User\Acl::getRoleId
     */
    public function testGetRoleId()
    {
        $this->assertInstanceOf('ArrayObject', $this->_object->getRoleId('Administrator'));
    }

    /**
     * @covers Gc\User\Acl::listResources
     */
    public function testListResources()
    {
        $this->assertInternalType('array', $this->_object->listResources());
    }

    /**
     * @covers Gc\User\Acl::listResourcesByGroup
     */
    public function testListResourcesByGroup()
    {
        $this->assertInternalType('array', $this->_object->listResourcesByGroup('Development'));
    }
}
