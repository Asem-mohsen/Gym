<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CommentResource extends JsonResource
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
            'content' => $this->content,
            'status' => $this->status,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            
            // Author
            'author' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'image' => $this->user->user_image,
            ],
            
            // Likes
            'likes_count' => $this->likes_count,
            'is_liked_by_user' => $this->when(
                Auth::guard('sanctum')->check() && $this->relationLoaded('likes'),
                function () {
                    return $this->isLikedBy(Auth::guard('sanctum')->user());
                }
            ),
            
            // Replies (nested comments)
            'replies' => CommentResource::collection($this->whenLoaded('children')),
            'replies_count' => $this->when(isset($this->children_count), $this->children_count),
        ];
    }
}
