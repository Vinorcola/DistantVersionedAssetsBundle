# VinorcolaDistantVersionedAssetsBundle

Link versioned assets across projects.

If you application needs to use some assets from a distant source and those assets are versioned, you can use this bundle to link them. The only requirement is that the distant source publish a `manifest.json`.

You can configure several targets (i.e. distant sources)

# Installation

```
composer require vinorcola/distant-versioned-assets-bundle
```

# Configuration

You must configure at least one target.

```yaml
# config/packages/vinorcola_distant_versioned_assets.yaml

vinorcola_distant_versioned_assets:
    targets:
        default:
            url: https://my-distant-storage.com
            manifestPath: build/manifest.json # Optional, default value
            cacheTtl: 3600 # Optional, default value
```

The manifest path is optional and is `build/manifest.json` by default. The manifest file is fetch and its content is cached to avoid querying the distant source at each request. You can configure the cache TTL (1 hour by default).

# Usage

Then, in Twig template, you can simply use the `distantAsset` function instead of `asset` function.

```twig
<link href="{{ distantAsset('build/style.css) }}" rel="stylesheet" />
<script src="{{ distantAsset('build/library.js') }}"></script>
```

# Advanced usage

You can configure several targets if needed.


```yaml
# config/packages/vinorcola_distant_versioned_assets.yaml

vinorcola_distant_versioned_assets:
    targets:
        default:
            url: https://my-distant-storage.com
        reference:
            url: https://my-reference-storage.com
```

```twig
<script src="{{ distantAsset('build/library.js') }}"></script> {# Uses the default target. #}
<script src="{{ distantAsset('build/library.js', 'reference) }}"></script> {# Uses the reference target. #}
```

If you have no target named `default`, you must indicate the default target:

```yaml
# config/packages/vinorcola_distant_versioned_assets.yaml

vinorcola_distant_versioned_assets:
    defaultTarget: storage
    targets:
        storage:
            url: https://my-distant-storage.com
        reference:
            url: https://my-reference-storage.com
```

Note that if you only have one target, there is no need to configure the `defaultTarget` option, whatever the target name is.
