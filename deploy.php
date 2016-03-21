<?php

require 'recipe/composer.php';

set('repository', 'https://github.com/lamberski/premiefier.git');
set('default_stage', 'production');
set('shared_files', ['.env']);
serverList('deploy.yml');

task('deploy:assets', function () {
    runLocally('gulp build');
    foreach (glob('public/*/*') as $file) {
        upload($file, '{{deploy_path}}/release/' . $file);
    }
})->desc('Compile and upload all static assets (JS, CSS, images)');

before('deploy:symlink', 'deploy:assets');
