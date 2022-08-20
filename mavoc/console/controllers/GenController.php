<?php

namespace mavoc\console\controllers;

// TODO: Need to move this to core
class GenController {

    public function keys($in, $out) {
        $dir = ao()->env('AO_BASE_DIR');
        if(!is_dir($dir)) {
            out('Error: ' . 'There was a problem with the base directory. Please make sure it exists with the proper permissions.', 'red');
            exit(1);
        }

        $file = '.keys.php';

        // Check if the file already exists
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if(is_file($path)) {
            out('Error: ' . 'The .keys.php file already appears to exist.', 'red');
            exit(1);
        }


$key = sodium_bin2hex(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
$content = <<<PHP
<?php

// Do not change these values, otherwise all of the encrypted data in the database will become unusable.

return [
    'CONNECTIONS_1' => '$key',
];

PHP;

        file_put_contents($path, $content);


        out('The .keys.php file has been created: ' . $file, 'green');
    }

}

