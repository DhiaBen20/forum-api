<?php

namespace App\Http\Requests;

use App\CommentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'body' => ['required', 'max:5000'],
            'type' => ['required', Rule::enum(CommentType::class)],
        ];

        $type = $this->enum('type', CommentType::class);

        if ($type === CommentType::CommentToPost) {
            $rules['post'] = ['required', 'exists:posts,id'];
        }

        if ($type === CommentType::ReplyToComment || $type === CommentType::ReplyToReply) {
            $rules['comment'] = ['required', 'exists:comments,id'];
        }

        if ($type === CommentType::ReplyToReply) {
            $rules['replyTo'] = [
                'required',
                new Exists('comments', 'id')->where('comment_id', $this->integer('comment')),
            ];
        }

        return $rules;
    }
}
