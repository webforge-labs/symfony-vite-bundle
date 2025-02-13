set dotenv-load := false

set positional-arguments

project_root := justfile_directory()

default:
  @just --list

php := "docker compose exec --user=www-data php"
php_root := "docker compose exec --user=root php"

cli *args='':
    {{ php }} bin/console "${@}"

phpstan *args='':
    {{php}} vendor/bin/phpstan "${@}"

phpunit *args='':
    {{ php }} vendor/bin/phpunit "$@"

composer *args='':
    {{ php }} composer "${@}"

prep:
    j phpstan
    j phpunit

up:
    docker compose up -d --remove-orphans
    just fix-docker-permissions

fix-docker-permissions:
    docker compose exec --user=root php bash -c "mkdir -p /var/www/.composer && chown -R 33:33 /var/www"
