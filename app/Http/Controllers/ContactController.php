<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Contact;
use App\Traits\Upload;

class ContactController extends Controller
{
    use Upload;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if(isset($request->row))
        {
            $record_per_page = $request->row;
        }
        else
        {
            $record_per_page = 10;
        }

        if(isset($request->search))
        {
            $search = $request->search;
        }
        else
        {
            $search = "";
            // $contacts = Contact::where('user_id', $user->id)
            //     ->paginate($record_per_page)
            //     ->withQueryString();
            // $data = [
            //     "contacts"=>$contacts,
            // ];
        }
        // $lastPage = $data["contacts"]->lastPage();
        // return $data["contacts"]->lastPage();

            // return $search;
            $contacts = Contact::where('user_id', $user->id)
                ->where(function (Builder $query) use ($search) {
                    $query->where('number', 'LIKE', '%' . $search . '%')
                          ->orWhere('name', 'LIKE', '%' . $search . '%');
                })
                ->paginate($record_per_page)
                ->withQueryString();

            $data = [
                "search"=>$search, 
                "record_per_page"=>$record_per_page,
                "contacts"=>$contacts,
            ];
        return view('contacts.index', compact("data"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validateWithBag('contactCreation', [
            'contact_number' => ['required'],
            'contact_name' => ['required'],
            'fileAttachment' => ['required'],
        ]);

        // Retrieve user details
        $user = $request->user();

        if ($request->hasFile('fileAttachment')) {
            $path = $this->UploadFile($request->file('fileAttachment'), 'Contacts');

            $contact = Contact::create([
                'number' => $request->contact_number,
                'name' => $request->contact_name,
                'user_id' => $user->id,
                'file_path' => $path,
            ]);
    
            return Redirect::route('contacts.index');

            // Contact::create([
            //     'path' => $path
            // ]);
            // return redirect()->route('files.index')->with('success', 'File Uploaded Successfully');
            // return $path;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $contact = Contact::find($id);

        if(empty($contact->file_path))
        {
            $request->validateWithBag('contactUpdate_'.$id.'', [
                'contact_number' => ['required'],
                'contact_name' => ['required'],
                'fileAttachment' => ['required'],
            ]);
        }
        else
        {
            $request->validateWithBag('contactUpdate_'.$id.'', [
                'contact_number' => ['required'],
                'contact_name' => ['required'],
            ]);

            $old_path = $contact->file_path;
        }

        if ($request->hasFile('fileAttachment')) {
            // Deleting the old file
            $this->deleteFile($old_path);

            // Uploading the new file
            $path = $this->UploadFile($request->file('fileAttachment'), 'Contacts');

            $contact->file_path = $path;
        }
        
        $contact->number = $request->contact_number;
        $contact->name = $request->contact_name;
        $contact->save();
    
        return Redirect::route('contacts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $contact = Contact::find($id);

        // Deleting the old file
        $old_path = $contact->file_path;
        $this->deleteFile($old_path);

        $contact->delete();
    
        return Redirect::route('contacts.index');
    }
}
