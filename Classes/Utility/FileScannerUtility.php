<?php

namespace Larsgowebdev\WPBattery\Utility;

use Exception;
use ReflectionFunction;

class FileScannerUtility
{
    /**
     * gets valid block rendering functions in file (functions with 'render' in the name)
     *
     * @param string $filePath
     * @return array|null
     * @throws Exception
     */
    public static function getValidRenderFunctionsInFile(string $filePath): ?array
    {
        // Read the file content
        $fileContent = file_get_contents($filePath);

        if ($fileContent === false) {
            throw new Exception("Could not read file: $filePath");
        }

        // Regular expression to match function definitions with 'render' in the name
        $functionPattern = '/function\s+([a-zA-Z0-9_]*render[a-zA-Z0-9_]*)\s*\(/';

        // Perform the regular expression match to find all function names containing 'render'
        if (preg_match_all($functionPattern, $fileContent, $matches)) {
            $validFunctions = [];

            // Loop through all matches
            foreach ($matches[1] as $match) {
                // Include the PHP file to load the functions into the current script's context
                include_once $filePath;

                // Check if the function exists and is callable
                if (function_exists($match) && is_callable($match)) {
                    // Use ReflectionFunction to inspect the function
                    $reflection = new ReflectionFunction($match);
                    if ($reflection->getNumberOfRequiredParameters() === 1 && $reflection->getNumberOfParameters() === 1) {
                        $validFunctions[] = $match;
                    }
                }
            }

            return $validFunctions;
        }

        return [];
    }
}