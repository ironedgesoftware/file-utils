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

use IronEdge\Component\FileUtils\Exception\UnsupportedFileTypeException;


/*
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 */
class Factory
{
    const JSON_TYPE                 = 'json';
    const YAML_TYPE                 = 'yml';

    /**
     * Creates an instance of a file.
     *
     * @param string      $file    - File.
     * @param null|string $type    - Type.
     * @param array       $options - Options.
     *
     * @throws UnsupportedFileTypeException - If the file type cannot be handled.
     *
     * @return \IronEdge\Component\FileUtils\File\Base
     */
    public function createInstance($file, $type = null, array $options = array())
    {
        if (!is_file($file) && $type === null) {
            throw new \InvalidArgumentException(
                'You must enter a $type if the file does not exist yet.'
            );
        }

        $type = pathinfo($file, PATHINFO_EXTENSION);

        switch ($type) {
            case self::JSON_TYPE:
                $file = new Json($file, $options);

                break;
            case self::YAML_TYPE:
                $file = new Yaml($file, $options);

                break;
            default:
                throw UnsupportedFileTypeException::create($type);
        }

        return $file;
    }
}