<?php

namespace Dtotoxml\Contracts;

use Dtotoxml\Enum\HandlerType;

interface Handler
{

    public function getType():HandlerType;
}