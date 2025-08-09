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
            'categories'  => ['nullable' , 'array' , 'min:1'],
            'published_at'=> ['nullable' , 'date'],
            'categories.*'=> ['string', 'max:255'],
            'tags'        => ['nullable' , 'array'],
            'tags.*'      => ['string', 'max:255'],
            'status'      => ['nullable', 'in:draft,published,archived'],
            'image'       => ['nullable' , 'image' , 'mimes:jpeg,png,jpg,gif,svg' , 'max:2048'],
        ];
    }
}
