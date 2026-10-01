<div>
    <div class="card">
        <div class="card-body">
            <h4 class="text-center font-weight-bold">Мои задачи</h4>
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>№</th><th>Заголовок</th><th>Стоимость</th>
                    <th>Дата создания</th><th>Дата обновления</th>
                    <th>Состояние</th><th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @forelse($myTasks as $task)
                    <tr wire:key="my-task-{{ $task['id'] }}">
                        <td>{{ $task['id'] }}</td>
                        <td>{{ $task['title'] }}</td>
                        <td>{{ $task['cost'] }}</td>
                        <td>{{ $task['created_at'] }}</td>
                        <td>{{ $task['updated_at'] }}</td>
                        <td class="status">{{ $task['status'] }}</td>
                        <td class="action">
                            <button class="btn btn-primary btn-sm"
                                    wire:click="openInfo({{ $task['id'] }})">
                                Информация
                            </button>

                            @if($task['raw_status'] === 'in_work')
                                <button class="btn btn-danger btn-sm"
                                        wire:click="cancel({{ $task['id'] }})"
                                        wire:confirm="Отказаться от задачи №{{ $task['id'] }}?">
                                    Отказаться
                                </button>
                                <button class="btn btn-success btn-sm"
                                        wire:click="complete({{ $task['id'] }})"
                                        wire:confirm="Завершить задачу №{{ $task['id'] }}?">
                                    Завершить
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">Нет задач</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <livewire:task-info />
</div>
