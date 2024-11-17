<?php

namespace Larsgowebdev\WPBattery\Utility;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class PathScannerUtility
{

    public static function getValidBlockJsonFiles(): array
    {
        $blockJsonFiles = [];

        $directory = PathUtility::getBlocksDirectory();

        // Get a list of files and folders in the directory
        $blockDirs = scandir($directory);

        // Iterate over the files and folders
        foreach ($blockDirs as $blockDir) {
            // Skip the current and parent directory entries
            if ($blockDir === '.' || $blockDir === '..') {
                continue;
            }

            // Get the full path to the block folder
            $blockFolder = $directory . PathUtility::$directorySeparator . $blockDir;

            // if it is not a directory, continue
            if (!is_dir($blockFolder)) {
                continue;
            }

            $fullBlockFilename = $blockFolder . PathUtility::$directorySeparator . 'block.json';

            // If it's a file named 'block.json', add it to return
            if (is_file($fullBlockFilename)) {
                //register_block_type($fullBlockFilename);
                $blockJsonFiles[] = $fullBlockFilename;
            }
        }

        return $blockJsonFiles;
    }

    public static function scanForCompatibleFiles(string $directory, string $filename = '', string $extension = 'php'): array
    {
        // Early return if directory doesn't exist
        if (!is_dir($directory) || !file_exists($directory)) {
            return [];
        }

        $files = [];
        $pattern = '';

        if ($filename) {
            // Escape the filename to avoid issues with special characters in regex
            $escapedFilename = preg_quote($filename, '/');
            $pattern = '/^.+\/' . $escapedFilename . '$/i';
        } elseif ($extension) {
            // Escape the extension to avoid issues with special characters in regex
            $escapedExtension = preg_quote($extension, '/');
            $pattern = '/^.+\.' . $escapedExtension . '$/i';
        }

        if (!$pattern) {
            throw new \InvalidArgumentException("Either filename or extension must be provided.");
        }

        // Create a RecursiveDirectoryIterator to scan the directory
        $directoryIterator = new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS);

        // Use RecursiveIteratorIterator to traverse the directories recursively
        $iterator = new RecursiveIteratorIterator($directoryIterator);

        // Use RegexIterator to filter files based on the constructed pattern
        $regexIterator = new RegexIterator($iterator, $pattern, RegexIterator::GET_MATCH);

        // Collect all matched file paths
        foreach ($regexIterator as $file) {
            $files[] = $file[0];
        }

        return $files;
    }


}