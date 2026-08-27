@if($feedbackMessage)
    <div class="alert alert-{{ $feedbackType }} alert-dismissible fade show" role="alert">
        {{ $feedbackMessage }}
        <button type="button" class="btn-close" wire:click="$set('feedbackMessage', null)"></button>
    </div>
@endif
