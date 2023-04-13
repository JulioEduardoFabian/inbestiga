<?php

use App\Models\Activity;
use App\Models\Project;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('message.{id}', function ($user, $id) {
    return (int) $user->id == (int) $id;
});

Broadcast::channel('projects', function ($id) {
    return Project::find($id);
});

Broadcast::channel('direction', function ($id) {
    return Project::find($id);
});

Broadcast::channel('updates', function ($id) {
    return Activity::find($id);
});