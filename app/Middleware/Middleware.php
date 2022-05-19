<?php

namespace Supriadi\BelajarPhpMvc\Middleware;

interface Middleware
{
    function before(): void;
}