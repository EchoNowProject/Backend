<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GroupChatConversation
 * 
 * @property int $id
 * @property string $group_name
 * @property string|null $description
 * @property string|null $path_cover_image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class GroupChatConversation extends Model
{
	protected $table = 'group_chat_conversations';
}
