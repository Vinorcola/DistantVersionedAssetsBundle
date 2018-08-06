<?php

namespace Vinorcola\DistantVersionedAssetsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vinorcola\DistantVersionedAssetsBundle\DependencyInjection\VinorcolaDistantVersionedAssetsExtension;

class VinorcolaDistantVersionedAssetsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     * @return VinorcolaDistantVersionedAssetsExtension
     */
    public function getContainerExtension()
    {
        return new VinorcolaDistantVersionedAssetsExtension();
    }
}
