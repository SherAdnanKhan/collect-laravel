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
                <span>Craig Childs</span>
                <!-- Project Name -->
                <span>"Example Project Name"</span>
            </p>
            <p>
                <!-- Label Name -->
                <span>Capitol Records</span>
                <!-- Project Number -->
                <span>#VEVA7812387123</span>
            </p>
            <p>
                <!-- Total # of Recordings -->
                <span>TOTAL RECORDINGS:</span>
                <span>12</span>
            </p>
            <p>
                <!-- Total # of Files -->
                <span>TOTAL FILES:</span>
                <span>2,794</span>
            </p>
            <p>
                <!-- Project Notes -->
                <span>PROJECT NOTES:</span>
                <span>Lorem ipsum dolor sit amet, con carne avec mi danke gracias, mucho, gusto amor.</span>
            </p>
        </div>

        <!-- Recording fields -->
        @foreach(range(1, 2) as $i)
            <div class="block">
                <p>
                    <!-- Song Title -->
                    <span><strong>"Prove You Wrong"</strong></span>
                </p>
                <p>
                    <!-- Version -->
                    <span>Version 2</span>
                </p>
                <p>
                    <!-- Subtitle -->
                    <span>Lorem Ipsum</span>
                </p>
                <p>
                    <!-- ISRC -->
                    <span>ISRC: </span>
                    <span>US-VE-12-12451</span>
                </p>
                <p>
                    <!-- Duration -->
                    <span>Duration: </span>
                    <span>0h 5m 23s</span>
                </p>
                <p>
                    <!-- Tempo -->
                    <span>Tempo: </span>
                    <span>72bpm</span>
                </p>
                <p>
                    <!-- Key Signature -->
                    <span>Key Signature: </span>
                    <span>A#</span>
                </p>
                <p>
                    <!-- Recorded date -->
                    <span>Recorded On: </span>
                    <span>2019-03-29</span>
                </p>
                <p>
                    <!-- Mixed date -->
                    <span>Mixed On: </span>
                    <span>2019-04-05</span>
                </p>
            </div>
        @endforeach

        <!-- Recording Parties -->
        <div class="block">
            <!-- Roles -->
            <p>
                <span>Producer: </span>
                <span>Chris Neal, Craig Childs, Deborah Fairchild</span>
            </p>
            <p>
                <span>Tracking Engineer: </span>
                <span>Kieran Osgood, Wes Bos</span>
            </p>
            <p>
                <span>Vocal Engineer: </span>
                <span>Chris Neal, Craig Childs</span>
            </p>
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
