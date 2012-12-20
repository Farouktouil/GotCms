<?php
namespace Datatypes\Mixed;

use Gc\Datatype\Model as DatatypeModel;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-10-17 at 20:42:12.
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 * @group Datatypes
 */
class PrevalueEditorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PrevalueEditor
     */
    protected $_object;

    /**
     * @var DatatypeModel
     */
    protected $_datatype;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {

        $this->_datatype = DatatypeModel::fromArray(array(
            'name' => 'MixedTest',
            'prevalue_value' => 'a:1:{s:9:"datatypes";a:1:{i:0;a:3:{s:4:"name";s:10:"Textstring";s:5:"label";s:4:"Test";s:6:"config";a:1:{s:6:"length";s:0:"";}}}}',
            'model' => 'Mixed',
        ));
        $this->_datatype->save();
        $datatype = new Datatype();
        $datatype->load($this->_datatype);
        $this->_object = $datatype->getPrevalueEditor();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_datatype->delete();
        unset($this->_datatype);
        unset($this->_object);
    }

    /**
     * @covers Datatypes\Mixed\PrevalueEditor::save
     * @covers Datatypes\Mixed\PrevalueEditor::_getDatatype
     */
    public function testSave()
    {
        $post = $this->_object->getRequest()->getPost();
        $post->set('add-model', 'Textstring');
        $post->set('datatypes', array(
            1 => array(
                'name' => 'Textstring',
                'label' => 'TextstringTest',
                'length' => 25,
            ),
        ));
        $this->assertNull($this->_object->save());
    }

    /**
     * @covers Datatypes\Mixed\PrevalueEditor::load
     * @covers Datatypes\Mixed\PrevalueEditor::_getDatatype
     */
    public function testLoad()
    {
        $this->assertInternalType('string', $this->_object->load());
    }
}
