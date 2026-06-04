<?php

/**
 * Forward all root requests to the Laravel public directory.
 * Required for XAMPP / shared hosting where the document root is the project folder.
 */
require __DIR__.'/public/index.php';
