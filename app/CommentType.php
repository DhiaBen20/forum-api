<?php

namespace App;

enum CommentType: string
{
    case CommentToPost = 'comment_to_post';
    case ReplyToComment = 'reply_to_comment';
    case ReplyToReply = 'reply_to_reply';
}
