<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="{{ asset('css/pdfs/export.css') }}" />
    </head>
    <body>
        <header>
            <img src="{{ asset('images/logo.pdf.png') }}" />
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
    </body>
</html>
