<div>
    @if($show)
        <div style="position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1050;
                    display:flex; align-items:center; justify-content:center;">
            <div style="background:#fff; border-radius:8px; padding:24px; width:400px; max-width:90%;">
                <h3 class="mb-3">Оцените работу, {{ $userName }}</h3>

                <div class="mb-3">
                    @for($i = 5; $i >= 1; $i--)
                        <label class="me-2">
                            <input type="radio" wire:model="rating" value="{{ $i }}">
                            {{ $i }}★
                        </label>
                    @endfor
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary"
                            wire:click="$set('show', false)">Отмена</button>
                    <button type="button" class="btn btn-primary"
                            wire:click="submit">Спасибо!</button>
                </div>
            </div>
        </div>
    @endif
</div>
