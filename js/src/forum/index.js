import { extend } from "flarum/common/extend";
import app from "flarum/common/app";
import CommentPost from "flarum/forum/components/CommentPost";
import DiscussionPage from "flarum/forum/components/DiscussionPage";
import DiscussionControls from "flarum/forum/utils/DiscussionControls";
import LogInModal from "flarum/forum/components/LogInModal";
import TextEditor from "flarum/common/components/TextEditor";
import TextEditorButton from "flarum/common/components/TextEditorButton";

app.initializers.add("littlecxm/reply-to-see", () => {
  extend(TextEditor.prototype, "toolbarItems", function (items) {
    items.add(
      "reply-to-see",
      <TextEditorButton
        onclick={() => {
          this.attrs.composer.editor.insertAtCursor("[reply][/reply]");
          const range = this.attrs.composer.editor.getSelectionRange();
          this.attrs.composer.editor.moveCursorTo(range[1] - 8);
        }}
        icon="fa fa-comment-medical"
      >
        {app.translator.trans("littlecxm-reply-to-see.forum.button_tooltip")}
      </TextEditorButton>
    );
  });

  extend(CommentPost.prototype, "content", function () {
    if (app.session.user && app.current instanceof DiscussionPage)
      $(".reply2see_reply")
        .off("click")
        .on("click", () =>
          DiscussionControls.replyAction.call(
            app.current.get("discussion"),
            true,
            false
          )
        );
    else
      $(".reply2see_reply")
        .off("click")
        .on("click", () => app.modal.show(LogInModal));
  });
});
