<?php

use Osnova\TJournal;
use Osnova\Services\Timeline\Owners\TimelineCategory;

// Create resource instance with the "1.4" API version.
$tjournal = TJournal::make('1.4');

$entries = $tjournal->getTimelineEntries(new TimelineCategory('index'));

foreach ($entries as $entry) {
    //var_dump($entry);
    echo '<h1>'.$entry->getTitle().'</h1> --';
    echo $entry->getIntroInFeed() ? 'yes' : 'NO' ;
    echo '--<p>'.$entry->getIntro().'</p>';
    echo "<hr>";
}
//var_dump($entries);
