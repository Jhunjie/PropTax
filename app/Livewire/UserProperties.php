<?php

namespace App\Livewire;

use App\Imports\PropertyRegistrationSheetImport;
use App\Models\UserProperty;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Properties')]
class UserProperties extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $upload; 

    protected $rules = [
        'upload' => 'required|file|mimes:xlsx,xls|max:10240', 
    ];

    public function importUpload()
    {
        $this->validate();
        
        Excel::import(new PropertyRegistrationSheetImport, $this->upload);

        $path = $this->upload->store('uploads/property-registrations');

        $this->reset('upload');
        $this->resetPage();
        $this->dispatch('$refresh');

        session()->flash('status', "Upload processed and saved to {$path}.");
    }

    public function render()
    {
        return view('livewire.user-properties-table', [
            'userPropertiesData' => UserProperty::paginate(10),
        ]);
    }
}
