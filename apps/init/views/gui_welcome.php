<div class="dashboard">
    <?php foreach (MenuItem::getAllMenus('getDashboard') as $icon): ?>
        <div>
            <h1><?php echo $icon->label; ?></h1>
            <a href="<?php echo $this->buildLink($icon->url); ?>">
                <img src="<?php echo $this->url($icon->image); ?>" alt="<?php echo $icon->label; ?>"/>
            </a>
        </div>
    <?php endforeach; ?>
</div>