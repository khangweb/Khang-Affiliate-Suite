<?php

namespace KhangWeb\Deeplink\Services;

use KhangWeb\Deeplink\Models\DeeplinkTemplate;

class BuildDeeplinkService
{
    public function build(DeeplinkTemplate $template, string $productUrl): string
    {
        $replacement = $template->should_encode_url
            ? urlencode($productUrl)
            : $productUrl;

        $query = str_replace(['{{url}}', '{url}'], $replacement, $template->query_template);

        return rtrim($template->base_url, '?') . '?' . ltrim($query, '?');
    }
}
