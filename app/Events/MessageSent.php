<?php

namespace App\Events;

use App\Models\Dialog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        $dialog = Dialog::where('user_id',$this->message['sender_id'])->count();
        if($dialog == 0){
            $d = new Dialog;
            $d->user_id = $this->message['sender_id'];
            $d->reciver_id = $this->message['reciver_id'];
            $d->save();
            $d2 = new Dialog;
            $d2->reciver_id = $this->message['sender_id'];
            $d2->user_id = $this->message['reciver_id'];
            $d2->save();
        }
        return new PrivateChannel('chat.'.$this->message['sender_id'].'.'.$this->message['reciver_id']);
    }

    public function broadcastAs(){
        return 'MessageSent';
    }


}
