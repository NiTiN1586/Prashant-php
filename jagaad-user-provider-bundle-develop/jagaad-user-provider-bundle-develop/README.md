# Jagaad User Provider Bundle

**Jagaad User Provider Bundle** is a Symfony bundle providing a reusable authentication and user management system for any
Jagaad service. It uses **Jagaad User API** to process login authentication and to provide an interface to manage Jagaad users.

## Installation

In order to integrate the bundle you will first need a working instance of **Jagaad User API**.

After that, add the following lines to your composer.json:

```json
    "repositories": [
        { "name": "jagaad/user-provider-bundle", "type": "vcs", "url": "https://gitlab.com/jagaad-team/jagaad-user-provider-bundle.git" }
    ],
    "require": {
        "jagaad/user-provider-bundle": "dev-develop",
    }
```
And run `composer install` to integrate the bundle into your application.

### Now it's a configuration time.

Create a `config/packages/jagaad_user_provider.yaml` plugin configuration file and put the following content in it:
```yaml
jagaad_user_provider:
    user_api:
        host: '%env(JAGAAD_USER_API_HOST)%'
        login_route: login
    post_login_redirect_route: post-login
```

`user_api.host` - the base hostname of the **Jagaad User API** instance you would like to use.
`user_api.login_route` - is the route name of your login page
`post_login_redirect_route` - is the route name of the page you would like a user to be taken
after the successful login.

The next step is a routing import. The bundle provides two routes for its own internal use:
`jagaad.user_provider.authentication.callback` - is used as a callback URL for Google Authentication
`jagaad.user_provider.authentication.logout` - is a generic logout route which will work automatically and requires no extra configuration

In order to import those you will just need to add the following lines:

```yaml
jagaad_user_provider:
  resource: '@JagaadUserProviderBundle/src/Resources/config/routes.yaml'
```

to your `config/routes.yaml` file.

And the last thing is a firewall configuration. Since Symfony does not allow to merge multiple security configuration, we have to add it manually.

Add a new firewall to your `config/packages/security.yaml` :

```yaml
jagaad_user_provider:
    anonymous: true
    provider: user_provider_bundle_provider
    form_login:
        login_path: app_login
        check_path: jagaad.user_provider.authentication.callback
    logout:
        path: jagaad.user_provider.authentication.logout
    guard:
        authenticators:
            - jagaad.user_provider.authenticator
```

The ony thing you will have to modify here is your login page route name / path.

And that's all. You are all set up.

### How to use

First of all you need to make sure that `HTTP://**YOUR_APP.DOMAIN**/_user-provider/authentication/callback` 
is a whitelisted callback url in a Google application used by the **Jagaad User API**.

In order to get the Google login authentication link, inject the `Jagaad\UserProviderBundle\Manager\AuthenticationManager` service 
and use a `getGoogleAuthenticationUrl()` method. Ideally to keep the application logic consistent 
this link should be placed on a login page you've configured. 

And basically that is all you need. By clicking this link the user will be redirected to the Google login form 
allowing him to select his corporate Google account.

If the login was successful the user will be authenticated and redirected to the `post_login_redirect_route` 
you have configured in `config/packages/jagaad_user_provider.yaml` before.

If login fails the user is taken back to the login page you have configured before.

Or course you might want to discover why did the login fail. In order to do this, you can inject 
a `Jagaad\UserProviderBundle\Manager\AuthenticationResultManager` service and use its `getLatestAuthenticationResult()`
method. 

If the login failed you will receive an instance 
of `Jagaad\UserProviderBundle\DataClass\AuthenticationResult\FailedAuthenticationResult` which holds the exception that 
caused the login to fail.

If the login was successful you will receive an instance
of `Jagaad\UserProviderBundle\DataClass\AuthenticationResult\SuccessfulAuthenticationResult` holding the authenticated user object.

# Features

## Custom User Processor
Every system implementing the bundle must be able to perform custom checks and mutations for user on login. In order to do this a system with 
Role Providers was implemented. By default the authenticator uses the default `jagaad.user_processor` service which returns an empty set of user roles.
If you want a different behavior, just decorate this service:
```yaml
App\Security\CustomAuthenticatedUserProcessor:
    decorates: jagaad.user_processor
```
the service must implement `process` and it's only method which takes the authenticated user as an argument by reference to be modified if needed