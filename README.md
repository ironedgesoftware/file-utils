# File Utils

[![Build Status](https://travis-ci.org/ironedgesoftware/file-utils.svg?branch=master)](https://travis-ci.org/ironedgesoftware/file-utils)
[![Dependency Status](https://www.versioneye.com/user/projects/566c65d64e049b00410007d4/badge.svg?style=flat)](https://www.versioneye.com/user/projects/566c65d64e049b00410007d4)
[![Reference Status](https://www.versioneye.com/php/ironedgesoftware:file-utils/reference_badge.svg?style=flat)](https://www.versioneye.com/php/ironedgesoftware:file-utils/references)

## Description

This component allows you to load, save, encode and decode files of different types on a very simple way.

Currently supported formats:

* JSON

See the roadmap to know which other file types will be supported in future versions.

## Usage

To open a file, use the following code:

``` php

use IronEdge\Component\FileUtils\File\Factory;

$factory = new Factory();

// $file will be an instance of a subclass of \IronEdge\Component\FileUtils\File\Base .
// It detects the file type by its extension, and creates an instance of the appropiate
// class, if it's available.

$file = $factory->createInstance('/path/to/your/file');

// File contents are lazy loaded and decoded. When you call the "getContents" method, it opens
// the file and decodes its data.

$data = $file->getContents();

// Suppose we've open a JSON file with contents {"myParam": "myValue"}

print_r($data);

// It would print

Array
(
    [myParam] => myValue
)

// If you need to update the file

$data['myParam'] = 'newValue !';

$file->setContents($data);

$file->save();

```




## Roadmap

* YML Handling.
* XML Handling.