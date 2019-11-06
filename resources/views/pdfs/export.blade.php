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
                <span>{{ $project->files()->count() }}</span>
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
                <p>
                    <!-- Recorded date -->
                    <span>Recorded On: </span>
                    <span>{{ $recording->recorded_on->toDateString() }}</span>
                </p>
                <p>
                    <!-- Mixed date -->
                    <span>Mixed On: </span>
                    <span>{{ $recording->mixed_on->toDateString() }}</span>
                </p>
            </div>

            <!-- Recording Parties -->
            <div class="block">
                @php
                    // Filter only musicians and map them
                    // by their instruments.
                    $musicians = $recording->credits->filter(function($item) {
                        return $item->role->ddex_key === 'Musician';
                    })->mapToGroups(function($item) {
                        return [$item->instrument->name => $item];
                    });

                    // Filter out non-musician credits
                    // and then map them by their roles.
                    $nonMusicians = $recording->credits->filter(function($item) {
                        return $item->role->ddex_key !== 'Musician';
                    })->mapToGroups(function ($item, $key) {
                        return [$item->role->name => $item];
                    });
                @endphp

                <!-- Roles -->
                @foreach($nonMusicians as $roleGroupName => $roleGroup)
                    <p>
                        <span>{{ $roleGroupName }}: </span>
                        <span>{{ collect($roleGroup)->implode('party.name', ', ') }}</span>
                    </p>
                @endforeach
                <!-- Musicians, by instrument -->
                @foreach($musicians as $instrumentName => $musicianGroup)
                    <p>
                        <span>{{ $instrumentName }}: </span>
                        <span>{{ collect($musicianGroup)->implode('party.name', ', ') }}</span>
                    </p>
                @endforeach
            </div>
        @endforeach


        <!-- Session Credits -->
        {{-- Sessions Listed in this order: Tracking always first, Mixing and Mastering always the last two - any session inbetween, doesn’t matter the order it’s displayed. --}}
        <div class="block">
            @php
                // Only the tracking session.
                $trackingSession = $project->sessions->filter(function($session) {
                    return $session->type->ddex_key === 'Tracking';
                })->first();

                // Only the mixing and mastering sessions
                $lastSessions = $project->sessions->filter(function($session) {
                    return in_array($session->type->ddex_key, ['Mixing', 'Mastering']);
                });

                // All other sessions.
                $sessions = $project->sessions->filter(function($session) {
                    return !in_array($session->type->ddex_key, ['Tracking', 'Mixing', 'Mastering']);
                });
            @endphp

            @if($trackingSession)
                <p>
                    <span>Tracking: </span>
                    <span>{{ $trackingSession->venue->name }}</span>,
                    <span>{{ $trackingSession->venue_room }}</span>,
                    <span>{{ $trackingSession->venue->address }}</span> -

                    @if($trackingSession->bitdepth)
                        <span>{{ $trackingSession->bitdepth }} bit</span>,
                    @endif
                    @if($trackingSession->samplerate)
                        <span>{{ $trackingSession->samplerate / 1000 }}kHz</span>,
                    @endif
                    <span>{{ $trackingSession->union_session ? 'Union, ' : '' }}</span>
                    <span>{{ $trackingSession->analog_session ? 'Analog, ' : '' }}</span>
                    <span>{{ $trackingSession->timecode_type }}</span>,
                    <span>{{ $trackingSession->timecode_frame_rate }}</span>,
                    <span>{{ $trackingSession->drop_frame ? 'Drop Frame, ' : '' }}</span>
                    <span>{{ $trackingSession->description ? $trackingSession->description : 'none' }}</span>
                </p>
            @endif

            @foreach($sessions as $session)
                <p>
                    <span>{{ $session->type->name }}: </span>
                    <span>{{ $session->venue->name }}</span>,
                    <span>{{ $session->venue_room }}</span>,
                    <span>{{ $session->venue->address }}</span> -

                    @if($session->bitdepth)
                        <span>{{ $session->bitdepth }} bit</span>,
                    @endif
                    @if($session->samplerate)
                        <span>{{ $session->samplerate / 1000 }}kHz</span>,
                    @endif
                    <span>{{ $session->union_session ? 'Union, ' : '' }}</span>
                    <span>{{ $session->analog_session ? 'Analog, ' : '' }}</span>
                    <span>{{ $session->timecode_type }}</span>,
                    <span>{{ $session->timecode_frame_rate }}</span>,
                    <span>{{ $session->drop_frame ? 'Drop Frame, ' : '' }}</span>
                    <span>{{ $session->description ? $session->description : 'none' }}</span>
                </p>
            @endforeach

            @foreach($lastSessions as $session)
                <p>
                    <span>{{ $session->type->name }}: </span>
                    <span>{{ $session->venue->name }}</span>,
                    <span>{{ $session->venue_room }}</span>,
                    <span>{{ $session->venue->address }}</span> -

                    @if($session->bitdepth)
                        <span>{{ $session->bitdepth }} bit</span>,
                    @endif
                    @if($session->samplerate)
                        <span>{{ $session->samplerate / 1000 }}kHz</span>,
                    @endif
                    <span>{{ $session->union_session ? 'Union, ' : '' }}</span>
                    <span>{{ $session->analog_session ? 'Analog, ' : '' }}</span>
                    <span>{{ $session->timecode_type }}</span>,
                    <span>{{ $session->timecode_frame_rate }}</span>,
                    <span>{{ $session->drop_frame ? 'Drop Frame, ' : '' }}</span>
                    <span>{{ $session->description ? $session->description : 'none' }}</span>
                </p>
            @endforeach
        </div>
    </body>
</html>
