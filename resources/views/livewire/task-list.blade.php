<div>
    @permission('task_create')
    <div class="m-3">
        <button type="button" class="btn btn-primary btn-lg" wire:click="openCreate">
            Создать задачу
        </button>
    </div>
    @endpermission

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center font-weight-bold">Список задач</h4>

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Заголовок</th>
                            <th>Стоимость</th>
                            <th>Дата создания</th>
                            <th>Дата обновления</th>
                            <th>Состояние</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($tasks as $task)
                            <tr wire:key="task-{{ $task['id'] }}" class="tasks_row">
                                <td>{{ $task['id'] }}</td>
                                <td>{{ $task['title'] }}</td>
                                <td>{{ $task['cost'] }}</td>
                                <td>{{ $task['created_at'] }}</td>
                                <td>{{ $task['updated_at'] }}</td>
                                <td class="status">{{ $task['status'] }}</td>
                                <td class="action">
                                    @if($task['role'] === 'worker')
                                        <button class="btn btn-primary btn-sm"
                                                wire:click="openInfo({{ $task['id'] }})">
                                            Информация
                                        </button>

                                        @if($task['raw_status'] === 'new')
                                            <button class="btn btn-success btn-sm"
                                                    wire:click="takeToWork({{ $task['id'] }})"
                                                    wire:confirm="Вы уверены, что хотите взять задачу №{{ $task['id'] }}?">
                                                Взять в работу
                                            </button>
                                        @endif
                                    @else
                                        <button class="btn btn-primary btn-sm"
                                                wire:click="openEdit({{ $task['id'] }})">
                                            Редактировать
                                        </button>

                                        @if($task['raw_status'] === 'complete' && $task['user_id'])
                                            <button class="btn btn-warning btn-sm"
                                                    wire:click="payForWork({{ $task['id'] }})">
                                                Оплатить
                                            </button>
                                            <button class="btn btn-info btn-sm"
                                                    wire:click="openRating({{ $task['id'] }}, {{ $task['user_id'] }})">
                                                Оценить
                                            </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Задач нет</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <livewire:task-form/>
    <livewire:task-info/>
    <livewire:rating-modal/>
</div>
