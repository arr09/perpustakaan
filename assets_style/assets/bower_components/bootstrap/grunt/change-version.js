#!/usr/bin/env node
'use strict';

var fs = require('fs');
var path = require('path');
var sh = require('shelljs');
sh.config.fatal = true;
var sed = sh.sed;

// Function to safely escape a string for use in RegExp
RegExp.quote = function (string) {
  if (typeof string !== 'string') return string; // Prevent non-string input
  return string.replace(/[-\\^$*+?.()|[\]{}]/g, '\\$&');
};

// Function to escape a replacement string
RegExp.quoteReplacement = function (string) {
  if (typeof string !== 'string') return string; // Prevent non-string input
  return string.replace(/[$]/g, '$$');
};

var DRY_RUN = false;

function walkAsync(directory, excludedDirectories, fileCallback, errback) {
  if (excludedDirectories.has(path.parse(directory).base)) {
    return;
  }
  fs.readdir(directory, function (err, names) {
    if (err) {
      errback(err);
      return;
    }
    names.forEach(function (name) {
      var filepath = path.join(directory, name);
      fs.lstat(filepath, function (err, stats) {
        if (err) {
          process.nextTick(errback, err);
          return;
        }
        if (stats.isSymbolicLink()) {
          return;
        } else if (stats.isDirectory()) {
          process.nextTick(walkAsync, filepath, excludedDirectories, fileCallback, errback);
        } else if (stats.isFile()) {
          process.nextTick(fileCallback, filepath);
        }
      });
    });
  });
}

function replaceRecursively(directory, excludedDirectories, allowedExtensions, original, replacement) {
  // Safely compile the RegExp to prevent ReDoS
  try {
    original = new RegExp(RegExp.quote(original), 'g');
  } catch (e) {
    console.error('Error compiling RegExp for original pattern:', e);
    process.exit(1);
  }

  replacement = RegExp.quoteReplacement(replacement);
  var updateFile = !DRY_RUN ? function (filepath) {
    if (allowedExtensions.has(path.parse(filepath).ext)) {
      sed('-i', original, replacement, filepath);
    }
  } : function (filepath) {
    if (allowedExtensions.has(path.parse(filepath).ext)) {
      console.log('FILE: ' + filepath);
    } else {
      console.log('EXCLUDED:' + filepath);
    }
  };
  walkAsync(directory, excludedDirectories, updateFile, function (err) {
    console.error('ERROR while traversing directory:');
    console.error(err);
    process.exit(1);
  });
}

function main(args) {
  if (args.length !== 2) {
    console.error('USAGE: change-version old_version new_version');
    console.error('Got arguments:', args);
    process.exit(1);
  }

  var oldVersion = args[0];
  var newVersion = args[1];

  // Validate input size and pattern to prevent ReDoS
  if (oldVersion.length > 100 || newVersion.length > 100) {
    console.error('Error: Version strings should not exceed 100 characters to prevent performance issues.');
    process.exit(1);
  }

  // Example of sanitizing and limiting unsafe characters in version strings
  var sanitizedOldVersion = oldVersion.replace(/[^a-zA-Z0-9.-]/g, '');
  var sanitizedNewVersion = newVersion.replace(/[^a-zA-Z0-9.-]/g, '');

  var EXCLUDED_DIRS = new Set([
    '.git',
    'node_modules',
    'vendor'
  ]);
  var INCLUDED_EXTENSIONS = new Set([
    '.css', '.html', '.js', '.json', '.less', '.md', '.nuspec', '.ps1', '.scss', '.txt', '.yml'
  ]);
  
  replaceRecursively('.', EXCLUDED_DIRS, INCLUDED_EXTENSIONS, sanitizedOldVersion, sanitizedNewVersion);
}

main(process.argv.slice(2));
