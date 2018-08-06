<?php

namespace Vinorcola\DistantVersionedAssetsBundle\Config;

use LogicException;

class Config
{
    /**
     * @var Target[]
     */
    private $targets = [];

    /**
     * @var string
     */
    private $defaultTarget;

    /**
     * Config constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach ($config['targets'] as $key => $targetOptions) {
            $target = new Target($key, $targetOptions['url'], $targetOptions['manifestPath'], $targetOptions['cacheTtl']);
            $this->targets[$key] = $target;
        }
        $this->defaultTarget = $config['defaultTarget'];

        if (!key_exists($this->defaultTarget, $this->targets)) {
            if ($this->defaultTarget === 'default' && count($this->targets) === 1) {
                // Only one target but not named 'default', we set it as default.
                $this->defaultTarget = key($this->targets);
            } else {
                throw new LogicException('Unknown target "' . $this->defaultTarget . '". Please check the name of your default target in configuration.');
            }
        }
    }

    /**
     * @param string|null $key
     * @return Target
     */
    public function getTarget(?string $key = null): Target
    {
        if ($key === null) {
            return $this->targets[$this->defaultTarget];
        }
        if (key_exists($key, $this->targets)) {
            throw new LogicException('Requested an non-existing distant asset target "' . $key . '".');
        }

        return $this->targets[$key];
    }
}
