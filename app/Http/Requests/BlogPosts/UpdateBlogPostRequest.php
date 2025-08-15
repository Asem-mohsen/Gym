<?php

namespace App\Http\Requests\BlogPosts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogPostRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'title'       => ['nullable' , 'string' , 'max:255'],
            'content'     => ['nullable' , 'string'],
            'categories'  => ['nullable' , 'array'],
            'categories.*'=> ['string', 'max:255'],
            'published_at'=> ['nullable' , 'date'],
            'tags'        => ['nullable' , 'array'],
            'tags.*'      => ['string', 'max:255'],
            'status'      => ['nullable', 'in:draft,published,archived'],
            'image'       => ['nullable' , 'image' , 'mimes:jpeg,png,jpg,gif,svg' , 'max:2048'],
            'images'      => ['nullable' , 'array'],
            'images.*'    => ['image' , 'mimes:jpeg,png,jpg,gif,svg' , 'max:2048'],
            'description' => ['nullable' , 'string'],
            'quote_author_name' => ['nullable' , 'string' , 'max:255'],
            'quote_author_title' => ['string' , 'max:255', 'required_with:quote_author_name'],
            'author_comment' => ['nullable' , 'string'],
        ];
    }
}
