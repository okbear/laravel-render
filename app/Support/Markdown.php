<?php

namespace App\Support;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class Markdown
{
    public static function toHtml(string $markdown): string
    {
        $environment = new Environment([
            'html_input' => 'strip',   // 生HTMLは除去（XSS対策）
            'allow_unsafe_links' => false,
        ]);

        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension); // テーブル・打ち消し線など

        $converter = new MarkdownConverter($environment);

        return $converter->convert($markdown)->getContent();
    }
}
