# Witcher

Witcher is a complete online web app packed with features from bug tracking to project management. <br/>
It will bring the team together, by helping you at tracking, assigning, reporting and managing all work and time within a beautiful <br/>
and user-friendly interface. Deliver experiences across all software development teams.<br/>
Witcher will help to track the load of each member of the team and their effort into the project. <br/>


## Requirements
1. Docker v18.02.0+
2. Docker compose v3.6
3. RabbitMQ
4. Redis
5. MySQL 8.0
6. Nginx
7. PHP >=7.4

## Dev Environment Installation

1. Setup `jagaad-user-api` service;
2. Copy `.env` file into `.env.local` and **populate all environment variables correctly**;
3. To setup project invoke `./bin/start.sh --env="dev" --build` script;
4. If you want only to start project `./bin/start.sh --env="dev"`
5. `docker-compose exec php bin/console --no-interaction doctrine:migration:migrate`
6. Setup rabbitMQ queues `docker-compose exec php bin/console messenger:setup-transports`
7. Create elasticsearch indices `docker-compose exec php bin/console fos:elastica:create`
8. Create api-token in jagaad-user-api `docker-compose exec php bin/console api_token:create --create="witcher"`
9. Create system user via command `docker-compose exec php bin/console user:create`
   - Copy jira accountId from your **jira profile** page browser address line
   - Copy Gitlab user Id from [Gitlab profile page](https://gitlab.com/-/profile)
10. Invoke jira migrations **docker-compose exec php bin/console witcher:migration --type="all"**

## Usage

- Use the following url to open api documentation http://localhost:8899/api
- GraphQL is available via http://localhost:8899/api/graphql

## Authentication into system

1. Please see [jagaad-user-api](https://gitlab.com/jagaad-team/jagaad-user-api) readme
2. System user can be created via command `docker-compose exec php bin/console user:create`
3. You need to create postload redirect url in google cloud to make google redirect to this url after authentication is successful
4. Use `/_user-provider/authentication/google/auth-url` endpoint to get Google authentication URL
5. Use returned Google authentication url for user authentication process

### To run available code-style fixing and analyzing tools use `analyze.sh` script

```shell
./bin/analyze.sh              --help      # Display help
./bin/analyze.sh              --all       # Run all tools
./bin/analyze.sh              --phpstan   # Run phpstan
./bin/analyze.sh              --fixer     # Run cs-fixer
./bin/analyze.sh              --validate  # Validate database schema
./bin/analyze.sh              --phpunit   # Run phpunit tests
```

### To run jira migrations use below command

```shell
docker-compose exec php bin/console witcher:migration --type="argument"
```

#### Available variables for command argument `type`

1. `all`
2. `project`
3. `issue`
4. `issueType`
5. `status`
6. `issueHistory`
7. `all`
8. `priority`
9. `branch`

## Important notes

1. If `all` argument was passed gitlab branches and projects will not be migrated. You'll need to match Witcher project for particular gitlab project first via API
2. If WitcherUser is not created, all migrated projects and tasks will not have assignee/reporter/creator. Only jira account ids will be set in a separate entity
3. After user is created with gitlab account, all tasks will be assigned to this user, if he has any
