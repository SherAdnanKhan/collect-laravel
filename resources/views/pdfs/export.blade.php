<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="{{ public_path('assets/css/pdfs/export.css') }}" />
    </head>
    <body>
        <header>
            <img src="{{ public_path('assets/images/email-logo.png') }}" />
            <span id="date">Date printed: {{ $datestamp }}</span>
        </header>

        <h1 id="title">Credits Report</h1>

        <!-- Project fields block -->
        <div class="block">
            <p>
                <!-- Main Artist Name -->
                @if($project->artist)
                    <span>{{ $project->artist->name }}</span>
                @endif
                <!-- Project Name -->
                <span>"{{ $project->name }}"</span>
            </p>
            <p>
                <!-- Label Name -->
                @if($project->label)
                    <span>{{ $project->label->name }}</span>
                @endif
                <!-- Project Number -->
                <span>#{{ $project->number }}</span>
            </p>
            <p>
                <!-- Total # of Recordings -->
                <span>TOTAL RECORDINGS:</span>
                <span>{{ $project->recordings()->count() }}</span>
            </p>
            <p>
                <!-- Total # of Files -->
                <span>TOTAL FILES:</span>
                <span>{{ $project->allFiles()->count() }}</span>
            </p>
            <p>
                <!-- Project Notes -->
                <span>PROJECT NOTES:</span>
                <span>{{ !empty($project->description) ? $project->description : 'none' }}</span>
            </p>
        </div>

        <!-- Recording fields -->
        @foreach($recordings as $recording)
            <div class="block">
                <p>
                    <!-- Song Title -->
                    <span><strong>"{{ $recording->song->title }}"</strong></span>
                </p>
                @if(!empty($recording->version))
                <p>
                    <!-- Version -->
                    <span>{{ $recording->version }}</span>
                </p>
                @endif
                @if(!empty($recording->subtitle))
                <p>
                    <!-- Subtitle -->
                    <span>{{ $recording->subtitle }}</span>
                </p>
                @endif
                <p>
                    <!-- ISRC -->
                    <span>ISRC: </span>
                    <span>{{ $recording->isrc }}</span>
                </p>
                <p>
                    <!-- Duration -->
                    <span>Duration: </span>
                    <span>{{ sprintf('%02d:%02d:%02d', ($recording->duration/3600),($recording->duration/60%60), $recording->duration%60) }}</span>
                </p>
                <p>
                    <!-- Tempo -->
                    <span>Tempo: </span>
                    <span>{{ $recording->tempo }}</span>
                </p>
                <p>
                    <!-- Key Signature -->
                    <span>Key Signature: </span>
                    <span>{{ $recording->key_signature }}</span>
                </p>
                <p>
                    <!-- Time Signature -->
                    <span>Time Signature: </span>
                    <span>{{ $recording->time_signature }}</span>
                </p>
                @if($recording->recorded_on)
                    <p>
                        <!-- Recorded date -->
                        <span>Recorded On: </span>
                        <span>{{ $recording->recorded_on->toDateString() }}</span>
                    </p>
                @endif
                @if($recording->mixed_on)
                    <p>
                        <!-- Mixed date -->
                        <span>Mixed On: </span>
                        <span>{{ $recording->mixed_on->toDateString() }}</span>
                    </p>
                @endif
            </div>

            <!-- Recording Parties -->
            <div class="block">
                @php
                    // Build a collection of every credit (recording or session)
                    $recordingCredits = $recording->credits;

                    $sessionCredits = collect();
                    foreach ($recording->sessions as $session) {
                        $sessionCredits = $sessionCredits->combine($session->credits);
                    }

                    $allCredits = $recordingCredits->combine($sessionCredits);

                    // Filter only musicians and map them
                    // by their instruments.
                    $musicians = $allCredits->filter(function($item) {
                        return strpos($item->role->ddex_key, 'Musician') !== FALSE;
                    })->mapToGroups(function($item) {
                        return [$item->instrument->name => $item];
                    });

                    $producers = $allCredits->filter(function($item) {
                        return strpos($item->role->ddex_key, 'Producer') !== FALSE;
                    })->sortBy('role.ddex_key')->mapToGroups(function ($item, $key) {
                        return [$item->role->name => $item];
                    });

                    $engineers = $allCredits->filter(function($item) {
                        return strpos($item->role->ddex_key, 'Engineer') !== FALSE;
                    })->sortBy('role.ddex_key')->mapToGroups(function ($item, $key) {
                        return [$item->role->name => $item];
                    });

                    // Filter out non-musician credits
                    // and then map them by their roles.
                    $otherCredits = $allCredits->filter(function($item) {
                        return strpos($item->role->ddex_key, 'Producer') === FALSE &&
                            strpos($item->role->ddex_key, 'Engineer') === FALSE &&
                            strpos($item->role->ddex_key, 'Musician') === FALSE;
                    })->mapToGroups(function ($item, $key) {
                        return [$item->role->name => $item];
                    });
                @endphp

                <!-- Roles -->
                @foreach([$producers, $engineers, $otherCredits, $musicians] as $credits)
                    @foreach($credits as $groupkey => $group)
                        <p>
                            <span>{{ $groupkey }}: </span>
                            <span>{{ collect($group)->implode('party.name', ', ') }}</span>
                        </p>
                    @endforeach
                @endforeach
            </div>


            <!-- Session Credits -->
            {{-- Sessions Listed in this order: Tracking always first, Mixing and Mastering always the last two - any session inbetween, doesn’t matter the order it’s displayed. --}}
            <div class="block">
                @php
                    // Get all recording credits
                    $sessions = $recording->sessions;

                    // Only the tracking session.
                    $trackingSession = $sessions->filter(function($session) {
                        return $session->type->ddex_key === 'Tracking';
                    })->first();

                    // Only the mixing and mastering sessions
                    $lastSessions = $sessions->filter(function($session) {
                        return in_array($session->type->ddex_key, ['Mixing', 'Mastering']);
                    })->sortByDesc('type.ddex_key');

                    // All other sessions.
                    $sessions = $sessions->filter(function($session) {
                        return !in_array($session->type->ddex_key, ['Tracking', 'Mixing', 'Mastering']);
                    })->sortBy('type.ddex_key');

                    $allSessions = collect([$trackingSession])
                        ->merge($sessions)
                        ->merge($lastSessions)
                        ->filter()->all();
                @endphp

                @foreach($allSessions as $session)
                    <p>
                        <span>{{ $session->type->name }}: </span>
                        @if ($session->venue->name)
                            <span>{{ $session->venue->name }}</span>,
                        @endif
                        @if ($session->venue->name)
                            <span>{{ $session->venue_room }}</span>,
                        @endif
                        @if ($session->venue->name)
                            <span>{{ $session->venue->address }}</span> -
                        @endif

                        @if($session->bitdepth)
                            <span>{{ $session->bitdepth }} bit</span>,
                        @endif
                        @if($session->samplerate)
                            <span>{{ $session->samplerate / 1000 }}kHz</span>,
                        @endif
                        <span>{{ $session->union_session ? 'Union, ' : '' }}</span>
                        <span>{{ $session->analog_session ? 'Analog, ' : '' }}</span>
                        @if ($session->timecode_type)
                            <span>{{ $session->timecode_type }},</span>
                        @endif
                        @if ($session->timecode_frame_rate)
                            <span>{{ $session->timecode_frame_rate }}</span>,
                        @endif
                        <span>{{ $session->drop_frame ? 'Drop Frame, ' : '' }}</span>
                        <span>{{ $session->description ? $session->description : 'none' }}</span>
                    </p>
                @endforeach
            </div>

        @endforeach
    </body>
</html>
