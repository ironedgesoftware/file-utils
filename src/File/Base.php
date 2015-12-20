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

use IronEdge\Component\FileUtils\Exception\LoadException;
use IronEdge\Component\FileUtils\Exception\SaveException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/*
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 */
abstract class Base
{
    /**
     * Field _path.
     *
     * @var string
     */
    private $_path;

    /**
     * Field _rawContents.
     *
     * @var string
     */
    private $_contents;

    /**
     * Field _options.
     *
     * @var array
     */
    private $_options = array();


    /**
     * Base constructor.
     *
     * @param string $path    - Path to the file.
     * @param array  $options - Options.
     */
    public function __construct($path, array $options = array())
    {
        $this->setPath($path)
            ->setOptions($options);
    }

    /**
     * Getter method for field _path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * Setter method for field path.
     *
     * @param string $path - path.
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->_path = $path;

        return $this;
    }

    /**
     * Returns an option.
     *
     * @param string $name    - Option name.
     * @param mixed  $default - Default value.
     *
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        return array_key_exists($name, $this->_options) ?
            $this->_options[$name] :
            $default;
    }

    /**
     * Getter method for field _options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Setter method for field options.
     *
     * @param array $options - options.
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $resolver = new OptionsResolver();

        $this->configureOptions($resolver);

        $this->_options = $resolver->resolve($options);

        return $this;
    }

    /**
     * Getter method for field _contents.
     *
     * @return string
     */
    public function getContents()
    {
        $this->load();

        return $this->_contents;
    }

    /**
     * Setter method for field contents.
     *
     * @param string $contents - contents.
     *
     * @return $this
     */
    public function setContents($contents)
    {
        $this->_contents = $contents;

        return $this;
    }

    /**
     * Opens a file.
     *
     * @param bool  $refresh - If this is true then the file will be open even if it was open before.
     * @param array $options - Options.
     *
     * @throws LoadException
     *
     * @return $this
     */
    public function load($refresh = false, array $options = array())
    {
        if ($refresh || $this->_contents === null) {
            $this->_contents = @file_get_contents($this->_path);

            if ($this->_contents === false) {
                throw LoadException::create($this->_path);
            }

            $this->setContents($this->decode($options));
        }

        return $this;
    }

    /**
     * Saves the file.
     *
     * @param array $options - Options.
     *
     * @throws SaveException
     *
     * @return $this
     */
    public function save(array $options = array())
    {
        $data = $this->encode($options);

        if (($result = @file_put_contents($this->_path, $data)) === false) {
            throw SaveException::create($this->_path);
        }

        return $this;
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
    }

    /**
     * This method is called after the file is loaded.
     *
     * @param array $options - Options.
     *
     * @return mixed
     */
    abstract public function decode(array $options = array());

    /**
     * This method is called before the file is saved.
     *
     * @param array $options - Options.
     *
     * @return string
     */
    abstract public function encode(array $options = array());
}