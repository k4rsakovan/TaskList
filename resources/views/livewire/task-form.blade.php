<div>
    @if($showCreate || $showEdit)
        <div style="position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1050;
                    display:flex; align-items:center; justify-content:center;"
             wire:click.self="close">
            <div style="background:#fff; border-radius:8px; padding:24px; width:600px; max-width:90%;">
                <h3 class="mb-3">
                    {{ $showCreate ? 'Добавить задачу' : 'Редактировать задачу № '.$taskId }}
                </h3>

                <form wire:submit="save">
                    <div class="mb-3">
                        <label class="form-label">Заголовок</label>
                        <input type="text" class="form-control" wire:model="title">
                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Описание</label>
                        <textarea class="form-control" rows="3" wire:model="text"></textarea>
                        @error('text') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Стоимость</label>
                        <input type="number" class="form-control" wire:model="cost">
                        @error('cost') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    @if($showEdit)
                        <div class="mb-3">
                            <label class="form-label">Назначить</label>
                            <select class="form-select" wire:model="worker">
                                <option value="">— не назначен —</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" wire:click="close">
                            Отмена
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ $showCreate ? 'Создать' : 'Сохранить' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
