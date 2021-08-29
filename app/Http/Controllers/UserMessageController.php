<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Message;
use App\MessageThread;
use App\MessageParticipant;
use App\Events\MessageReceived;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserMessageController extends UserBaseController
{
    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function index()
    {
        $this->authorize('view', $this->user);

        $threads = MessageThread::forUser(Auth::id())->latest('updated_at')->paginate(10);

        return view('_particles.users.messages.index', compact('threads'));
    }


    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($user_slug, $id)
    {
        $this->authorize('view', $this->user);

        try {
            $thread = MessageThread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error.message', trans('message_thread_not_found'));

            return redirect()->back();
        }

        $thread->markAsRead(Auth::id());

        $messages = $thread->messages()->paginate(10);

        return view('_particles.users.messages.show', compact('thread', 'messages'));
    }

    /**
     * Mark as read a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function read($user_slug, $id)
    {
        $this->authorize('view', $this->user);

        try {
            $thread = MessageThread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error.message', trans('message_thread_not_found'));

            return redirect()->back();
        }

        $thread->markAsRead(Auth::id());

        return redirect()->back();
    }

    /**
     * Mark as read a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function unread($user_slug, $id)
    {
        $this->authorize('view', $this->user);

        try {
            $thread = MessageThread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error.message', trans('message_thread_not_found'));

            return redirect()->back();
        }

        $thread->markAsUnRead(Auth::id());

        return redirect()->back();
    }

    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create(Request $request)
    {
        $this->authorize('view', $this->user);

        $to_user = null;

        if ($to = $request->get("to")) {
            $to_user = User::findByUsernameOrFail($to);
        }

        $users = User::where('id', '!=', Auth::id())->get();

        return view('_particles.users.messages.create', compact('users', 'to_user'));
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $this->authorize('view', $this->user);

        $inputs = $request->all();

        $val =  Validator::make(
            $inputs,
            [
                'subject' => 'required|min:1',
                'message' => 'required|max:2000|min:1',
                'recipients' => 'required|max:100',
            ]
        );

        if ($val->fails()) {
            Session::flash('error.message',  $val->errors()->first());

            return redirect()->back()->withInput($inputs);
        }

        $thread = MessageThread::create([
            'subject' => $inputs['subject'],
        ]);

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $inputs['message'],
        ]);

        // Sender
        MessageParticipant::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'last_read' => new Carbon,
        ]);

        // Recipients
        if ($request->has('recipients')) {
            $thread->addParticipant(explode(',', $inputs['recipients']));
        }

        event(new MessageReceived($message));

        return redirect()->action('UserMessageController@index', [$this->user->username_slug]);
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $user_slug, $id)
    {
        $this->authorize('view', $this->user);

        $inputs = $request->all();

        $val = Validator::make(
            $inputs,
            [
                'message' => 'required|max:2000|min:1',
            ]
        );

        if ($val->fails()) {
            Session::flash('error.message',  $val->errors()->first());
            return redirect()->back()->withInput($inputs);
        }

        try {
            $thread = MessageThread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error.message', trans('message_thread_not_found'));
            return redirect()->back()->withInput($inputs);
        }

        $thread->activateAllParticipants();

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $request->input('message'),
        ]);

        // Add replier as a participant
        $participant = MessageParticipant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
        ]);
        $participant->last_read = now();
        $participant->save();

        // Recipients
        if ($request->has('recipients')) {
            $thread->addParticipant(explode(',', $request->input('recipients')));
        }

        event(new MessageReceived($message));

        return redirect()->action('UserMessageController@show', [$this->user->username_slug, $id]);
    }
}
