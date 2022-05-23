<?php
namespace Supriadi\BelajarPhpMvc\App {

    function header(string $value)
    {
        echo $value;
    }
}

namespace Supriadi\BelajarPhpMvc\Service {

    function setcookie(string $name, string $value)
    {
        echo "$name: $value";
    }
}
