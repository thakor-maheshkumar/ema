<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// EMA Users List
Breadcrumbs::for('ema_user', function ($trail) {
    $trail->parent('home');
    $trail->push('View All Users', route('ema_users'));
});

// Distributors
Breadcrumbs::for('distributor', function ($trail) {
    $trail->parent('home');
    $trail->push('Distributors', route('distributor'));
});

// Treatment Centre
Breadcrumbs::for('treatment-centre-list', function ($trail) {
    $trail->parent('home');
    $trail->push('Treatment Centres', route('treatment-centre-list'));
});

// Devices
Breadcrumbs::for('devices', function ($trail) {
    $trail->parent('home');
    $trail->push('Devices', route('devices'));
});

// List of Treatment center file
Breadcrumbs::for('list-treatmentcentre-file', function ($trail) {
    $trail->parent('home');
    $trail->push('Treatment Data', route('list-treatmentcentre-file'));
});

// Diagnostic Data
Breadcrumbs::for('diagnosticData', function ($trail) {
    $trail->parent('home');
    $trail->push('Diagnostic Data', route('diagnosticData'));
});

// Audit Log
Breadcrumbs::for('audit-list', function ($trail) {
    $trail->parent('home');
    $trail->push('Audit Log', route('audit-list'));
});

// Media library
Breadcrumbs::for('media-library', function ($trail) {
    $trail->parent('home');
    $trail->push('Media Library', route('media-library'));
});

// Email Templates
Breadcrumbs::for('emailTemplate', function ($trail) {
    $trail->parent('home');
    $trail->push('Email Templates', route('emailTemplate'));
});

// SMS Templates
Breadcrumbs::for('SMSTemplate', function ($trail) {
    $trail->parent('home');
    $trail->push('SMS Templates', route('SMSTemplate'));
});

Breadcrumbs::for('help', function ($trail) {
    $trail->parent('home');
    $trail->push('Help', route('help'));
});