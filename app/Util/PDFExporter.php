<?php

namespace App\Util;

use App\Models\Project;
use App\Models\Recording;

/**
 * This utility should generate a valid PDF in the
 * desired format, given a Project and optionally
 * a recording.
 */
class PDFExporter
{

    /**
     * The project we're generating the PDF for.
     *
     * @var \App\Models\Project
     */
    private $project;

    /**
     * The recording we will generate the
     * PDF from.
     * @var \App\Models\Recording|null
     */
    private $recording;

    /**
     * When initialising the exporter we need a project and
     * optionally a recording.
     *
     * @param \App\Models\Project        $project
     * @param \App\Models\Recording|null $recording
     */
    public function __construct(Project $project, $recording)
    {
        if (!is_null($recording) && !($recording instanceof Recording)) {
            throw new \Exception('The recording must be either null or an instance of ' . Recording::class);
        }

        $this->project = $project;
        $this->recording = $recording;
    }

    /**
     * Generate the PDF from the data.
     *
     * @return \Knp\Snappy\Pdf
     */
    public function generate()
    {
        $snappy = resolve('snappy.pdf.wrapper');

        return $snappy->loadView('pdfs.export', $this->getViewData());
    }

    private function getViewData(): array
    {
        $data = [
            'test' => 'Hello World',
        ];

        // TODO
        //
        // Key-value data to be passed into the
        // export view.

        return $data;
    }
}
