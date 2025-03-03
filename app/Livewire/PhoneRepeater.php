<?php

namespace App\Livewire;

use Livewire\Component;

class PhoneRepeater extends Component
{
    public $phones = [['phone_number' => '']];

    public function mount($existingPhones = [])
    {
        if (!empty($existingPhones)) {
            $this->phones = collect($existingPhones)->map(fn($phone) => ['phone_number' => $phone])->toArray();
        }
    }


    public function addPhone()
    {
        if (count($this->phones) < 3) {
            $this->phones[] = ['phone_number' => ''];
        }
    }

    public function removePhone($index)
    {
        if (count($this->phones) > 1) {
            unset($this->phones[$index]);
            $this->phones = array_values($this->phones);
        }
    }

    public function submitForm()
    {
        $this->validate();
    }

    public function render()
    {
        return view('livewire.phone-repeater');
    }
}
