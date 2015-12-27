<?php

/*
 * This file is part of the file-utils package.
 *
 * (c) Gustavo Falco <comfortablynumb84@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IronEdge\Component\FileUtils\Test\Acceptance;

use IronEdge\Component\FileUtils\File\Factory;

/*
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 */

class FactoryTest extends AbstractTestCase
{
    public function test_jsonFiles()
    {
        $factory = $this->createFactory();

        // Load JSON data

        $path = $this->files['json']['valid'][0]['path'];
        $file = $factory->createInstance($path);

        $this->assertInstanceOf('\IronEdge\Component\FileUtils\File\Json', $file);

        $data = $file->getContents();

        $this->assertEquals('value1', $data['test1']);

        // Save new JSON data

        $data['newValue'] = 'testValue';

        $newPath = $path.'.json';

        $file->setContents($data)
            ->setPath($newPath)
            ->save();

        $file = $factory->createInstance($newPath);

        $this->assertInstanceOf('\IronEdge\Component\FileUtils\File\Json', $file);

        $data = $file->getContents();

        $this->assertEquals('testValue', $data['newValue']);
    }

    public function test_ymlFiles()
    {
        $factory = $this->createFactory();

        // Load Yaml data

        $path = $this->files['yml']['valid'][0]['path'];
        $file = $factory->createInstance($path);

        $this->assertInstanceOf('\IronEdge\Component\FileUtils\File\Yaml', $file);

        $data = $file->getContents();

        $this->assertEquals('value1', $data['test1']);

        // Save new Yaml data

        $data['newValue'] = 'testValue';

        $newPath = $path.'.yml';

        $file->setContents($data)
            ->setPath($newPath)
            ->save();

        $file = $factory->createInstance($newPath);

        $this->assertInstanceOf('\IronEdge\Component\FileUtils\File\Yaml', $file);

        $data = $file->getContents();

        $this->assertEquals('testValue', $data['newValue']);
    }


    // Helper methods

    protected function createFactory()
    {
        return new Factory();
    }
}