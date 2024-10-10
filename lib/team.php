<?php
// team.php

// Sample data for team members
$members = [
    [
        'name' => 'Parveen Anand',
        'role' => 'Lead Designer',
        'image' => 'assets/img/team/1.jpg',
        'twitter' => '#!',
        'facebook' => '#!',
        'linkedin' => '#!'
    ],
    [
        'name' => 'Diana Petersen',
        'role' => 'Lead Marketer',
        'image' => 'assets/img/team/2.jpg',
        'twitter' => '#!',
        'facebook' => '#!',
        'linkedin' => '#!'
    ],
    [
        'name' => 'Larry Parker',
        'role' => 'Lead Developer',
        'image' => 'assets/img/team/3.jpg',
        'twitter' => '#!',
        'facebook' => '#!',
        'linkedin' => '#!'
    ],
];
?>

<!-- Team Section -->
<section class="page-section bg-light" id="team">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Our Amazing Team</h2>
            <h3 class="section-subheading text-muted">.</h3>
        </div>
        <div class="row">
            <?php foreach ($members as $member): ?>
                <div class="col-lg-4">
                    <div class="team-member">
                        <img class="mx-auto rounded-circle" src="<?php echo $member['image']; ?>" alt="<?php echo $member['name']; ?>" />
                        <h4><?php echo $member['name']; ?></h4>
                        <p class="text-muted"><?php echo $member['role']; ?></p>
                        <a class="btn btn-dark btn-social mx-2" href="<?php echo $member['twitter']; ?>" aria-label="<?php echo $member['name']; ?> Twitter Profile"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="<?php echo $member['facebook']; ?>" aria-label="<?php echo $member['name']; ?> Facebook Profile"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="<?php echo $member['linkedin']; ?>" aria-label="<?php echo $member['name']; ?> LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <p class="large text-muted">Our team is dedicated to making the best possible outcomes.</p>
            </div>
        </div>
    </div>
</section>
