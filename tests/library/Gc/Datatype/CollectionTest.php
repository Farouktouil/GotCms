<?php
/**
 * This source file is part of GotCms.
 *
 * GotCms is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * GotCms is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with GotCms. If not, see <http://www.gnu.org/licenses/lgpl-3.0.html>.
 *
 * PHP Version >=5.3
 *
 * @category Gc_Tests
 * @package  Library
 * @author   Pierre Rambaud (GoT) <pierre.rambaud86@gmail.com>
 * @license  GNU/LGPL http://www.gnu.org/licenses/lgpl-3.0.html
 * @link     http://www.got-cms.com
 */

namespace Gc\Datatype;

use Gc\Datatype\Model as DatatypeModel;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-10-17 at 20:40:10.
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 * @group Gc
 * @category Gc_Tests
 * @package  Library
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Collection
     *
     * @return void
     */
    protected $object;

    /**
     * @var Collection
     *
     * @return void
     */
    protected $datatype;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->datatype = DatatypeModel::fromArray(
            array(
                'name' => 'DatatypeCollectionTest',
                'prevalue_value' => '',
                'model' => 'DatatypeCollectionTest',
            )
        );
        $this->datatype->save();

        $this->object = new Collection;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->datatype->delete();
        unset($this->datatype);
        unset($this->object);
    }

    /**
     * Test
     *
     * @covers Gc\Datatype\Collection::init
     * @covers Gc\Datatype\Collection::setDatatypes
     *
     * @return void
     */
    public function testInit()
    {
        $this->object->init();
        $this->assertInternalType('array', $this->object->getDatatypes());
    }

    /**
     * Test
     *
     * @covers Gc\Datatype\Collection::getDatatypes
     *
     * @return void
     */
    public function testGetDatatypes()
    {
        $this->assertInternalType('array', $this->object->getDatatypes());
    }

    /**
     * Test
     *
     * @covers Gc\Datatype\Collection::getSelect
     *
     * @return void
     */
    public function testGetSelect()
    {
        $this->assertInternalType('array', $this->object->getSelect());
    }
}
