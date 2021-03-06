<?php
// src/metadataparsers/dc/CdmToDc.php

namespace mik\metadataparsers\dc;

class CdmToDc
{
    /**
     * @var array $settings - configuration settings from confugration class.
     */
    public $settings;
      
    /**
     * Create a new CdmToDc Instance
     * @param array $settings configuration settings.
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
    }
}
