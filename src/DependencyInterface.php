<?php

declare(strict_types=1);

namespace BloomUx\ViteManifest;

interface DependencyInterface
{
    /**
     * Get the type of the dependency
     */
    public function getType() : string;

    /**
     * Whether this is considered an "entry" file
     */
    public function isEntry() : bool;

    /**
     * Get the relative file path for this dependency
     */
    public function getFile() : string;

    /**
     * Get relative or absolute URL to the dependency file
     */
    public function getUrl() : string;

    /**
     * Set the base URL for all assets
     *
     * @param string $baseUrl Base URL to the assets directory
     * @return static Return self instance
     */
    public function setBaseUrl(string $baseUrl);
}
