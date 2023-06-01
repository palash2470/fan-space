/*
 Copyright (c) 2007-2017, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://cksource.com/ckfinder/license
 */

var config = {};

$config['accessControl'][] = Array(
     'role' => '*',
     'resourceType' => 'Images',
     'folder' => '/Logos',
 
     'FOLDER_VIEW'        => true,
     'FOLDER_CREATE'      => true,
     'FOLDER_RENAME'      => true,
     'FOLDER_DELETE'      => true,
 
     'FILE_VIEW'          => true,
     'FILE_CREATE'        => false,
     'FILE_RENAME'        => false,
     'FILE_DELETE'        => true,
 
     'IMAGE_RESIZE'        => true,
     'IMAGE_RESIZE_CUSTOM' => true
 );

// Set your configuration options below.

// Examples:
// config.language = 'pl';
// config.skin = 'jquery-mobile';

CKFinder.define( config );
