<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'status' => $this->status,
            'published_at' => $this->published_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            
            // Media
            'image' => $this->getFirstMediaUrl('blog_post_images'),
            'images' => $this->getMedia('blog_post_other_images')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'name' => $media->name,
                ];
            }),
            
            // Author
            'author' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            
            // Categories and Tags
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            
            // Comments (only for show page)
            'comments' => CommentResource::collection($this->whenLoaded('approvedComments')),
            'comments_count' => $this->whenLoaded('comments', function () {
                return $this->comments->count();
            }),
            
            // Social sharing statistics
            'shares' => [
                'facebook' => $this->facebook_shares_count,
                'twitter' => $this->twitter_shares_count,
                'email' => $this->email_shares_count,
                'total' => $this->shares->count(),
            ],
        ];
    }
}
