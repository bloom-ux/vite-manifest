<?php

declare(strict_types=1);

namespace Bloom_UX\ViteManifest;

use function is_object;

class JsDependency extends AbstractDependency implements DependencyInterface
{
    protected $file    = '';
    protected $isEntry = false;

    /**
     * Build a js dependency from the manifest data
     *
     * @param null|array|stdClass $properties Dependency properties
     */
    public function __construct($properties = null)
    {
        if (! empty($properties) && is_object($properties)) {
            $properties = (array) $properties;
        }
        if (! empty($properties['file'])) {
            $this->file = $properties['file'];
        }
        if (isset($properties['isEntry']) && $properties['isEntry']) {
            $this->isEntry = true;
        }
    }

    public function getFile() : string
    {
        return $this->file;
    }

    public function getType() : string
    {
        return 'js';
    }

    public function isEntry() : bool
    {
        return (bool) $this->isEntry;
    }
}
