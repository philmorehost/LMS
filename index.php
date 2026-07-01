<?php

/**
 * ─────────────────────────────────────────────────────────────────────────────
 *  LMS Root Entry Point
 * ─────────────────────────────────────────────────────────────────────────────
 *  This file exists because your web server's document root points to the
 *  Laravel project root instead of the /public folder.
 *
 *  It transparently forwards ALL requests through public/index.php so the
 *  application runs correctly without moving any files.
 * ─────────────────────────────────────────────────────────────────────────────
 */

// Point PHP to the correct script name so Laravel builds URLs correctly
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'index.php';
$_SERVER['SCRIPT_NAME']     = '/public/index.php';
$_SERVER['PHP_SELF']        = '/public/index.php';

// Load through public/index.php
require __DIR__ . '/public/index.php';
