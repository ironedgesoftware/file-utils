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
use Symfony\Component\OptionsResolver\OptionsResolver;

/*
 * Handles JSON files.
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 */
class Json extends Base
{
    /**
     * Decodes JSON data.
     *
     * @param array $options - Options.
     *
     * @throws DecodeException
     *
     * @return array
     */
    public function decode(array $options = [])
    {
        $json = json_decode($this->getContents(), true);

        if ($json === null && json_last_error() === JSON_ERROR_NONE) {
            $this->validateSyntax();

            return $this;
        }

        $this->validateSchema();

        return $json;
    }

    /**
     * Encodes data in JSON format.
     *
     * @param array $options - Options.
     *
     * @throws EncodeException
     *
     * @return string
     */
    public function encode(array $options = [])
    {
        $options = array_replace(
            [
                'encodingOptions'       => $this->getOption('encodingOptions')
            ],
            $options
        );

        $json = json_encode($this->getContents(), $options['encodingOptions']);

        if ($json === false) {
            switch (json_last_error()) {
                case JSON_ERROR_DEPTH:
                    $msg = 'The maximum stack depth has been exceeded.';

                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $msg = 'Occurs with underflow or with the modes mismatch.';

                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $msg = 'Control character error, possibly incorrectly encoded.';

                    break;
                case JSON_ERROR_UTF8:
                    $msg = 'alformed UTF-8 characters, possibly incorrectly encoded.';

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
            [
                'schemaPath'            => null,
                'encodingOptions'       => 448
            ]
        );
    }

    /**
     * Validates JSON schema.
     *
     * @throws DecodeException
     *
     * @return void
     */
    protected function validateSchema()
    {
        $schemaPath = $this->getOption('schemaPath');

        if ($schemaPath === null) {
            return;
        }

        $schemaData = json_decode(file_get_contents($schemaPath));

        if (!class_exists('\\JsonSchema\\Validator')) {
            throw new \RuntimeException(
                'If you want to validate a JSON schema, you must require package "justinrainbow/json-schema"-'
            );
        }

        $validator = new \JsonSchema\Validator();
        $validator->check($this->getContents(), $schemaData);

        if (!$validator->isValid()) {
            $errors = [];

            foreach ((array) $validator->getErrors() as $error) {
                $errors[] = ($error['property'] ? $error['property'].' : ' : '').$error['message'];
            }

            throw DecodeException::create($this->getPath(), ['errors' => $errors]);
        }
    }

    /**
     * Validates JSON syntax using lint.
     *
     * @throws DecodeException
     *
     * @return bool
     */
    protected function validateSyntax()
    {
        if (!class_exists('\\Seld\\JsonLint\\JsonParser')) {
            throw new \RuntimeException(
                'If you want to validate JSON syntax using lint, you must require package "seld/jsonlint".'
            );
        }

        $parser = new \Seld\JsonLint\JsonParser();
        $result = $parser->lint($this->getContents());

        if ($result === null) {
            if (JSON_ERROR_UTF8 === json_last_error()) {
                throw new \UnexpectedValueException(
                    '"'.$this->getPath().'" is not encoded in UTF-8, could not parse as JSON'
                );
            }

            return true;
        }

        throw DecodeException::create(
            $this->getPath(),
            ['message' => $result->getMessage(), 'details' => $result->getDetails()]
        );
    }
}