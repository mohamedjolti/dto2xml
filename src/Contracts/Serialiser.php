<?php

namespace Dtotoxml\Contracts;

interface Serialiser
{
    public function serialise(Object $dto,bool $isParent);

    public function unserialise(string $string,string $targetClass);

}