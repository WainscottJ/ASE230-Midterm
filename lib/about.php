<?php
// Read the about.txt file
$aboutData = file('data/about.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$aboutTitle = explode(': ', $aboutData[0])[1];
$aboutSubtitle = explode(': ', $aboutData[1])[1];
$timelineEntries = [];
$callToAction = '';

// Parse timeline entries
for ($i = 3; $i < count($aboutData); $i++) {
    if (strpos($aboutData[$i], 'Year:') !== false) {
        $entry = [
            'year' => explode(': ', $aboutData[$i])[1],
            'title' => explode(': ', $aboutData[++$i])[1],
            'content' => explode(': ', $aboutData[++$i])[1],
            'image' => explode(': ', $aboutData[++$i])[1],
        ];
        $timelineEntries[] = $entry;
    }
}

// Get call to action text
$callToAction = explode(': ', $aboutData[count($aboutData) - 1])[1];
?>

<section id="about">
    <div class="container text-center"> <!-- Add text-center here -->
        <h2 class="section-heading text-uppercase"><?php echo $aboutTitle; ?></h2>
        <h3 class="section-subheading text-muted"><?php echo $aboutSubtitle; ?></h3>
        <ul class="timeline">
            <?php foreach ($timelineEntries as $entry): ?>
                <li class="<?php echo $entry['year'] === 'July 2020' ? 'timeline-inverted' : ''; ?>">
                    <div class="timeline-image">
                        <img class="rounded-circle img-fluid" src="<?php echo $entry['image']; ?>" alt="..." />
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4><?php echo $entry['year']; ?></h4>
                            <h4 class="subheading"><?php echo $entry['title']; ?></h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted"><?php echo $entry['content']; ?></p>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
            <li class="timeline-inverted">
                <div class="timeline-image">
                    <h4><?php echo $callToAction; ?></h4>
                </div>
            </li>
        </ul>
    </div>
</section>
