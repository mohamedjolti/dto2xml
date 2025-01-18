<?php

namespace Dtotoxml\Properties;

class ObjectProperties
{
    const PROPERRTY_OUTPUT_REGEX_NAME = '/@outputName\s+([^\s]+)/';
    const PROPERTY_INPUT_REGEX_NAME = '/@inputName\s+([^\s]+)/';
    const PROPERTY_VAR_REGEX_NAME = '/@var\s+([^\s]+)/';

    const PROPERTY_VAR_ARRAY_REGEX_NAME = '/@var\s+([^\s]+)\[\]/';
    const SCALAR_VALUES = ['int', 'float', 'string', 'bool', 'array', 'null'];
    const PROPERTY_TYPE_ARRAY = '[]';


}