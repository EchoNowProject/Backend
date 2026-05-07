<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MessagesFile
 * 
 * @property int $id
 * @property int $message_id
 * @property string|null $file_name
 * @property string $path_file
 *
 * @package App\Models\Base
 */
class MessagesFile extends Model
{
	protected $table = 'messages_files';
	public $timestamps = false;

	protected $casts = [
		'message_id' => 'int'
	];
}
