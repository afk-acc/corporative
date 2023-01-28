<?php

namespace App\Http\Controllers\api\v1;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserChatResource;
use App\Models\Dialog;
use App\Models\Message;
use App\Repository\ChatRepository;
use BeyondCode\LaravelWebSockets\Dashboard\Http\Controllers\SendMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //
    public $chat;

    public function __construct(ChatRepository $repo)
    {
        $this->chat = $repo;
    }

    public function index(Request $request, ?int $reciver_id)
    {
        return empty($reciver_id) ? [] : $this->chat->getUserMessages($request->user()->id, $reciver_id);
    }

    public function store(Request $request, ?int $reciver_id)
    {
        $request->validate([
                'file'=>'file|max:10240'
            ]);
        if (empty($reciver_id)) {
            return;
        }
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $tmp = $file->storeAs('chat/'.$request->user()->id.'-'.$reciver_id, $filename, 'public');
                $message = $this->chat->sendMessage([
                    'sender_id' => $request->user()->id,
                    'reciver_id' => $reciver_id,
                    'message'=>$tmp,
                    'type'=>'image'
                ]);
            } else {
                $message = $this->chat->sendMessage([
                    'sender_id' => $request->user()->id,
                    'reciver_id' => $reciver_id,
                    'message' => $request->input('message')
                ]);
            }
            event(new MessageSent($message));
        } catch (\Throwable $th) {
        }

    }

    public function user_list(Request $request)
    {

        return new UserChatResource(Dialog::where('user_id', $request->user()->id)->get());
    }

}
