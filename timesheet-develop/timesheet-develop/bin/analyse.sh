#!/bin/bash

set -euo pipefail
COLOUR_GREEN=$(tput setaf 2)
RESET=$(tput sgr0)

case "${1:--help}" in
     '--phpunit'|'-pu')
          echo "${COLOUR_GREEN}Running PHP Unit Tests...${RESET}"

          docker-compose exec php composer phpunit tests/Unit

          echo "${COLOUR_GREEN}Setup Test Database For Functional Tests...${RESET}"
          docker-compose exec php bin/console --env=test --no-interaction doctrine:database:drop --if-exists --force
          docker-compose exec php bin/console --env=test doctrine:database:create
          docker-compose exec php bin/console --env=test --no-interaction doctrine:migration:migrate

          echo "${COLOUR_GREEN}Running Functional Tests...${RESET}"
          docker-compose exec php composer phpunit tests/Functional

          echo "${COLOUR_GREEN}Dropping Test Database...${RESET}"
          docker-compose exec php bin/console --env=test --no-interaction doctrine:database:drop --if-exists --force
          ;;
     '--fixer'|'-cs')
          docker-compose exec php composer cs-fixer
          ;;
     '--phpstan'|'-ps')
          docker-compose exec php composer phpstan
          ;;

     '--validate'|'-sv')
          docker-compose exec php bin/console doctrine:schema:validate
          ;;
     '--all'|'-a')
          docker-compose exec php bin/console doctrine:schema:validate
          docker-compose exec php composer cs-fixer
          docker-compose exec php composer phpstan
          docker-compose exec php composer phpunit
          ;;
     '--help'|'-h'| *)
          echo "Usage: environment_tools ${COLOUR_GREEN}[option]${RESET}"
          echo
          echo
          echo "  -pu,  --phpunit         Run phpunit tests"
          echo "  -cs,  --fixer           Run php-cs-fixer"
          echo "  -ps,  --phpstan         Run phpstan analyzer"
          echo "  -a,   --all             Run all tools"
          echo "  -sv   --validate        Run doctrine schema validate"
          echo "  -h,   --help            Run help"
          exit 1
          ;;
esac