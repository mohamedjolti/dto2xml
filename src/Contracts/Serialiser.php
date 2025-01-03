<?php

namespace Dtotoxml\Contracts;

interface Serialiser
{
    public function serialise($dto);

    public function unserialise($string, $targetClass);

}