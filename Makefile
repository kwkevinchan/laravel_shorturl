BASE_IMAGE=php-base

# build base image
build-base:
	docker build -t $(BASE_IMAGE) - < docker/php/base.Dockerfile

build-dev: build-base
	docker build -t php-dev - < docker/php/dev.Dockerfile

dev: build-dev
	docker compose up -d

dev-exec:
	docker compose exec php-fpm bash
