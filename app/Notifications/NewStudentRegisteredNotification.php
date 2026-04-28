<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewStudentRegisteredNotification extends Notification
{
    use Queueable;

    public function __construct(public $student)
    {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'key' => 'new_student_registered',
            'user_id' => $this->student->id,
            'name' => $this->student->name,
            'email' => $this->student->email,
            'url' => route('admin.students.index', [], false),
            'icon' => 'fas fa-user-plus',
            'type' => 'new_student_registered',
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}