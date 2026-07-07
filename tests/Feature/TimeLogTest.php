<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_multiple_tasks_can_be_submitted_for_one_day(): void
    {
        $user = User::factory()->create();
        $project = Project::create(['name' => 'Alpha Project']);

        $response = $this->actingAs($user)->post(route('time-logs.store'), [
            'work_date' => now()->toDateString(),
            'tasks' => [
                [
                    'project_id' => $project->id,
                    'task_description' => 'First task',
                    'time' => '04:30',
                ],
                [
                    'project_id' => $project->id,
                    'task_description' => 'Second task',
                    'time' => '05:30',
                ],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('timelogs', 2);
        $this->assertSame(600, $user->timeLogs()->sum('total_minutes'));
    }

    public function test_daily_total_cannot_exceed_ten_hours(): void
    {
        $user = User::factory()->create();
        $project = Project::create(['name' => 'Beta Project']);

        $response = $this->actingAs($user)->post(route('time-logs.store'), [
            'work_date' => now()->toDateString(),
            'tasks' => [
                [
                    'project_id' => $project->id,
                    'task_description' => 'First task',
                    'time' => '06:00',
                ],
                [
                    'project_id' => $project->id,
                    'task_description' => 'Second task',
                    'time' => '05:00',
                ],
            ],
        ]);

        $response->assertSessionHasErrors('tasks');
        $this->assertDatabaseCount('timelogs', 0);
    }
}
