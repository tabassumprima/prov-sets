<?php

namespace App\Traits;

use App\Models\Event;

trait EventTrait
{
    public function addToCalendar($title, $type, $color = 'info', $starts_at = null, $ends_at = null, $description = null)
    {
        $event = new Event();
        $event->organization_id = auth()->user()->organization_id;
        $event->title       = $title;
        $event->color       = $color;
        $event->type        = $type;
        $event->starts_at   = $starts_at === null ? now() : $starts_at;
        $event->ends_at     = $ends_at;
        $event->description = $description;
        $event->save();

        return $event;
    }
}
