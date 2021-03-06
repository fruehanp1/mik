<?php
// src/filegettermanipulators/CdmSingleFileVanpunk.php

namespace mik\filegettermanipulators;

use \Monolog\Logger;

/**
 * CdmSingleFileVanpunk - Get the path to the master (OBJ) file for the current object.
 */
class CdmSingleFileVanpunk extends Filegettermanipulator
{
    /**
     * Create a new CdmSingleFileVanpunk instance
     */
    public function __construct($settings, $paramsArray, $record_key)
    {
        parent::__construct($settings, $paramsArray, $record_key);

        // Set up logger.
        $this->pathToLog = $settings['LOGGING']['path_to_manipulator_log'];
        $this->log = new \Monolog\Logger('config');
        $this->logStreamHandler = new \Monolog\Handler\StreamHandler(
            $this->pathToLog,
            Logger::INFO
        );
        $this->log->pushHandler($this->logStreamHandler);

        // This manipulator expects two parameters, a metadata field name and
        // a comma-separated list of file extensions.
        if (count($paramsArray) == 2) {
            $this->sourceField = $paramsArray[0];
            $extensions = $paramsArray[1];
            $this->extensions = explode(',', $extensions);
        } else {
            $this->log->addError(
                "CdmSingleFileVanpunk",
                array('Incorrect number of parameters' => count($paramsArray))
            );
        }
    }

    /*
     * Generates possible filepaths for master files.
     *
     * @return mixed
     *    An array of possible file paths, or false if none can be genreated.
     */
    public function getMasterFilePaths()
    {
        $possibleMasterFilePaths = array();

        $metadata_path = $this->settings['FETCHER']['temp_directory'] . DIRECTORY_SEPARATOR .
            $this->record_key . '.metadata';
        $metadata = unserialize(file_get_contents($metadata_path));

        if (isset($this->settings['FILE_GETTER']['input_directories'])) {
            if (isset($metadata[$this->sourceField]) && is_string($metadata[$this->sourceField])) {
                // Get the filename from the value of $this->sourceField.
                $identifier = $metadata[$this->sourceField];

                // For the vanpunk_1 collection, the value of identi and the master filenames
                // differ: identi values look like this: MSC109-Posters-0371, but we want files
                // that have names like MSC109-0371.tif.
                $base_name = preg_replace('/\-Posters/', '', $identifier);

                foreach ($this->settings['FILE_GETTER']['input_directories'] as $input_directory) {
                    foreach ($this->extensions as $ext) {
                        $master_file_path = $input_directory . DIRECTORY_SEPARATOR .
                            $base_name . '.' . $ext;
                        $possibleMasterFilePaths[] = $master_file_path;
                    }
                }
                return $possibleMasterFilePaths;
            } else {
                // Log that we can't get the sourcefield.
                $this->log->addError(
                    "CdmSingleFileVanpunk",
                    array('Metadata error' => "Can't get value of source field")
                );
                return false;
            }
        } else {
            // Log that there is no input directory.
            $this->log->addError(
                "CdmSingleFileVanpunk",
                array('Configuration error' => "No input directory is defined.")
            );
            return false;
        }
    }
}
