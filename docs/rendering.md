# Rendering Extensibility

This document describes how to customize the changelog output by implementing custom renderers.

## The Renderer Pipeline

The changelog generation process follows these steps:

1. **Collection**: Commits are gathered by a `CollectorInterface`.
2. **Processing**: A `GeneratorInterface` processes raw commits into a structured data model.
3. **Rendering**: A `RendererInterface` transforms the data model into the final string output (e.g., Markdown, HTML,
   JSON).

## Data Model

The renderer receives a `Chance\ReleaseScribe\Data\ChangeLogData` object, which contains:

- `getMainHeader()`: The primary title for the changelog.
- `getReleases()`: An array of `Release` objects.
- `getOptions()`: Renderer-specific configuration options.

Each `Release` object contains:

- `getTag()`: The version tag or heading (e.g., "v1.0.0" or "What's new?").
- `getSections()`: An array of `Section` objects.
- `getPreviousTag()`: The tag used for comparison (if any).
- `getDate()`: Release date (future use).
- `getAiSummary()`: An optional AI-generated summary.

Each `Section` object contains:

- `getLabel()`: The display name (e.g., "Features", "Bug Fixes").
- `getItems()`: An array of `ConventionalCommit` objects or raw strings.
- `getType()`: The commit type (e.g., "feat", "fix") that this section represents.

## Creating a Custom Renderer

To create a custom renderer, implement the `Chance\ReleaseScribe\Renderer\RendererInterface`:

```php
namespace App\Renderer;

use Chance\ReleaseScribe\Data\ChangeLogData;
use Chance\ReleaseScribe\Renderer\RendererInterface;

class MyCustomRenderer implements RendererInterface
{
    public function render(ChangeLogData|array $data, ?string $mainHeader = null): string
    {
        if (!$data instanceof ChangeLogData) {
            // Handle legacy array data if necessary, or throw exception
            return "Legacy data not supported";
        }

        $output = "# " . $data->getMainHeader() . "\n\n";

        foreach ($data->getReleases() as $release) {
            $output .= "## " . $release->getTag() . "\n\n";
            
            foreach ($release->getSections() as $section) {
                $output .= "### " . $section->getLabel() . "\n\n";
                foreach ($section->getItems() as $item) {
                    $subject = is_string($item) ? $item : $item->getDescription();
                    $output .= "- " . $subject . "\n";
                }
                $output .= "\n";
            }
        }

        return $output;
    }
}
```

## Configuring Your Renderer

Currently, the renderer is instantiated within the `GeneratorFactory`. In future updates, you will be able to configure
your custom renderer class via:

- Environment variable: `CHANGELOG_RENDERER_CLASS=App\Renderer\MyCustomRenderer`
- PHP config: `'renderer_class' => \App\Renderer\MyCustomRenderer::class`

## Use Cases for Custom Renderers

- **GitHub Release Notes**: Formatting output specifically for the GitHub Releases API.
- **Internal Formats**: Rendering as JSON or XML for ingestion by other internal tools.
- **Monorepos**: Grouping changes by package or component instead of commit type.
- **SemVer Impact**: Grouping sections by Major/Minor/Patch impact instead of conventional types.
- **Rich Metadata**: Including links to JIRA issues, Pull Requests, or commit diffs by parsing commit bodies or footers.
