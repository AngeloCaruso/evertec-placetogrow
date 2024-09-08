<?php

declare(strict_types=1);

use App\Models\AccessControlList;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});

Breadcrumbs::for('profile', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Profile', route('profile'));
});

// Microsites

Breadcrumbs::for('microsites.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Users', route('microsites.index'));
});

Breadcrumbs::for('microsites.create', function (BreadcrumbTrail $trail) {
    $trail->parent('microsites.index');
    $trail->push('Create User', route('microsites.create'));
});

Breadcrumbs::for('microsites.show', function (BreadcrumbTrail $trail, Microsite $microsite) {
    $trail->parent('microsites.index');
    $trail->push($microsite->slug, route('microsites.show', $microsite));
});

Breadcrumbs::for('microsites.edit', function (BreadcrumbTrail $trail, Microsite $microsite) {
    $trail->parent('microsites.show', $microsite);
    $trail->push('Edit User', route('microsites.edit', $microsite));
});

//Payments

Breadcrumbs::for('payments.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Payments', route('payments.index'));
});

Breadcrumbs::for('payments.show', function (BreadcrumbTrail $trail, Payment $payment) {
    $trail->parent('payments.index');
    $trail->push($payment->reference, route('payments.show', $payment));
});

//Subscriptions

Breadcrumbs::for('subscriptions.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Subscriptions', route('subscriptions.index'));
});

Breadcrumbs::for('subscriptions.show', function (BreadcrumbTrail $trail, Subscription $subscription) {
    $trail->parent('subscriptions.index');
    $trail->push($subscription->reference, route('subscriptions.show', $subscription));
});

// Users

Breadcrumbs::for('users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Users', route('users.index'));
});

Breadcrumbs::for('users.create', function (BreadcrumbTrail $trail) {
    $trail->parent('users.index');
    $trail->push('Create User', route('users.create'));
});

Breadcrumbs::for('users.show', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('users.index');
    $trail->push($user->email, route('users.show', $user));
});

Breadcrumbs::for('users.edit', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('users.show', $user);
    $trail->push('Edit User', route('users.edit', $user));
});

// Roles

Breadcrumbs::for('roles.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Roles', route('roles.index'));
});

Breadcrumbs::for('roles.create', function (BreadcrumbTrail $trail) {
    $trail->parent('roles.index');
    $trail->push('Create Role', route('roles.create'));
});

Breadcrumbs::for('roles.show', function (BreadcrumbTrail $trail, Role $role) {
    $trail->parent('roles.index');
    $trail->push($role->name, route('roles.show', $role));
});

Breadcrumbs::for('roles.edit', function (BreadcrumbTrail $trail, Role $role) {
    $trail->parent('roles.show', $role);
    $trail->push('Edit Role', route('roles.edit', $role));
});

// Access Control List

Breadcrumbs::for('acl.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Access Control List', route('acl.index'));
});

Breadcrumbs::for('acl.create', function (BreadcrumbTrail $trail) {
    $trail->parent('acl.index');
    $trail->push('Create Access Control List', route('acl.create'));
});

Breadcrumbs::for('acl.show', function (BreadcrumbTrail $trail, AccessControlList $acl) {
    $trail->parent('acl.index');
    $trail->push($acl->user->email, route('acl.edit', $acl));
});

Breadcrumbs::for('acl.edit', function (BreadcrumbTrail $trail, AccessControlList $acl) {
    $trail->parent('acl.show', $acl);
    $trail->push('Edit Access Control List', route('acl.edit', $acl));
});
