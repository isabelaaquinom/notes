<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditNote;
use App\Http\Requests\NewNote;
use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Http\Request;

class NoteController extends Controller{
    public function index()
    {
        //load users note
        $id = session('user.id');
        $notes = User::find($id)
                        ->notes()
                        ->whereNull('deleted_at')
                        ->get()
                        ->toArray();

        //show home view
        return view('home', ['notes' => $notes]);
    }

    public function newNote()
    {
        //show new note view
        return view('new_note');
    }

    public function newNoteSubmit(NewNote $request)
    {
        //validate
        $validatedData = $request->validated();

        //get user id
        $validatedData['user_id'] = session('user.id');
        
        Note::create($validatedData);

        //redirect to home
        return redirect()->route('home')->with('success', 'Note created successfully');
    }

    public function editNote($id)
    {
        $id = Operations::decryptId($id);

        if($id === null){
            return redirect()->route('home');
        }

        //load note
        $note = Note::find($id);

        //show edit note view
        return view('edit_note', ['note' => $note]);
    }

    public function editNoteSubmit(EditNote $request, $id)
    {
        // validate request
        $validatedData = $request->validated();

        //get user id
        $note = Note::findOrFail($id);

        $note->update($validatedData);
        
        // decrypt note id
        $id = Operations::decryptId($request->note_id);

        if($id === null){
            return redirect()->route('home');
        }
        
        // load note
        $note = Note::find($id);
 
        // redirect to home
        return redirect()->route('home')->with('success', 'Note updated successfully');

    }

    public function deleteNote($id)
    {
        $id = Operations::decryptId($id);

        if($id === null){
            return redirect()->route('home');
        }
        
        // load note
        $note = Note::find($id);

        // show delete confirmation
        return view('delete_note', ['note' => $note]);
    }

    public function deleteNoteConfirm($id)
    {
        // check if $id is encrypted
        $id = Operations::decryptId($id);

        if($id === null){
            return redirect()->route('home');
        }

        // load note
        $note = Note::find($id);

        // 1 - hard delete
        //$note->delete();


        // 2 - soft delete
        //$note->deleted_at = date('Y:m:d H:i:s');
        //$note->save();

        // 3 - soft delete (property SoftDeletes in model)
        $note->delete();

        // 4 - hard delete (property SoftDeletes in model)
        //$note->forceDelete();

        // return
        return redirect()->route('home');

    }


}
