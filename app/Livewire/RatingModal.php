<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\User;
use App\Services\RatingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class RatingModal extends Component
{
    public bool $show = false;
    public ?int $taskId = null;
    public ?int $userId = null;
    public ?string $userName = null;
    public int $rating = 5;

    #[On('open-rating')]
    public function open(int $taskId, int $userId): void
    {
        $user = User::query()->findOrFail($userId);

        $this->taskId = $taskId;
        $this->userId = $userId;
        $this->userName = $user->name;
        $this->rating = 5;
        $this->show = true;
    }

    /**
     * @param  RatingService  $service
     * @return void
     */
    public function submit(RatingService $service): void
    {
        $worker = User::query()->findOrFail($this->userId);

        $service->store(
            Auth::user(),
            $worker,
            $this->taskId,
            $this->rating
        );

        $this->show = false;
        $this->dispatch('notify', type: 'success', message: 'Спасибо за оценку!');
    }

    public function render()
    {
        return view('livewire.rating-modal');
    }
}
