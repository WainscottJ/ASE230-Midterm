<?php
// Define the path to the data file
$dataFile = 'data/portfolio_items.txt';

// Read data from the file
$portfolioItems = file($dataFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>

<!-- Portfolio Grid Section -->
<section class="page-section bg-light" id="portfolio">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Portfolio</h2>
            <h3 class="section-subheading text-muted">Here are some items we have done in the past</h3>
        </div>
        <div class="row">
            <?php foreach ($portfolioItems as $index => $item): 
                // Split the item into its components
                list($title, $intro, $description, $imageUrl, $client, $category) = explode('|', $item);
            ?>
                <!-- Portfolio Item -->
                <div class="col-lg-4 col-sm-6 mb-4">
                    <div class="portfolio-item">
                        <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal<?= $index + 1 ?>">
                            <div class="portfolio-hover">
                                <div class="portfolio-hover-content"><i class="fas fa-plus fa-3x"></i></div>
                            </div>
                            <img class="img-fluid" src="<?= $imageUrl ?>" alt="<?= $title ?>" />
                        </a>
                        <div class="portfolio-caption">
                            <div class="portfolio-caption-heading"><?= $title ?></div>
                            <div class="portfolio-caption-subheading text-muted"><?= $category ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Portfolio Modals Section -->
<?php foreach ($portfolioItems as $index => $item): 
    list($title, $intro, $description, $imageUrl, $client, $category) = explode('|', $item);
?>
    <div class="portfolio-modal modal fade" id="portfolioModal<?= $index + 1 ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="close-modal" data-bs-dismiss="modal"><img src="assets/img/close-icon.svg" alt="Close modal" /></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="modal-body">
                                <!-- Project details -->
                                <h2 class="text-uppercase"><?= $title ?></h2>
                                <p class="item-intro text-muted"><?= $intro ?></p>
                                <img class="img-fluid d-block mx-auto" src="<?= $imageUrl ?>" alt="<?= $title ?>" />
                                <p><?= $description ?></p>
                                <ul class="list-inline">
                                    <li><strong>Client:</strong> <?= $client ?></li>
                                    <li><strong>Category:</strong> <?= $category ?></li>
                                </ul>
                                <button class="btn btn-primary btn-xl text-uppercase" data-bs-dismiss="modal" type="button">
                                    <i class="fas fa-xmark me-1"></i>
                                    Close Project
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
