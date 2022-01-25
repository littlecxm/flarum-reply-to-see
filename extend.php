<?php

/*
 * This file is part of Flarum.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

use Flarum\Api\Serializer\PostSerializer;
use Flarum\Extend;
use s9e\TextFormatter\Configurator;
use Littlecxm\ReplyToSee\HideContentInPosts;

return [
    (new Extend\Frontend('forum'))
    ->js(__DIR__.'/js/dist/forum.js')
    ->css(__DIR__.'/resources/less/forum.less'),
(new Extend\Formatter)
    ->configure(function (Configurator $config) {
        $config->BBCodes->addCustom(
            '[REPLY]{TEXT}[/REPLY]',
            '<reply2see>{TEXT}</reply2see>'
        );
        }),
new Extend\Locales(__DIR__ . '/resources/locale'),
    (new Extend\ApiSerializer(PostSerializer::class))
        ->attributes(HideContentInPosts::class),
];
