<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class IndividualChatConversation
 * 
 * @property int $id
 * @property string $type_conversation
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class IndividualChatConversation extends Model
{
	protected $table = 'individual_chat_conversations';
}
