<?php

declare(strict_types=1);

namespace Bloom_UX\ViteManifest;

class CssDependency extends AbstractDependency implements DependencyInterface
{
    /**
     * Build a css dependency
     *
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->file = $filePath;
    }

    public function getFile() : string
    {
        return $this->file;
    }

    public function getType() : string
    {
        return 'css';
    }

    public function isEntry() : bool
    {
        return false;
    }
}
