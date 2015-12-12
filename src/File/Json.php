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


/*
 * Handlers JSON files.
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 */
use IronEdge\Component\FileUtils\Exception\DecodeException;
use IronEdge\Component\FileUtils\Exception\EncodeException;
use JsonSchema\Validator;
use Seld\JsonLint\JsonParser;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Json extends Base
{
    public function decode(array $options = array())
    {
        $json = json_decode($this->getContents(), true);

        if ($json === null && json_last_error() === JSON_ERROR_NONE) {
            $this->validateSyntax();

            return $this;
        }

        $this->validateSchema();

        return $json;
    }

    public function encode(array $options = array())
    {
        $json = json_encode($this->getContents(), $this->getOption('encodingOptions'));

        if (false === $json) {
            switch (json_last_error()) {
                case JSON_ERROR_DEPTH:
                    $msg = 'Maximum stack depth exceeded';

                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $msg = 'Underflow or the modes mismatch';

                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $msg = 'Unexpected control character found';

                    break;
                case JSON_ERROR_UTF8:
                    $msg = 'Malformed UTF-8 characters, possibly incorrectly encoded';

                    break;
                default:
                    $msg = 'Unknown error';
            }

            throw EncodeException::create($this->getPath(), $msg);
        }

        return $json;
    }

    /**
     * Configures the options resolver.
     *
     * @param OptionsResolver $resolver - Resolver.
     *
     * @return void
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            array(
                'schemaPath'            => null,
                'encodingOptions'       => 448
            )
        );
    }

    protected function validateSchema()
    {
        $schemaPath = $this->getOption('schemaPath');

        if ($schemaPath === null) {
            return;
        }

        $schemaData = json_decode(file_get_contents($schemaPath));

        $validator = new Validator();
        $validator->check($this->getContents(), $schemaData);


        if (!$validator->isValid()) {
            $errors = array();

            foreach ((array) $validator->getErrors() as $error) {
                $errors[] = ($error['property'] ? $error['property'].' : ' : '').$error['message'];
            }

            throw DecodeException::create($this->getPath, array('errors' => $errors));
        }
    }

    protected function validateSyntax()
    {
        $parser = new JsonParser();
        $result = $parser->lint($this->getContents());

        if ($result === null) {
            if (defined('JSON_ERROR_UTF8') && JSON_ERROR_UTF8 === json_last_error()) {
                throw new \UnexpectedValueException('"'.$this->getPath().'" is not UTF-8, could not parse as JSON');
            }

            return true;
        }

        throw DecodeException::create(
            $this->getPath(),
            array('message' => $result->getMessage(), 'details' => $result->getDetails())
        );
    }
}