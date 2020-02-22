<?php

namespace App\Http\Controllers;

use App\message;
use App\Events\MessageEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{

    /**
     * Check Authenticate users.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('chat');
    }

    
    function fetchMessages($id)
    {
        return message::with('user')->where([ ['from',$id] , ['to',auth()->id()] ])->orWhere([ ['from',auth()->id()] , ['to',$id] ])->get();
    }


    public function sendMessages(Request $request)
    {
        $message = auth()->user()->usermessage()->create([
            'from' => auth()->id(),
            'to' => $request->to,
            'message' => $request->message,
        ]);

        // event(new \App\Events\MessageEvent($message->load('user')) );
    
        broadcast(new MessageEvent( $message->load('user') ))->toOthers();

        // return $message;
        return ['status' => 'success'];
    }
}
