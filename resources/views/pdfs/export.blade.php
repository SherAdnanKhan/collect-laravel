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
                <!-- Roles -->
                @foreach($recording->credits->mapToGroups(function ($item, $key) {
                    return [$item->role->name => $item];
                }) as $roleGroupName => $roleGroup)
                    <p>
                        <span>{{ $roleGroupName }}: </span>
                        <span>{{ collect($roleGroup)->implode('party.name', ', ') }}</span>
                    </p>
                @endforeach
                <!-- Musicians, by instrument -->
                <p>
                    <span>Bass: </span>
                    <span>Chris Neal</span>
                </p>
                <p>
                    <span>Electric Guitar: </span>
                    <span>Joel Currie, Craig Childs</span>
                </p>
            </div>
        @endforeach


        <!-- Session Credits -->
        {{-- Sessions Listed in this order: Tracking always first, Mixing and Mastering always the last two - any
session inbetween, doesn’t matter the order it’s displayed. --}}
        <div class="block">
            <p>
                <span>Tracking: </span>
                <span>Sonic Element Studio</span>,
                <span>Los Angeles, CA</span> -
                <span>24 bit, 48KHz, WAV</span>,
                <span>Lorem ipsum dolor sit amet</span>
            </p>
            <p>
                <span>Overdub: </span>
                <span>Sonic Element Studio</span>,
                <span>Los Angeles, CA</span> -
                <span>24 bit, 48KHz, WAV</span>,
                <span>Lorem ipsum dolor sit amet</span>
            </p>
            <p>
                <span>Mixing: </span>
                <span>Sonic Element Studio</span>,
                <span>Los Angeles, CA</span> -
                <span>24 bit, 48KHz, WAV</span>,
                <span>Lorem ipsum dolor sit amet</span>
            </p>
            <p>
                <span>Mastering: </span>
                <span>Sonic Element Studio</span>,
                <span>Los Angeles, CA</span> -
                <span>24 bit, 48KHz, WAV</span>,
                <span>Lorem ipsum dolor sit amet</span>
            </p>
        </div>
    </body>
</html>
