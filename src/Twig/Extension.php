<?php

namespace Vinorcola\DistantVersionedAssetsBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Vinorcola\DistantVersionedAssetsBundle\Model\VersionModel;

class Extension extends AbstractExtension
{
    /**
     * @var VersionModel
     */
    private $versionModel;

    /**
     * Extension constructor.
     *
     * @param VersionModel $versionModel
     */
    public function __construct(VersionModel $versionModel)
    {
        $this->versionModel = $versionModel;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('distantAsset', [ $this->versionModel, 'getFileUrl' ]),
        ];
    }
}
