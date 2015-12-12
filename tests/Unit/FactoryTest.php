<?php

/*
 * This file is part of the file-utils package.
 *
 * (c) Gustavo Falco <comfortablynumb84@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IronEdge\Component\FileUtils\Test\Unit;

use IronEdge\Component\FileUtils\File\Factory;

/*
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 */

class FactoryTest extends AbstractTestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_failIfTypeIsNotDefinedAndFileDoesNotExist()
    {
        $file = $this->createFileInstance(__DIR__.'/idontexist.json');
    }

    public function test_createsAJsonInstance()
    {
        $file = $this->createFileInstance(__DIR__.'/idontexist.json', Factory::JSON_TYPE);

        $this->assertInstanceOf('\IronEdge\Component\FileUtils\File\Json', $file);
    }

    protected function createFileInstance($path, $type = null, array $options = array())
    {
        $factory = new Factory();

        return $factory->createInstance($path, $type, $options);
    }
}