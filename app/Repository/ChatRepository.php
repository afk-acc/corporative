<?php
namespace App\Repository;


use App\Models\Message;

class ChatRepository{
    public function getUserMessages(int $sender_id, int $reciver_id){
        return Message::whereIn('sender_id', [$sender_id, $reciver_id])
            ->whereIn('reciver_id', [$sender_id, $reciver_id])
            ->orderBy('created_at','desc')
            ->paginate(15);
    }
    public function sendMessage(array $data){
        return Message::create($data);
    }
}
