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


/*
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 */
abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    public $files;

    public function setUp()
    {
        parent::setUp();

        $this->cleanUp();

        $tmpDir = $this->getTmpDir();

        $this->files = array(
            'json'              => array(
                'valid'             => array(
                    array(
                        'path'              => $tmpDir.'/valid.json',
                        'contents'          => json_encode(array('test1' => 'value1', 'test2' => 'value2'))
                    )
                ),
                'invalid'       => array(
                    array(
                        'path'              => $tmpDir.'/invalid.json',
                        'contents'          => '{{}}}{{{{{'
                    )
                )
            )
        );

        foreach ($this->files as $fileType => $validAndInvalids) {
            foreach ($validAndInvalids as $validOrInvalid => $files) {
                foreach ($files as $fileData) {
                    file_put_contents($fileData['path'], $fileData['contents']);
                }
            }
        }
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->cleanUp();
    }

    protected function cleanUp()
    {
        $glob = glob($this->getTmpDir().'/*');

        foreach ($glob as $file) {
            @unlink($file);
        }
    }

    protected function getTmpDir()
    {
        return realpath(__DIR__.'/../tmp');
    }
}