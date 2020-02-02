<div class="card mt-3 color:--text-default-color">
    <ul class="list-reset text-xs">
        @foreach($project->activity as $activity)
            <li class=" {{ $loop->last ? '' :  'mb-1' }}">
                @include("projects.activity.{$activity->description}")
                <span class="text-default">{{ $activity->created_at->diffForHumans(false, true) }}</spap>
            </li>
        @endforeach
    </ul>
</div>
