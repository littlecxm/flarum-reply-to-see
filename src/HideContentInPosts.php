<?php

namespace Littlecxm\ReplyToSee;

use Flarum\Api\Serializer\PostSerializer;
use Flarum\Database\AbstractModel;

class HideContentInPosts extends FormatContent
{
    public function __invoke(PostSerializer $serializer, AbstractModel $post, array $attributes)
    {
        if (isset($attributes["contentHtml"])) {
            $newHTML = $attributes["contentHtml"];
            if (!str_contains($newHTML, '<reply2see>')) {
                return $attributes;
            }

            $usersModel = $post['discussion']->participants()->get('id');
            $users = [];
            foreach ($usersModel as $user) {
                $users[] = $user->id;
            }

            $replied = !$serializer->getActor()->isGuest() && in_array($serializer->getActor()->id, $users);
            if ($serializer->getActor()->isAdmin()) $replied = true;
            if ($replied) {
                $newHTML = preg_replace('/<reply2see>(.*?)<\/reply2see>/is',
                    '<div class="reply2see"><div class="reply2see_title">' .
                    $this->translator->trans('littlecxm-reply-to-see.forum.hidden_content')
                    . '</div>$1</div>',
                    $newHTML
                );
            } else {
                $newHTML = preg_replace(
                    '/<reply2see>(.*?)<\/reply2see>/is',
                    '<div class="reply2see"><div class="reply2see_alert">' .
                    $this->translator->trans('littlecxm-reply-to-see.forum.reply_to_see',
                        array(
                            '{reply}' => '<a class="reply2see_reply">' . $this->translator->trans('core.forum.discussion_controls.reply_button') . '</a>'
                        )
                    ) . '</div></div>',
                    $newHTML
                );
            }

            $attributes['contentHtml'] = $newHTML;
        }

        return $attributes;
    }

}
