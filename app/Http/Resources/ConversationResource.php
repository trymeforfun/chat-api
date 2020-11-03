<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'user' => auth()->user()->id == $this->user_id ? new UserResource(User::find($this->sec_user_id)) : new UserResource(User::find($this->user_id)),
            'messages' => MessagesResource::collection($this->messages)
        ];
    }
}
