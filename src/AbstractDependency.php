<?php

declare(strict_types=1);

namespace BloomUx\ViteManifest;

abstract class AbstractDependency implements DependencyInterface
{
    protected $baseUrl;

    /**
     * @inheritDoc
     * @return static
     */
    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function getUrl() : string
    {
        return $this->baseUrl . $this->getFile();
    }
}
