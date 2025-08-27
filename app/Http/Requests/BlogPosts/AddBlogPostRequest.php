<?php

namespace App\Http\Requests\BlogPosts;

use Illuminate\Foundation\Http\FormRequest;

class AddBlogPostRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->user()->can('create_blog_posts');
    }

    public function rules(): array
    {
        return [
            'title'       => ['required' , 'string' , 'max:255'],
            'content'     => ['required' , 'string'],
            'categories'  => ['required' , 'array' , 'min:1'],
            'categories.*'=> ['string', 'max:255'],
            'tags'        => ['required' , 'array', 'min:1'],
            'tags.*'      => ['string', 'max:255'],
            'status'      => ['required', 'in:draft,published,archived'],
            'description' => ['required' , 'string'],
            'quote_author_name' => ['nullable' , 'string' , 'max:255'],
            'quote_author_title' => ['string' , 'max:255', 'required_with:quote_author_name'],
            'author_comment' => ['required' , 'string'],
            'image'       => ['required' , 'image' , 'mimes:jpeg,png,jpg,gif,svg' , 'max:2048'],
            'images'      => ['nullable' , 'array'],
            'images.*'    => ['image' , 'mimes:jpeg,png,jpg,gif,svg' , 'max:2048'],
        ];
    }
}
