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

namespace Gc\Media;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-12-06 at 14:01:01.
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 * @group Gc
 * @category Gc_Tests
 * @package  Library
 */
class ImageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Image
     *
     * @return void
     */
    protected $object;

    /**
     * @var string
     *
     * @return void
     */
    protected $directory;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->directory = __DIR__ . '/_files/';
        $this->object    = new Image;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->object);
        unset($this->directory);
    }

    /**
     * Test
     *
     * @covers Gc\Media\Image::__construct
     *
     * @return void
     */
    public function testConstructor()
    {
        $this->assertInstanceOf('Gc\Media\Image', new Image($this->directory . 'test.png'));
    }

    /**
     * Test
     *
     * @covers Gc\Media\Image::open
     *
     * @return void
     */
    public function testOpen()
    {
        $this->assertInstanceOf('Gc\Media\Image', $this->object->open($this->directory . 'test.png'));
        $this->assertInstanceOf('Gc\Media\Image', $this->object->open($this->directory . 'test.jpg'));
        $this->assertInstanceOf('Gc\Media\Image', $this->object->open($this->directory . 'test.gif'));
        $this->assertInstanceOf('Gc\Media\Image', $this->object->open($this->directory . 'test.bmp'));
    }

    /**
     * Test
     *
     * @covers Gc\Media\Image::resize
     * @covers Gc\Media\Image::getSizeByFixedWidth
     * @covers Gc\Media\Image::getSizeByFixedHeight
     * @covers Gc\Media\Image::hex2rgb
     *
     * @return void
     */
    public function testResizeWithUndefinedOption()
    {
        $this->object->open($this->directory . 'test.png');
        $this->assertInstanceOf('Gc\Media\Image', $this->object->resize(50, 500, 'undefined option'));
        //double test for optimal size tests
        $this->assertInstanceOf('Gc\Media\Image', $this->object->resize(500, 50, 'undefined option'));
    }

    /**
     * Test
     *
     * @covers Gc\Media\Image::resize
     * @covers Gc\Media\Image::crop
     * @covers Gc\Media\Image::hex2rgb
     *
     * @return void
     */
    public function testResizeWithCropOption()
    {
        $this->object->open($this->directory . 'test.png');
        $this->assertInstanceOf('Gc\Media\Image', $this->object->resize(50, 50, 'crop'));
    }

    /**
     * Test
     *
     * @covers Gc\Media\Image::resize
     * @covers Gc\Media\Image::getSizeByFixedWidth
     * @covers Gc\Media\Image::getSizeByFixedHeight
     * @covers Gc\Media\Image::hex2rgb
     *
     * @return void
     */
    public function testResizeWithUndefinedColor()
    {
        $this->object->open($this->directory . 'test.png');
        $this->assertInstanceOf('Gc\Media\Image', $this->object->resize(50, 50, 'auto', 'FFFFFFFF'));
    }

    /**
     * Test
     *
     * @covers Gc\Media\Image::resize
     *
     * @return void
     */
    public function testResizeWithNoImage()
    {
        $this->assertFalse($this->object->resize(50, 50));
    }

    /**
     * Test
     *
     * @covers Gc\Media\Image::hex2rgb
     *
     * @return void
     */
    public function testHex2rgbWith6Chars()
    {
        $this->assertInternalType('array', $this->object->hex2rgb('FFFFFF'));
    }

    /**
     * Test
     *
     * @covers Gc\Media\Image::hex2rgb
     *
     * @return void
     */
    public function testHex2rgbWith3Chars()
    {
        $this->assertInternalType('array', $this->object->hex2rgb('FFF'));
    }

    /**
     * Test
     *
     * @covers Gc\Media\Image::hex2rgb
     *
     * @return void
     */
    public function testHex2rgbWithWrongValue()
    {
        $this->assertFalse($this->object->hex2rgb('0123456'));
    }

    /**
     * Test
     *
     * @covers Gc\Media\Image::save
     *
     * @return void
     */
    public function testSaveWithNoImage()
    {
        $this->assertFalse($this->object->save('wrong/path'));
    }

    /**
     * Test
     *
     * @covers Gc\Media\Image::save
     *
     * @return void
     */
    public function testSaveWithPng()
    {
        $saving_path = $this->directory . 'saving-test.png';
        $this->object->open($this->directory . 'test.png');
        $this->object->resize(50, 50);
        $this->assertTrue($this->object->save($saving_path));
        unlink($saving_path);
    }

    /**
     * Test
     *
     * @covers Gc\Media\Image::save
     *
     * @return void
     */
    public function testSaveWithGif ()
    {
        $saving_path = $this->directory . 'saving-test.gif';
        $this->object->open($this->directory . 'test.gif');
        $this->object->resize(50, 50);
        $this->assertTrue($this->object->save($saving_path));
        unlink($saving_path);
    }

    /**
     * Test
     *
     * @covers Gc\Media\Image::save
     *
     * @return void
     */
    public function testSaveWithJpg()
    {
        $saving_path = $this->directory . 'saving-test.jpg';
        $this->object->open($this->directory . 'test.jpg');
        $this->object->resize(50, 50);
        $this->assertTrue($this->object->save($saving_path));
        unlink($saving_path);
    }

    /**
     * Test
     *
     * @covers Gc\Media\Image::save
     *
     * @return void
     */
    public function testSaveInBmp()
    {
        $saving_path = $this->directory . 'saving-test.bmp';
        $this->object->open($this->directory . 'test.png');
        $this->object->resize(50, 50);
        $this->assertFalse($this->object->save($saving_path));
    }
}
