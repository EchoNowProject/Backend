<?php

namespace App\Models;

use App\Models\Base\GroupChatConversation as BaseGroupChatConversation;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class GroupChatConversation extends BaseGroupChatConversation
{
    const IMAGEGROUPPATH = "/groups/";

    protected $fillable = [
        'group_name',
        'description',
        'path_cover_image',
    ];

    protected $appends = ['file_cover_image'];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'group_chat_conversation_participants', 'conversation_id', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(GroupChatMessage::class, 'conversation_id', 'id')->orderBy('id', 'asc');
    }

    protected function fileCoverImage(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->path_cover_image) {
                    return null;
                }

                $path = Storage::disk('public')->path(self::IMAGEGROUPPATH . $this->path_cover_image);

                if (!file_exists($path)) {
                    return null;
                }

                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);

                return [
                    'base64' => base64_encode($data),
                    'mime_type' => $type,
                ];
            }
        );
    }
}
