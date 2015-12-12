<?php

/*
 * This file is part of the file-utils package.
 *
 * (c) Gustavo Falco <comfortablynumb84@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IronEdge\Component\FileUtils\Exception;


/*
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 */
class UnsupportedFileTypeException extends BaseException
{
    public static function create($type)
    {
        return new self('Unsupported file type "'.$type.'".');
    }
}