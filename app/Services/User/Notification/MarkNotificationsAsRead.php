<?php

namespace App\Services\User\Notification;

use App\Services\BaseService;
use App\Models\User\Notification;

class MarkNotificationsAsRead extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
        ];
    }

    /**
     * Mark all notifications as read.
     *
     * @param array $data
     * @return bool
     */
    public function execute(array $data) : bool
    {
        $this->validate($data);

        Notification::where('user_id', 'user_id')
            ->update(['read' => true]);

        return true;
    }
}
