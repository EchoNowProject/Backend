<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ServerChatConversation
 * 
 * @property int $id
 * @property int $id_server
 * @property string $channel_text_name
 * @property string|null $description
 * @property bool $is_main
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class ServerChatConversation extends Model
{
	protected $table = 'server_chat_conversations';

	protected $casts = [
		'id_server' => 'int',
		'is_main' => 'bool'
	];
}
