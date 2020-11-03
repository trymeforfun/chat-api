<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationCollection;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conversations = Conversation::where('user_id', auth()->user()->id)->orWhere('sec_user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->get();
        $count = count($conversations);
        for ($i=0; $i < $count; $i++) { 
            for ($j= $i + 1 ; $j < $count; $j++) { 
                if ($conversations[$i]->messages->last()->id < $conversations[$j]->messages->last()->id) {
                    $temp = $conversations[$i];
                    $conversations[$i] = $conversations[$j];
                    $conversations[$j] = $temp;
                }
            }
        }

        return ConversationResource::collection($conversations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'message' => 'required',
        ]);

        $conversations = Conversation::create([
            'user_id' => auth()->user()->id,
            'sec_user_id' => $request->user_id,
        ]);

        Message::create([
            'body' => $request->message,
            'user_id' => auth()->user()->id,
            'read' => false,
            'conversation_id' => $conversations->id
        ]);

        return new ConversationResource($conversations);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function conversationAsReaded(Request $request)
    {
        $request->validate('conversation_id', 'required');

        $conversations = Conversation::findOrFail($request->conversation_id);

        foreach ($conversations->messages as $message) {
            $message->update(['read' => true]);
        }

        return response()->json('success', 200);
    }

    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
