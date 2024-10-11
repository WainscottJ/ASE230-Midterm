<?php
// Read services.txt file
$servicesData = file('data/services.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$services = [];

// Parse service entries
for ($i = 0; $i < count($servicesData); $i += 3) {
    $service = [
        'title' => explode(': ', $servicesData[$i])[1],
        'description' => explode(': ', $servicesData[$i + 1])[1],
        'icon' => explode(': ', $servicesData[$i + 2])[1],
    ];
    $services[] = $service;
}
?>

<section class="page-section" id="services">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Services</h2>
            <h3 class="section-subheading text-muted">Below are our services</h3>
        </div>
        <div class="row text-center">
            <?php foreach ($services as $service): ?>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="<?php echo $service['icon']; ?> fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3"><?php echo $service['title']; ?></h4>
                    <p class="text-muted"><?php echo $service['description']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
