@include('config.php');

@servers([ 'web-dev' => 'jq@tapi.tupppai.com', 'apk-dev' => '127.0.0.1', 'apk-production' => '127.0.0.1', 'web-production1' => 'root@www.tupppai.com', 'web-production2' => 'ubuntu@www.tupppai.com'])

@task('web-deploy', ['on' => 'web-dev', 'confirm' => false])
    cd {{$webPath.$tupppaiPath}}
    git checkout develop
    git pull origin develop
    php artisan migrate
    php artisan db:seed
@endtask

@task('web-publish', ['on' => 'web-production1', 'confirm' => true])
    cd {{$webPath.$tupppaiPath}}
    cp -r {{$webPath.$tupppaiPath}} {{$backPath}}/{{$tupppaiPath}}_{{$date}}
    git checkout master
    git pull origin master
    php artisan migrate
@endtask

@task('design-deploy', ['on' => 'web-dev', 'confirm' => true])
    cd {{$webPath.$designPath}}
    git checkout develop
    git pull origin develop
    php artisan migrate
@endtask

@task('design-publish', ['on' => 'web-production1', 'confirm' => true])
    cd {{$webPath.$designPath}}
    cp -r {{$webPath.$designPath}} {{$backPath}}/{{$designPath}}_{{$date}}
    git checkout master
    git pull origin master
    php artisan migrate
@endtask

@task('android-release', ['on' => 'apk-production', 'confirm' => true])
    cd {{$androidPath}}
    git pull origin master
    ./gradlew assembleRelease -Pandroid.injected.signing.store.file={{$keystore}} -Pandroid.injected.signing.store.password={{$keyPwd}} -Pandroid.injected.signing.key.alias={{$keyAlias}} -Pandroid.injected.signing.key.password={{$keyPwd}}
@endtask
