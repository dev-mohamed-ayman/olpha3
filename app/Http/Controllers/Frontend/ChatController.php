<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use App\Models\Message;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\UserSendPoint;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function users()
    {
        $senders = Message::query()->where('sender', auth()->user()->id)->pluck('receiver');
        $receivers = Message::query()->where('receiver', auth()->user()->id)->pluck('sender');
        $ids = [];
        foreach ($senders as $sender) {
            if (in_array($sender, $ids)) {

            } else {
                array_push($ids, $sender);
            }
        }
        foreach ($receivers as $receiver) {
            if (in_array($receiver, $ids)) {

            } else {
                array_push($ids, $receiver);
            }
        }

        $users = User::query()
            ->whereIn('id', $ids)
            ->with(['senderMessages' => function ($q) {
                $q->orderBy('id', 'desc')->limit(1)->first();
            }, 'receiverMessages' => function ($q) {
                $q->orderBy('id', 'desc')->limit(1)->first();
            }])
            ->get();

        return view('frontend.chat-users', compact('users'));

    }

    public function sendMessage($receiver, Request $request)
    {
        $receiver = User::findOrFail($receiver);
        $user = auth()->user();
        $message = new Message();
        $message->sender = $user->id;
        $message->receiver = $receiver->id;
        $message->message = $request->message;
        $message->save();

        event(new \App\Events\Message(
            $user,
            $receiver,
            $message,
            $user->image
        ));

        return response()->json([
            'receiver' => $receiver,
            'user' => $user,
            'message' => $message,
            'image' => asset($user->image)
        ]);

    }

    public function sendRecord($receiver, Request $request)
    {
        if ($request->hasFile('audio') && $request->file('audio')->isValid()) {
            $file = $request->file('audio');
            $fileName = time() . '_' . $file->getClientOriginalName();

            $receiver = User::findOrFail($receiver);
            $user = auth()->user();
            $message = new Message();
            $message->sender = $user->id;
            $message->receiver = $receiver->id;
            $message->type = 'rec';
            $message->message = $file->storeAs('uploads/frontend/records', $fileName, 'public_uploads');
            $message->save();

            event(new \App\Events\Message(
                $user,
                $receiver,
                $message,
                $user->image
            ));


//            Recording::create(['file_name' => $fileName]);

            return response()->json([
                'receiver' => $receiver,
                'user' => $user,
                'message' => $message,
                'rec' => asset($message->message),
                'image' => asset($user->image),
                'msg' => 'تم ارسال التسجيل بنجاح'
            ]);

//            return response('تم ارسال التسجيل بنجاح', 200);
        } else {
            return response('فشل ارسال التسجيل', 400);
        }
    }

    public function sendGift(User $user, Gift $gift)
    {

        try {


            $userPoint = UserPoint::query()->where('user_id', auth()->user()->id)->get();
            $points = $userPoint->sum('amount') - $userPoint->sum('expense');

            if ($gift->price > $points) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'نقاطك غير كافيه'
                ]);
            }

            $newUserPoint = new UserPoint();
            $newUserPoint->user_id = auth()->user()->id;
            $newUserPoint->amount = '0';
            $newUserPoint->expense = $gift->price;
            $newUserPoint->save();


            $receiver = $user;
            $user = auth()->user();
            $msg = '<img src="' . asset($gift->image) . '" style="width: 60px; height: 60px;">';
            $message = new Message();
            $message->sender = $user->id;
            $message->receiver = $receiver->id;
            $message->message = $msg;
            $message->save();

            $userSendPoint = new UserSendPoint();
            $userSendPoint->sender = auth()->user()->id;
            $userSendPoint->receiver = $message->receiver;
            $userSendPoint->points = $gift->price;
            $userSendPoint->save();

            event(new \App\Events\Message(
                $user,
                $receiver,
                $message,
                $user->image
            ));

            return response()->json([
                'receiver' => $receiver,
                'user' => $user,
                'message' => $message,
                'image' => asset($user->image)
            ]);


        } catch (\Exception $exception) {
            return back()->withErrors($exception->getMessage());
        }

    }
}
