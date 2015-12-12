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
class DecodeException extends BaseException
{
    public static function create($path, array $errors = array())
    {
        return new self('Couldn\'t decode file "'.$path.'". Errors: '.print_r($errors, true));
    }
}