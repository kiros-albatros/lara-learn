#!/bin/sh

set -e

# Запуск проверки phpstan
#./vendor/bin/phpstan --xdebug analyse
# Зауск проверки стандарта PSR-12
#./vendor/bin/phpcs
# Deptrac слои
#vendor/bin/deptrac analyse --config-file=depfile-layers.yaml
# Deptrac модули
#vendor/bin/deptrac analyse --config-file=depfile-modules.yaml