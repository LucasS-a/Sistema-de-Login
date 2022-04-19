<?php

namespace src\conf;

use Tuupola\Middleware\JwtAuthentication;

function JwtAuth():JwtAuthentication
{
    return new JwtAuthentication([
        "secure" => true,
        "relaxed" => ["localhost", "http://localhost:8080"],
        "attribute" => "jwt",
        "secret" => getenv("JWT_SECRET")           
    ]);
}
