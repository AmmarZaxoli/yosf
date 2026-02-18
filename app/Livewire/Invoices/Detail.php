<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use App\Models\Invoice;
use Livewire\Attributes\On;

class Detail extends Component
{
    public $invoice;
    public $currentImageIndex = 0;
    public $currentItemIndex = 0;
    public $items = [];
    public $images = [];
    public $isOpen = false;

    #[On('showInvoiceDetail')]
    public function loadInvoice($invoiceId)
    {
        $this->invoice = Invoice::with(['items.images'])->findOrFail($invoiceId);
        $this->items = $this->invoice->items;
        $this->currentItemIndex = 0;
        $this->loadItemImages(0);
        $this->isOpen = true;
    }

    public function loadItemImages($itemIndex)
    {
        $this->currentItemIndex = $itemIndex;
        $this->images = $this->items[$itemIndex]->images ?? [];
        $this->currentImageIndex = 0;
    }

    public function nextImage()
    {
        if (count($this->images) > 0) {
            $this->currentImageIndex = ($this->currentImageIndex + 1) % count($this->images);
        }
    }

    public function prevImage()
    {
        if (count($this->images) > 0) {
            $this->currentImageIndex = ($this->currentImageIndex - 1 + count($this->images)) % count($this->images);
        }
    }

    public function selectImage($index)
    {
        $this->currentImageIndex = $index;
    }

    public function close()
    {
        $this->isOpen = false;
        $this->invoice = null;
        $this->items = [];
        $this->images = [];
        $this->dispatch('invoiceDetailClosed');
    }

    public function render()
    {
        return view('livewire.Invoices.detail');
    }
}