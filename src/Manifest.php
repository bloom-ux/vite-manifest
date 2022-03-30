<?php

declare(strict_types=1);

namespace BloomUx\ViteManifest;

use RuntimeException;
use stdClass;
use UnexpectedValueException;

use function file_get_contents;
use function is_object;
use function is_readable;
use function is_string;
use function json_decode;
use function json_last_error;
use function json_last_error_msg;

class Manifest
{
    /**
     * Raw manifest data from JSON object
     *
     * @var null|stdClass null or JSON object
     */
    private $data;

    /**
     * The base URL for all assets (absolute or relative)
     *
     * @var string
     */
    private $baseUrl = '';

    /**
     * Build Manifest from a path or the json data
     *
     * @param null|string|stdClass $filePathOrData Path to the json file, or the json data
     * @throws RuntimeException File is not readable.
     * @throws UnexpectedValueException File is empty.
     */
    public function __construct($filePathOrData = null)
    {
        if (is_string($filePathOrData)) {
            $this->data = $this->readFromPath($filePathOrData);
        } elseif (is_object($filePathOrData)) {
            $this->data = $filePathOrData;
        }
    }

    /**
     * Read json data from the given path
     *
     * @param string $filePath Full path to the manifest.json file
     * @return stdClass Manifest data
     * @throws RuntimeException File is not readable.
     * @throws UnexpectedValueException Empty file or unable to parse JSON.
     */
    public function readFromPath(string $filePath) : stdClass
    {
        if (! is_readable($filePath)) {
            throw new RuntimeException("Can't read file at: {$filePath}");
        }
        $fileContent = file_get_contents($filePath);
        if (! $fileContent) {
            throw new UnexpectedValueException("Got empty content from file at {$filePath}");
        }
        $fileData  = (object) json_decode($fileContent);
        $jsonError = json_last_error();
        if ($jsonError) {
            $jsonErrorMessage = json_last_error_msg();
            throw new UnexpectedValueException("Wrong JSON data from {$filePath}: [{$jsonError}] {$jsonErrorMessage}");
        }
        return $fileData;
    }

    /**
     * Set the base URL for all assets (absolute or relative)
     *
     * @param string $baseUrl Base URL
     * @return static
     */
    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * Get a flat list of dependencies for a given entry file
     *
     * @param string $entry The entry file to get depencies
     * @return DependencyInterface[] List of dependencies
     */
    public function getEntryDeps(string $entry) : array
    {
        $deps = [];
        // Direct dependencies
        if (! isset($this->data->{$entry})) {
            return [];
        }
        $this->addJs($entry, $deps);
        $this->addCss($entry, $deps);
        foreach ($this->data->{$entry}->imports as $importKey) {
            $this->addJs($importKey, $deps);
            $this->addCss($importKey, $deps);
        }
        return $deps;
    }

    /**
     * Add a JsDependency to the flat list of dependencies
     *
     * @param string $key  File key (relative path)
     * @param array  $deps All dependencies
     */
    private function addJs(string $key, &$deps)
    {
        $newDep = new JsDependency($this->data->{$key});
        if ($this->baseUrl) {
            $newDep->setBaseUrl($this->baseUrl);
        }
        $deps[] = $newDep;
    }

    /**
     * Add a CssDependency to the flat list of dependencies
     *
     * @param string $key  File key (relative path)
     * @param array  $deps All dependencies
     */
    private function addCss(string $key, &$deps)
    {
        if (! isset($this->data->{$key}->css)) {
            return;
        }
        foreach ($this->data->{$key}->css as $css) {
            $newDep = new CssDependency($css);
            if ($this->baseUrl) {
                $newDep->setBaseUrl($this->baseUrl);
            }
            $deps[] = $newDep;
        }
    }
}
