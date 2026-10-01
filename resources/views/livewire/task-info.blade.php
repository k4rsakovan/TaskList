<div>
    @if($show && $task)
        <div style="position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1050;
                    display:flex; align-items:center; justify-content:center;"
             wire:click.self="close">
            <div style="background:#fff; border-radius:8px; padding:24px; width:600px; max-width:90%;">
                <h3 class="mb-3">Заявка № {{ $task->id }}</h3>

                <div class="mb-3">
                    <label class="form-label">Заголовок</label>
                    <input type="text" class="form-control" value="{{ $task->title }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Описание</label>
                    <textarea class="form-control" rows="3" disabled>{{ $task->text }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Стоимость</label>
                    <input type="number" class="form-control" value="{{ $task->cost }}" disabled>
                </div>

                @if($task->files->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label">Файлы</label>
                        <ul>
                            @foreach($task->files as $file)
                                <li>
                                    <a href="{{ url('/getFile/'.$task->id.'/'.$file->id) }}">
                                        {{ $file->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="text-end">
                    <button type="button" class="btn btn-secondary" wire:click="close">Закрыть</button>
                </div>
            </div>
        </div>
    @endif
</div>
