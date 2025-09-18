<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
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
            'inviter_id' => $this->inviter_id,
            'invitee_email' => $this->invitee_email,
            'invitee_name' => $this->invitee_name,
            'invitee_phone' => $this->invitee_phone,
            'is_used' => $this->is_used,
            'expires_at' => $this->expires_at,
            'qr_code' => $this->qr_code,
            'invitation_limit' => $this->membership->invitation_limit,
            'status' => $this->status,
            'used_at' => $this->used_at,
            'created_at' => $this->created_at,
        ];
    }
}
