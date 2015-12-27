<?php

/*
 * This file is part of the file-utils package.
 *
 * (c) Gustavo Falco <comfortablynumb84@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IronEdge\Component\FileUtils\File;

use IronEdge\Component\FileUtils\Exception\DecodeException;
use IronEdge\Component\FileUtils\Exception\EncodeException;
use IronEdge\Component\FileUtils\Exception\MissingComponentException;
use Symfony\Component\Yaml\Yaml as SymfonyYaml;

/*
 * Handles Yaml files.
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 */
class Yaml extends Base
{
    /**
     * Yaml constructor.
     *
     * @param string $path    - Path.
     * @param array  $options - Options.
     *
     * @throws MissingComponentException
     */
    public function __construct($path, array $options)
    {
        if (!class_exists('\\Symfony\Component\Yaml\\Yaml')) {
            throw MissingComponentException::create(
                'You need to install component "symfony/yaml" in order to handle YML files.'
            );
        }

        parent::__construct($path, $options);
    }

    /**
     * Decodes Yaml data.
     *
     * @param array $options - Options.
     *
     * @throws DecodeException
     *
     * @return array
     */
    public function decode(array $options = [])
    {
        $options = array_merge(
            [
                'exceptionOnInvalidType'        => false,
                'objectSupport'                 => false,
                'objectForMap'                  => false
            ],
            $options
        );

        return SymfonyYaml::parse(
            $this->getContents(),
            $options['exceptionOnInvalidType'],
            $options['objectSupport'],
            $options['objectForMap']
        );
    }

    /**
     * Encodes data in Yaml format.
     *
     * @param array $options - Options.
     *
     * @throws EncodeException
     *
     * @return string
     */
    public function encode(array $options = [])
    {
        $options = array_merge(
            [
                'inline'                        => 2,
                'indent'                        => 4,
                'exceptionOnInvalidType'        => false,
                'objectSupport'                 => false
            ],
            $options
        );

        return SymfonyYaml::dump(
            $this->getContents(),
            $options['inline'],
            $options['indent'],
            $options['exceptionOnInvalidType'],
            $options['objectSupport']
        );
    }
}