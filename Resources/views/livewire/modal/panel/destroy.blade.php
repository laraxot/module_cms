<x-wire-elements-pro::bootstrap.modal on-submit="delete" :content-padding="false">
    <x-slot name="title">Delete Model</x-slot>
 
   Are you sure to delete id {{$model_id}} of {{$model_type}} ?
    
    <x-slot name="buttons">
          
        <button type="submit" class="btn btn-primary">
            Delete
        </button>
        
        <button type="button" class="btn btn-danger" wire:click="$emit('modal.close')">
            Cancel
        </button>
    </x-slot>
</x-wire-elements-pro::bootstrap.modal>