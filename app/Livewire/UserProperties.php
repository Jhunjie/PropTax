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
        
        $import = new PropertyRegistrationSheetImport;
        Excel::import($import, $this->upload);

        $path = $this->upload->store('uploads/property-registrations');

        $this->reset('upload');
        $this->resetPage();
        $this->dispatch('$refresh');

        session()->flash('status', "Upload processed and saved to {$path}.");
    
        if (!empty($import->duplicates)) {
            $count = count($import->duplicates);
            $examples = collect($import->duplicates)->take(5)->map(fn ($d) => "{$d['account_code']} ({$d['type']})")->implode(', ');

            session()->flash(
                'duplicate_warning',
                "{$count} duplicate row(s) skipped: {$examples}" . ($count > 5 ? '...' : '')
            );
        }
    }

    public function render()
    {
        return view('livewire.user-properties-table', [
            'userPropertiesData' => UserProperty::paginate(10),
        ]);
    }
}
