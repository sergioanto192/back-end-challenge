<?php
namespace App\Config;
class Config
{
    public static function loadEnv($file)
    {
        if (!file_exists($file)) {
            throw new  \Exception("Arquivo .env não encontrado.");
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);

            // Define a variável no ambiente, se ainda não existir
            if (!getenv($key)) {
                putenv("$key=$value");
            }
        }
    }
}
