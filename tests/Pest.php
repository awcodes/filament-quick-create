<?php

declare(strict_types=1);

pest()
    ->extend(Awcodes\QuickCreate\Tests\TestCase::class)
    ->in('src/Feature', 'src/Unit');
