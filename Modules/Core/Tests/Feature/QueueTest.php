<?php

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Queue;
use Modules\Core\Jobs\ProcessMediaJob;

it('ProcessMediaJob can be dispatched to the queue', function () {
    Queue::fake();

    ProcessMediaJob::dispatch('test/file.gif');

    Queue::assertPushed(ProcessMediaJob::class, function ($job) {
        return $job->filePath === 'test/file.gif';
    });
});

it('ProcessMediaJob has correct tries and timeout', function () {
    $job = new ProcessMediaJob('sample.gif');

    expect($job->tries)->toBe(3)
        ->and($job->timeout)->toBe(120);
});

it('ProcessMediaJob implements ShouldQueue', function () {
    $job = new ProcessMediaJob('sample.gif');

    expect($job)->toBeInstanceOf(ShouldQueue::class);
});
